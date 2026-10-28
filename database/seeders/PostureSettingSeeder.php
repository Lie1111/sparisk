<?php

namespace Database\Seeders;

use App\Models\PostureSetting;
use Illuminate\Database\Seeder;

/**
 * Seeds the SATA Posture Reference Table v1 — Evidence Based.
 *
 * DEVIATION ANGLE OUTPUT: every row stores the reference the engine will
 * subtract from the measured angle, together with its provenance:
 *   - reference_value : the applicable reference (NULL for an evidence gap)
 *   - normal_min/max  : the published reference band, used as the tolerance
 *                       for On Point / Near Point / Off Point
 *   - reference_level : published | sata_validated | individual_baseline
 *   - reference_source: where the value came from, so the deviation is traceable
 *   - sex             : the sex the reference applies to, when the evidence
 *                       requires it (Pelvic Tilt, Knee)
 *
 * NO arbitrary cut-off is invented: a parameter with no Level 1 / Level 2
 * reference is stored as individual_baseline with a NULL reference value.
 */
class PostureSettingSeeder extends Seeder
{
    private const AGE_GROUPS = ['6-8', '9-12', '13-18', '19-49', '50+'];

    /**
     * Frontal/back definitional neutrals. These are SATA-validated references
     * (a level line is 0°). The tolerance for the point bands comes from the
     * locked SATA severity thresholds for the region.
     *
     * [view, section, label, [6-8, 9-12, 13-18, 19-49, 50+], interpretation]
     */
    private const FRONT = [
        ['A1',  'Body Alignment',          [0, 0, 0, 0, 0], 'Vertical neutral'],
        ['A2',  'Head Tilt',               [0, 0, 0, 0, 0], 'Horizontal neutral'],
        ['A3',  'Shoulder Alignment',      [0, 0, 0, 0, 0], 'Level shoulders'],
        ['A4',  'Axillae Alignment',       [0, 0, 0, 0, 0], 'Symmetrical'],
        ['A5',  'Ribcage Tilt',            [0, 0, 0, 0, 0], 'Level rib cage'],
        ['A6',  'Trunk Alignment',         [0, 0, 0, 0, 0], 'Midline'],
        ['A7',  'Pelvic Tilt / Obliquity', [0, 0, 0, 0, 0], 'Level pelvis'],
        ['A8',  'Right Knee Angle',        [0, 0, 0, 0, 0], 'Neutral frontal alignment'],
        ['A9',  'Left Knee Angle',         [0, 0, 0, 0, 0], 'Neutral frontal alignment'],
        ['A10', 'Right Foot Rotation',     [5, 7, 7, 7, 8], 'External foot progression'],
        ['A11', 'Left Foot Rotation',      [5, 7, 7, 7, 8], 'External foot progression'],
    ];

    private const BACK = [
        ['B1', 'Body Alignment',          [0, 0, 0, 0, 0], 'Vertical neutral'],
        ['B2', 'Head Tilt',               [0, 0, 0, 0, 0], 'Head level'],
        ['B3', 'Shoulder Alignment',      [0, 0, 0, 0, 0], 'Shoulders level'],
        ['B4', 'Axillae Alignment',       [0, 0, 0, 0, 0], 'Symmetrical'],
        ['B5', 'Trunk Alignment',         [0, 0, 0, 0, 0], 'Midline'],
        ['B6', 'Pelvic Tilt / Obliquity', [0, 0, 0, 0, 0], 'Pelvis level'],
        ['B7', 'Knee Alignment',          [0, 0, 0, 0, 0], 'Neutral'],
        // B8 uses the same forward-axis foot progression measurement as
        // A10/A11, so it takes the same external-progression reference instead
        // of 0°. The old ankle→heel reading measured foot anatomy (~47°).
        ['B8', 'Feet Alignment',          [5, 7, 7, 7, 8], 'External foot progression'],
    ];

    /**
     * Region tolerance for the SATA-validated definitional neutrals, taken
     * from the locked severity thresholds (mild band). Used as the ± tolerance
     * around the reference for the point bands.
     */
    private const NEUTRAL_REGION = [
        'A1' => 'trunk', 'A2' => 'head', 'A3' => 'shoulder', 'A4' => 'trunk',
        'A5' => 'trunk', 'A6' => 'trunk', 'A7' => 'pelvic', 'A8' => 'knee',
        'A9' => 'knee', 'A10' => 'foot', 'A11' => 'foot',
        'B1' => 'trunk', 'B2' => 'head', 'B3' => 'shoulder', 'B4' => 'trunk',
        'B5' => 'trunk', 'B6' => 'pelvic', 'B7' => 'knee', 'B8' => 'foot',
    ];

