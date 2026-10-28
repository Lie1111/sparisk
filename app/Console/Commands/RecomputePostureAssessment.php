<?php

namespace App\Console\Commands;

use App\Models\PostureAssessment;
use App\Services\Sparisk\SpariskAquaticRecommendationEngine;
use App\Services\Sparisk\SpariskDecisionEngine;
use App\Services\Sparisk\SpariskInterpretationEngine;
use App\Services\Sparisk\SpariskMassageRecommendationEngine;
use App\Services\Sparisk\SpariskMeasurementEngine;
use App\Services\Sparisk\SpariskPersonalProgramEngine;
use App\Services\Sparisk\SpariskSagittalAngleCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Re-derive a stored assessment with the current measurement definitions.
 *
 * Version 1 stored side-view plumb angles with the raw image sign, so the left
 * profile flipped every sagittal direction, and it let the left capture
 * overwrite the shared clinical angles (side_cva / side_kyphosis /
 * side_lordosis). Version 1 also stored B8 as the ankle→heel angle, which
 * reported foot anatomy instead of alignment.
 *
 * This command rebuilds those values exactly once per assessment, then re-runs
 * the measurement, interpretation and decision engines. New assessments are
 * stored as version 2 by the API, so they are skipped unless --force is used.
 */
class RecomputePostureAssessment extends Command
{
    protected $signature = 'posture:recompute
        {assessment : Posture assessment id}
        {--dry-run : Print the corrected values without writing anything}
        {--force : Re-run even when the assessment is already version 2}';

    protected $description = 'Rebuild a stored posture assessment with the current measurement definitions';

