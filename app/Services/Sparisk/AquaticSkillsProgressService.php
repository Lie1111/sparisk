<?php

namespace App\Services\Sparisk;

use App\Models\Patient;
use App\Models\SkillAssessment;

/**
 * Assembles the Student Progress Dashboard for a patient.
 *
 * The client asked for three views, and this service produces the data behind
 * all of them so the app, the web page and the PDF cannot drift apart:
 *
 *   1. overall progress across sessions (line)
 *   2. achievement in each of the four categories (bars)
 *   3. the overall score as a percentage (headline figure)
 *
 * A "session" is one sitting of the checklist. The first sitting is the
 * baseline every later one is measured against.
 */
class AquaticSkillsProgressService
{
    /**
     * The dashboard payload for a patient, newest sitting last.
     *
     * @return array<string, mixed>
     */
    public function forPatient(Patient $patient): array
    {
        $assessments = $patient->skillAssessments()
            ->orderBy('assessed_on')
            ->orderBy('id')
            ->get();

        $sessions = $assessments->values()->map(fn (SkillAssessment $a, int $i) => [
            'id' => $a->id,
            'label' => $i === 0 ? 'Baseline' : "Session {$i}",
            'index' => $i,
            'date' => $a->assessed_on?->toDateString(),
            'score' => $a->total_score,
            'max' => $a->max_score,
            'percentage' => $a->percentage,
            'category_scores' => $a->categoryPercentages(),
        ])->all();

        $latest = $assessments->last();
        $baseline = $assessments->first();

        return [
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
            ],
            'catalogue' => [
                'version' => config('sparisk.aquatic_skills.version'),
                'categories' => config('sparisk.aquatic_skills.categories'),
                'item_count' => count(config('sparisk.aquatic_skills.items', [])),
            ],
            'sessions' => $sessions,
            'categories' => $latest?->categoryPercentages() ?? $this->emptyCategories(),
            'current' => $latest === null ? null : [
                'id' => $latest->id,
                'label' => 'Session ' . ($assessments->count() - 1),
                'date' => $latest->assessed_on?->toDateString(),
                'score' => $latest->total_score,
                'max' => $latest->max_score,
                'percentage' => $latest->percentage,
                'achieved' => $latest->total_score,
                'missed' => max(0, $latest->max_score - $latest->total_score),
            ],
            'improvement' => $this->improvement($sessions),
        ];
    }

    /**
     * Movement from the baseline sitting to the latest one.
     *
     * With a single sitting there is nothing to compare against, so the deltas
     * are null rather than a misleading zero.
     *
     * @param  array<int, array<string, mixed>>  $sessions
     * @return array<string, mixed>
     */
    public function improvement(array $sessions): array
    {
        $baseline = $sessions[0] ?? null;
        $latest = $sessions === [] ? null : $sessions[array_key_last($sessions)];

        $baselineFigure = $baseline === null ? null : [
            'label' => $baseline['label'],
            'score' => $baseline['score'],
            'max' => $baseline['max'],
            'percentage' => $baseline['percentage'],
        ];

        if (count($sessions) < 2) {
            return [
                'has_comparison' => false,
                'items' => null,
                'percentage_points' => null,
                'baseline' => $baselineFigure,
                'latest' => null,
            ];
        }

        return [
            'has_comparison' => true,
            'items' => $latest['score'] - $baseline['score'],
            'percentage_points' => round($latest['percentage'] - $baseline['percentage'], 1),
            'baseline' => $baselineFigure,
            'latest' => [
                'label' => $latest['label'],
                'score' => $latest['score'],
                'max' => $latest['max'],
                'percentage' => $latest['percentage'],
            ],
        ];
    }

    /**
     * The four categories at zero, so a patient with no sittings still renders
     * an empty chart rather than a missing one.
     *
     * @return array<int, array<string, mixed>>
     */
    private function emptyCategories(): array
    {
        return array_map(function (string $label, string $key) {
            $items = collect(config('sparisk.aquatic_skills.items', []))
                ->where('category', $key);

            return [
                'key' => $key,
                'label' => $label,
                'achieved' => 0,
                'total' => $items->count(),
                'percentage' => 0.0,
            ];
        }, config('sparisk.aquatic_skills.categories', []), array_keys(config('sparisk.aquatic_skills.categories', [])));
    }
}