    public function run(): void
    {
        // Legacy rows were written with an empty-string sex ('') instead of
        // NULL. Only NULL is neutral for the engine, so those rows are dead
        // duplicates that shadow the real reference. Drop them first and let
        // the seeders below rebuild every parameter with sex = NULL.
        PostureSetting::where('sex', '')->delete();

        // C4/D4 used to resolve a sex-specific published pelvic-tilt norm. The
        // measured axis is the hip-over-ankle plumb, so the sex rows no longer
        // apply; leaving them behind would make the engine prefer the stale
        // 12.6°/14.7° values over the definitional neutral below.
        PostureSetting::whereIn('section', ['C4', 'D4'])->whereNotNull('sex')->delete();

        $this->seedNeutralReferences();
        $this->seedReferenceTable();
        $this->seedEvidenceGaps();
    }

    /**
     * Frontal and back definitional neutrals (SATA validated, reference 0° or
     * the foot-progression neutral).
     */
    private function seedNeutralReferences(): void
    {
        foreach ([['front', self::FRONT], ['back', self::BACK]] as [$view, $params]) {
            foreach ($params as [$section, $label, $references, $interpretation]) {
                $region = self::NEUTRAL_REGION[$section] ?? 'trunk';
                $tolerance = (float) config("sparisk.severity_thresholds.{$region}.mild", 5);

                foreach (self::AGE_GROUPS as $i => $ageGroup) {
                    $value = (float) $references[$i];

                    PostureSetting::updateOrCreate(
                        ['view' => $view, 'section' => $section, 'age_group' => $ageGroup, 'sex' => null],
                        [
                            'label' => $label,
                            'reference_value' => $value,
                            'normal_min' => round($value - $tolerance, 2),
                            'normal_max' => round($value + $tolerance, 2),
                            'reference_source' => 'SATA validated — definitional neutral alignment',
                            'reference_level' => 'sata_validated',
                            'sex_if_required' => false,
                            'interpretation' => $interpretation,
                        ]
                    );
                }
            }
        }
    }

    /**
     * The locked sagittal reference table for D1–D7. One parameter expands to
     * its measurement sections, its age groups and (only where the evidence
     * requires it) its male/female variants.
     */
    private function seedReferenceTable(): void
    {
        $table = config('sparisk.posture_reference_table', []);

        foreach ($table as $parameter) {
            $sexRequired = (bool) ($parameter['sex_if_required'] ?? false);

            foreach (self::AGE_GROUPS as $ageGroup) {
                $group = $parameter['groups'][$ageGroup] ?? null;
                if ($group === null) {
                    continue;
                }

                // Either a single reference or sex-keyed variants. A PHP array
                // cannot hold a NULL key (it collapses to ""), so the single
                // case is handled explicitly to keep `sex` NULL for neutral rows.
                $isSingle = isset($group['level']);
                $sexes = $isSingle ? [null] : array_keys($group);

                foreach ($sexes as $sex) {
                    $body = $isSingle ? $group : $group[$sex];

                    foreach ($parameter['sections'] as $section) {
                        PostureSetting::updateOrCreate(
                            [
                                'view' => $section['view'],
                                'section' => $section['section'],
                                'age_group' => $ageGroup,
                                'sex' => $sex,
                            ],
                            [
                                'label' => $parameter['label'],
                                'reference_value' => $body['value'] ?? null,
                                'normal_min' => $body['min'] ?? null,
                                'normal_max' => $body['max'] ?? null,
                                'reference_source' => $body['source'] ?? 'SATA Evidence Gap — Individual Baseline',
                                'reference_level' => $body['level'],
                                'sex_if_required' => $sexRequired,
                                'interpretation' => $this->interpretationFor($body['level']),
                            ]
                        );
                    }
                }
            }
        }
    }

    /**
     * Any measurement section the locked table does not carry is an evidence
     * gap: it is stored as individual_baseline with a NULL reference value so
     * the engine reports change from the individual's own first assessment and
     * never invents a population cut-off.
     */
    private function seedEvidenceGaps(): void
    {
        $measurements = config('sparisk.measurements', []);

        foreach ($measurements as $section => $def) {
            foreach (self::AGE_GROUPS as $ageGroup) {
                $exists = PostureSetting::where('view', $def['view'])
                    ->where('section', $section)
                    ->where('age_group', $ageGroup)
                    ->exists();

                if ($exists) {
                    continue;
                }

                PostureSetting::updateOrCreate(
                    ['view' => $def['view'], 'section' => $section, 'age_group' => $ageGroup, 'sex' => null],
                    [
                        'label' => $def['label'],
                        'reference_value' => null,
                        'normal_min' => null,
                        'normal_max' => null,
                        'reference_source' => 'SATA Evidence Gap — Individual Baseline',
                        'reference_level' => 'individual_baseline',
                        'sex_if_required' => false,
                        'interpretation' => $this->interpretationFor('individual_baseline'),
                    ]
                );
            }
        }
    }

    private function interpretationFor(string $level): string
    {
        return match ($level) {
            'published' => 'Compared against the applicable published reference for the age group.',
            'sata_validated' => 'Compared against the SATA-validated reference.',
            default => 'No population reference yet — tracked as change from the individual baseline.',
        };
    }
}