    public function handle(
        SpariskMeasurementEngine $measurements,
        SpariskInterpretationEngine $interpretation,
        SpariskDecisionEngine $decision,
        SpariskAquaticRecommendationEngine $aquatic,
        SpariskMassageRecommendationEngine $massage,
        SpariskPersonalProgramEngine $program,
        SpariskSagittalAngleCalculator $geometry,
    ): int {
        $assessment = PostureAssessment::with(['patient', 'measurements', 'images'])->find($this->argument('assessment'));

        if ($assessment === null) {
            $this->error('Assessment not found.');

            return self::FAILURE;
        }

        $version = (int) ($assessment->measurement_version ?? 1);

        if ($version >= 2 && ! $this->option('force')) {
            $this->info("Assessment {$assessment->id} already uses measurement version 2. Use --force to re-run it.");

            return self::SUCCESS;
        }

        $before = [
            'score' => $assessment->overall_score,
            'classification' => $assessment->posture_classification,
            'suspected' => $assessment->suspected_pattern,
            'secondary' => $assessment->secondary_pattern,
            'confidence' => $assessment->confidence_level,
        ];

        $values = $assessment->measurements
            ->mapWithKeys(fn ($m) => [$m->section => (float) ($m->measured_angle ?? $m->value)])
            ->all();

        if ($values === []) {
            $this->error('Assessment has no stored measurements to rebuild.');

            return self::FAILURE;
        }

        $images = $assessment->images->keyBy('view');
        $aspect = fn (?string $view): float => $this->imageAspect($images->get($view)?->image_path);
        $landmarks = fn (?string $view): array => (array) ($images->get($view)?->landmarks ?? []);

        $rightLandmarks = $landmarks('right_side');
        $leftLandmarks = $landmarks('left_side');
        $rightFacing = $geometry->facing($rightLandmarks, 'right_side');
        $leftFacing = $geometry->facing($leftLandmarks, 'left_side');

        if ($version < 2) {
            // Resolve the raw image sign to the anatomical front.
            foreach (['C1', 'C2', 'C3', 'C4', 'C6'] as $section) {
                if (isset($values[$section])) {
                    $values[$section] = round($values[$section] * $rightFacing, 2);
                }
            }

            foreach (['D1', 'D2', 'D3', 'D4', 'D6'] as $section) {
                if (isset($values[$section])) {
                    $values[$section] = round($values[$section] * $leftFacing, 2);
                }
            }

            // B8 now shares the A10/A11 foot-progression axis.
            if (isset($values['A10'], $values['A11'])) {
                $values['B8'] = round(($values['A10'] + $values['A11']) / 2, 2);
            }
        }

        // Rebuild the shared clinical angles from the right-side reference
        // view. They used to be overwritten by the left capture in the payload.
        if ($rightLandmarks !== []) {
            $r = fn (int $i) => $rightLandmarks[$i] ?? null;
            $ear = $r(8);
            $shoulder = $r(12);
            $hip = $r(24);
            $knee = $r(26);
            $rightAspect = $aspect('right_side');

            if ($ear && $shoulder) {
                $values['side_cva'] = $geometry->cva($ear, $shoulder, $rightAspect);
            }

            if ($ear && $shoulder && $hip) {
                $values['side_kyphosis'] = $geometry->curve($ear, $shoulder, $hip, $rightAspect);
            }

            if ($shoulder && $hip && $knee) {
                $values['side_lordosis'] = $geometry->curve($shoulder, $hip, $knee, $rightAspect);
            }
        }

        $changed = $this->changedValues($assessment, $values);

        $this->table(
            ['Section', 'Stored', 'Rebuilt'],
            collect($changed)->map(fn ($new, $section) => [
                $section,
                $this->fmt($assessment->measurements->firstWhere('section', $section)?->measured_angle),
                $this->fmt($new),
            ])->values()->all(),
        );

        if ($this->option('dry-run')) {
            $this->warn('Dry run — nothing was written.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($assessment, $values, $measurements, $interpretation, $decision, $aquatic, $massage, $program) {
            $assessment->measurements()->delete();
            $assessment->classifications()->delete();
            $assessment->exerciseRecommendations()->delete();
            $assessment->massageRecommendations()->delete();
            $assessment->weeklyPrograms()->delete();

            $measurements->process($values, $assessment);

            $score = $measurements->calculateOverallScore($assessment);
            $interpretation->interpret($assessment);
            $interpretation->generateSymmetry($assessment);
            $interpretation->generateOverallInterpretation($assessment);
            $primaryFindings = $interpretation->generatePrimaryFindings($assessment);
            $clinicalSummary = $interpretation->generateClinicalSummary($assessment);

            $decision->decide($assessment);

            $assessment->update([
                'measurement_version' => 2,
                'overall_score' => $score,
                'overall_status' => $score >= 80 ? 'GOOD' : ($score >= 60 ? 'MODERATE' : 'NEEDS IMPROVEMENT'),
                'clinical_summary' => $clinicalSummary,
                'primary_findings' => json_encode($primaryFindings),
                'patient_problem' => $interpretation->patientProblem($assessment),
            ]);

            $aquatic->recommend($assessment);
            $massage->recommend($assessment);
            $program->generate($assessment);
        });

        $assessment->refresh();

        $this->newLine();
        $this->table(['Field', 'Before', 'After'], [
            ['Posture score', $before['score'], $assessment->overall_score],
            ['Classification', $before['classification'], $assessment->posture_classification],
            ['Suspected pattern', $before['suspected'], $assessment->suspected_pattern],
            ['Secondary pattern', $before['secondary'], $assessment->secondary_pattern],
            ['Confidence', $before['confidence'], $assessment->confidence_level],
        ]);

        $this->info("Assessment {$assessment->id} rebuilt with measurement version 2.");

        return self::SUCCESS;
    }

    /**
     * Sections whose rebuilt value differs from the stored measured angle.
     */
    private function changedValues(PostureAssessment $assessment, array $values): array
    {
        $stored = $assessment->measurements
            ->mapWithKeys(fn ($m) => [$m->section => (float) ($m->measured_angle ?? $m->value)])
            ->all();

        return collect($values)
            ->filter(fn ($value, $section) => ! isset($stored[$section]) || abs($stored[$section] - $value) > 0.005)
            ->all();
    }

    private function imageAspect(?string $path): float
    {
        if ($path === null || ! Storage::disk('public')->exists($path)) {
            return 1.0;
        }

        $size = @getimagesize(Storage::disk('public')->path($path));

        if ($size === false || $size[1] === 0) {
            return 1.0;
        }

        return $size[0] / $size[1];
    }

    private function fmt(mixed $value): string
    {
        return $value === null ? '—' : number_format((float) $value, 2);
    }
}
