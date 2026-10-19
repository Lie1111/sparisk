<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SPARISK Knowledge Base (SKB)
    |--------------------------------------------------------------------------
    | This is the intellectual property core of SATA. All clinical rules,
    | thresholds, classifications, and recommendation mappings are defined here.
    */

    'version' => '1.1',

    /*
    |--------------------------------------------------------------------------
    | Neurodevelopmental Profile (SATA NeuroPosture)
    |--------------------------------------------------------------------------
    | Captured once after Add Participant and stored on the participant profile.
    | This personalises instructions and support recommendations. It does not
    | create or confirm a clinical diagnosis. The condition list lives here so
    | the mobile app, the web form and the API always offer the same options.
    */
    'neuro_profiles' => [
        'neurotypical' => ['label' => 'Neurotypical'],
        'neurodivergent' => ['label' => 'Neurodivergent'],
    ],

    'neuro_conditions' => [
        'asd' => ['label' => 'Autism Spectrum Disorder (ASD)'],
        'adhd' => ['label' => 'ADHD'],
        'gdd' => ['label' => 'Global Developmental Delay (GDD)'],
        'dcd' => ['label' => 'Developmental Coordination Disorder (DCD)'],
        'intellectual_disability' => ['label' => 'Intellectual Disability'],
        'down_syndrome' => ['label' => 'Down Syndrome'],
        'dyslexia' => ['label' => 'Dyslexia or Learning Difference'],
        'other' => ['label' => 'Other'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Age Groups (SATA Age-Based Posture Reference)
    |--------------------------------------------------------------------------
    | Fixed age bands used to resolve the age-based reference value for each
    | posture parameter. A patient's age is mapped to one of these groups.
    */
    'age_groups' => [
        '6-8'   => ['label' => '6–8 years',  'min' => 6,  'max' => 8],
        '9-12'  => ['label' => '9–12 years', 'min' => 9,  'max' => 12],
        '13-18' => ['label' => '13–18 years','min' => 13, 'max' => 18],
        '19-49' => ['label' => '19–49 years','min' => 19, 'max' => 49],
        '50+'   => ['label' => '50+ years',  'min' => 50, 'max' => null],
    ],

    /*
    |--------------------------------------------------------------------------
    | BMI Reference (SATA Age + Gender Classification)
    |--------------------------------------------------------------------------
    | BMI is calculated centrally as Weight (kg) / Height (m)². The category is
    | never hard-coded per screen: it is resolved by SpariskBmiEngine from the
    | editable `bmi_references` table, which falls back to this block when the
    | table is empty, so references can be updated without touching the engine.
    |
    | Children (age 6–18) are classified by EXACT age + gender; adults (19+)
    | use the shared adult cut-offs where male and female are identical.
    |
    | Child cut-offs are the official WHO 2007 BMI-for-age (5–19 years) z-score
    | values at each exact year of age:
    |   sd-3 severe thinness · sd-2 thinness · sd+1 overweight · sd+2 obesity
    | Bands are contiguous and `max` is exclusive.
    |
    | NOTE: these values ship flagged unverified until SATA confirms them
    | against the approved clinical reference dataset.
    */
    'bmi_references' => [
        'child_age_groups' => ['6-8', '9-12', '13-18'],
        'child_source' => 'WHO 2007 BMI-for-age (5–19 years) z-scores',
        'child' => [
            'male' => [
                6  => ['sd-3' => 12.1, 'sd-2' => 13.0, 'sd+1' => 16.8, 'sd+2' => 18.5],
                7  => ['sd-3' => 12.3, 'sd-2' => 13.1, 'sd+1' => 17.0, 'sd+2' => 19.0],
                8  => ['sd-3' => 12.4, 'sd-2' => 13.3, 'sd+1' => 17.4, 'sd+2' => 19.7],
                9  => ['sd-3' => 12.6, 'sd-2' => 13.5, 'sd+1' => 17.9, 'sd+2' => 20.5],
                10 => ['sd-3' => 12.8, 'sd-2' => 13.7, 'sd+1' => 18.5, 'sd+2' => 21.4],
                11 => ['sd-3' => 13.1, 'sd-2' => 14.1, 'sd+1' => 19.2, 'sd+2' => 22.5],
                12 => ['sd-3' => 13.4, 'sd-2' => 14.5, 'sd+1' => 19.9, 'sd+2' => 23.6],
                13 => ['sd-3' => 13.8, 'sd-2' => 14.9, 'sd+1' => 20.8, 'sd+2' => 24.8],
                14 => ['sd-3' => 14.3, 'sd-2' => 15.5, 'sd+1' => 21.8, 'sd+2' => 25.9],
                15 => ['sd-3' => 14.7, 'sd-2' => 16.0, 'sd+1' => 22.7, 'sd+2' => 27.0],
                16 => ['sd-3' => 15.1, 'sd-2' => 16.5, 'sd+1' => 23.5, 'sd+2' => 27.9],
                17 => ['sd-3' => 15.4, 'sd-2' => 16.9, 'sd+1' => 24.3, 'sd+2' => 28.6],
                18 => ['sd-3' => 15.7, 'sd-2' => 17.3, 'sd+1' => 24.9, 'sd+2' => 29.2],
            ],
            'female' => [
                6  => ['sd-3' => 11.7, 'sd-2' => 12.7, 'sd+1' => 17.0, 'sd+2' => 19.2],
                7  => ['sd-3' => 11.8, 'sd-2' => 12.7, 'sd+1' => 17.3, 'sd+2' => 19.8],
                8  => ['sd-3' => 11.9, 'sd-2' => 12.9, 'sd+1' => 17.7, 'sd+2' => 20.6],
                9  => ['sd-3' => 12.1, 'sd-2' => 13.1, 'sd+1' => 18.3, 'sd+2' => 21.5],
                10 => ['sd-3' => 12.4, 'sd-2' => 13.5, 'sd+1' => 19.0, 'sd+2' => 22.6],
                11 => ['sd-3' => 12.7, 'sd-2' => 13.9, 'sd+1' => 19.9, 'sd+2' => 23.7],
                12 => ['sd-3' => 13.2, 'sd-2' => 14.4, 'sd+1' => 20.8, 'sd+2' => 25.0],
                13 => ['sd-3' => 13.6, 'sd-2' => 14.9, 'sd+1' => 21.8, 'sd+2' => 26.2],
                14 => ['sd-3' => 14.0, 'sd-2' => 15.4, 'sd+1' => 22.7, 'sd+2' => 27.3],
                15 => ['sd-3' => 14.4, 'sd-2' => 15.9, 'sd+1' => 23.5, 'sd+2' => 28.2],
                16 => ['sd-3' => 14.6, 'sd-2' => 16.2, 'sd+1' => 24.1, 'sd+2' => 28.9],
                17 => ['sd-3' => 14.7, 'sd-2' => 16.4, 'sd+1' => 24.5, 'sd+2' => 29.3],
                18 => ['sd-3' => 14.7, 'sd-2' => 16.4, 'sd+1' => 24.8, 'sd+2' => 29.5],
            ],
        ],
        'child_categories' => [
            ['from' => null, 'to' => 'sd-3', 'category' => 'Severely Underweight'],
            ['from' => 'sd-3', 'to' => 'sd-2', 'category' => 'Underweight'],
            ['from' => 'sd-2', 'to' => 'sd+1', 'category' => 'Normal'],
            ['from' => 'sd+1', 'to' => 'sd+2', 'category' => 'Overweight'],
            ['from' => 'sd+2', 'to' => null, 'category' => 'Obesity'],
        ],
        'adult_source' => 'SATA adult BMI classification',
        'adult' => [
            ['min' => 0.0,  'max' => 18.5, 'category' => 'Underweight'],
            ['min' => 18.5, 'max' => 25.0, 'category' => 'Normal'],
            ['min' => 25.0, 'max' => 30.0, 'category' => 'Overweight'],
            ['min' => 30.0, 'max' => 35.0, 'category' => 'Obesity I'],
            ['min' => 35.0, 'max' => 40.0, 'category' => 'Obesity II'],
            ['min' => 40.0, 'max' => null, 'category' => 'Obesity III'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SATA Fixed Severity Bands
    |--------------------------------------------------------------------------
    | Deviation = ABS(Clinical Angle - Age Reference) is mapped to a band.
    | Bands are evaluated top-down; `max` is exclusive.
    */
    'severity_bands' => [
        ['min' => 0,  'max' => 5,   'level' => 'normal',   'label' => 'NORMAL',   'color' => 'green'],
        ['min' => 5,  'max' => 10,  'level' => 'mild',     'label' => 'MILD',     'color' => 'yellow'],
        ['min' => 10, 'max' => 20,  'level' => 'moderate', 'label' => 'MODERATE', 'color' => 'orange'],
        ['min' => 20, 'max' => null, 'level' => 'severe',  'label' => 'SEVERE',   'color' => 'red'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Alignment Status (user-facing wording)
    |--------------------------------------------------------------------------
    | Plain-language labels shown to non-clinical users instead of the internal
    | severity levels. `review` is used when a measurement cannot be trusted
    | (missing landmark, impossible angle, missing reference).
    */
    'alignment_statuses' => [
        'normal'   => ['label' => 'Aligned',              'color' => 'green',  'correction' => 'ON POINT'],
        'mild'     => ['label' => 'Slightly Misaligned',  'color' => 'yellow', 'correction' => 'MINOR CORRECTION'],
        'moderate' => ['label' => 'Misaligned',           'color' => 'orange', 'correction' => 'CORRECTION NEEDED'],
        'severe'   => ['label' => 'Clearly Misaligned',   'color' => 'red',    'correction' => 'PRIORITY CORRECTION'],
        'baseline' => ['label' => 'Baseline / Change Tracking', 'color' => 'blue', 'correction' => 'BASELINE TRACKING'],
        'review'   => ['label' => 'Check Measurement',    'color' => 'grey',   'correction' => 'REVIEW'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Alignment Status Descriptions
    |--------------------------------------------------------------------------
    | Short explanation of what each alignment status means, used as the
    | per-measurement interpretation when a measurement is within range or
    | cannot be relied upon.
    */
    'alignment_status_descriptions' => [
        'normal'   => 'The position is within the target range.',
        'review'   => 'This measurement may need to be reviewed — the landmark or angle could not be validated.',
        'missing'  => 'This measurement was not captured and needs to be reviewed.',
    ],

    /*
    |--------------------------------------------------------------------------
    | SATA Reference Levels (Evidence Hierarchy)
    |--------------------------------------------------------------------------
    | SATA Posture Reference Table v1 — Evidence Based.
    | Every measured section (D1–D7 and the frontal/back parameters) resolves its
    | reference through this hierarchy. The engine NEVER invents a cut-off: when
    | no Level 1 or Level 2 reference exists the parameter is reported against the
    | individual's own baseline (Level 3 — Evidence Gap).
    |
    |   - published           : Level 1 — normative value from a published study.
    |   - sata_validated      : Level 2 — reference validated by SATA (for example
    |                           a definitional neutral such as 0° for a level line).
    |   - individual_baseline : Level 3 — Evidence Gap. The first assessment is stored
    |                           as the baseline and later assessments are reported as
    |                           change from baseline. No population cut-off is created.
    */
    'reference_levels' => [
        'published'           => ['rank' => 1, 'label' => 'Published Reference',                'approved' => true],
        'sata_validated'      => ['rank' => 2, 'label' => 'SATA Validated Reference',           'approved' => true],
        'individual_baseline' => ['rank' => 3, 'label' => 'Individual Baseline (Evidence Gap)', 'approved' => false],
    ],

    /*
    |--------------------------------------------------------------------------
    | SATA Posture Reference Table v1 — Evidence Based (D1–D7)
    |--------------------------------------------------------------------------
    | LOCKED DEVELOPER REFERENCE — SAGITTAL POSTURE — DEVIATION ANGLE OUTPUT.
    |
    | Output standard: D1–D7 are displayed as deviation angles.
    |   0°      = the measured value is exactly at the applicable reference.
    |   +/-     = the direction and magnitude of deviation from that reference.
    |
    | Core formula: Deviation Angle = Measured Angle − Applicable Reference Value.
    |
    | Every parameter resolves its reference per SATA age group (and, only where
    | the evidence requires it, per sex). A parameter is either:
    |   - 'published'           : Level 1 — a real published/normative value. The
    |                             reference centre (`value`) and the published
    |                             band (`min`/`max`) are stored. The band's own
    |                             half-width is the tolerance used for the
    |                             On Point / Near Point / Off Point output — NO
    |                             arbitrary ±5° / ±10° cut-off is invented.
    |   - 'individual_baseline' : Level 3 — Evidence Gap. No population reference
    |                             exists, so the first assessment is stored as the
    |                             individual's baseline and later assessments are
    |                             reported as change from that baseline.
    |
    | `sections` lists the app measurement sections this parameter feeds, so the
    | seeder can expand one locked parameter into the right per-view rows.
    | `sex_if_required` marks the parameters whose evidence is sex-dependent.
    */
    'posture_reference_table' => [
        'D1' => [
            'label' => 'Body Alignment',
            'region' => 'alignment',
            'sex_if_required' => false,
            'sections' => [
                ['section' => 'C1', 'view' => 'right_side'],
                ['section' => 'D1', 'view' => 'left_side'],
            ],
            'groups' => [
                '6-8' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — body alignment (0° neutral)'],
                '9-12' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — body alignment (0° neutral)'],
                '13-18' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — body alignment (0° neutral)'],
                '19-49' => [
                    'level' => 'published', 'value' => 1.73, 'min' => 0.79, 'max' => 2.67,
                    'source' => 'Published systematic review — adult body-alignment reference (1.73 ± 0.94)**',
                ],
                '50+' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — body alignment (0° neutral)'],
            ],
        ],
        'D2' => [
            'label' => 'Head',
            'region' => 'head',
            'sex_if_required' => false,
            'sections' => [
                ['section' => 'side_cva', 'view' => 'right_side'],
                ['section' => 'side_cva', 'view' => 'left_side'],
            ],
            'groups' => [
                '6-8' => [
                    'level' => 'published', 'value' => 49.3, 'min' => 44.0, 'max' => 54.6,
                    'source' => '2025 paediatric photogrammetry — head posture angle (7–9 y)†',
                ],
                '9-12' => [
                    'level' => 'published', 'value' => 48.9, 'min' => 43.7, 'max' => 54.1,
                    'source' => '2025 paediatric photogrammetry — head posture angle (10–14 y)†',
                ],
                '13-18' => [
                    'level' => 'published', 'value' => 51.5, 'min' => 46.4, 'max' => 56.6,
                    'source' => '2025 paediatric photogrammetry — head posture angle (15–17 y)†',
                ],
                '19-49' => [
                    'level' => 'published', 'value' => 51.42, 'min' => 46.55, 'max' => 56.29,
                    'source' => 'Published systematic review — adult head posture angle (51.42 ± 4.87)**',
                ],
                '50+' => ['level' => 'sata_validated', 'value' => 48.0, 'min' => 43.1, 'max' => 52.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — CVA / head reference (≥50 y, 48°)'],
            ],
        ],
        'D3' => [
            'label' => 'Shoulder',
            'region' => 'shoulder',
            'sex_if_required' => false,
            'sections' => [
                ['section' => 'C3', 'view' => 'right_side'],
                ['section' => 'D3', 'view' => 'left_side'],
            ],
            'groups' => [
                '6-8' => [
                    'level' => 'published', 'value' => 64.5, 'min' => 52.7, 'max' => 76.3,
                    'source' => '2025 paediatric photogrammetry — shoulder angle (7–9 y)†',
                ],
                '9-12' => [
                    'level' => 'published', 'value' => 69.2, 'min' => 57.3, 'max' => 81.1,
                    'source' => '2025 paediatric photogrammetry — shoulder angle (10–14 y)†',
                ],
                '13-18' => [
                    'level' => 'published', 'value' => 72.4, 'min' => 58.9, 'max' => 85.9,
                    'source' => '2025 paediatric photogrammetry — shoulder angle (15–17 y)†',
                ],
                '19-49' => ['level' => 'individual_baseline'],
                '50+' => ['level' => 'individual_baseline'],
            ],
        ],
        'D4' => [
            'label' => 'Pelvic Tilt',
            'region' => 'pelvis',
            'sex_if_required' => true,
            'sections' => [
                ['section' => 'C4', 'view' => 'right_side'],
                ['section' => 'D4', 'view' => 'left_side'],
            ],
            'groups' => [
                '6-8' => [
                    'male' => ['level' => 'published', 'value' => 12.6, 'min' => 7.8, 'max' => 17.4, 'source' => '2025 paediatric photogrammetry — pelvic tilt, boys (7–17 y)†'],
                    'female' => ['level' => 'published', 'value' => 14.7, 'min' => 9.8, 'max' => 19.6, 'source' => '2025 paediatric photogrammetry — pelvic tilt, girls (7–17 y)†'],
                ],
                '9-12' => [
                    'male' => ['level' => 'published', 'value' => 12.6, 'min' => 7.8, 'max' => 17.4, 'source' => '2025 paediatric photogrammetry — pelvic tilt, boys (7–17 y)†'],
                    'female' => ['level' => 'published', 'value' => 14.7, 'min' => 9.8, 'max' => 19.6, 'source' => '2025 paediatric photogrammetry — pelvic tilt, girls (7–17 y)†'],
                ],
                '13-18' => [
                    'male' => ['level' => 'published', 'value' => 12.6, 'min' => 7.8, 'max' => 17.4, 'source' => '2025 paediatric photogrammetry — pelvic tilt, boys (7–17 y)†'],
                    'female' => ['level' => 'published', 'value' => 14.7, 'min' => 9.8, 'max' => 19.6, 'source' => '2025 paediatric photogrammetry — pelvic tilt, girls (7–17 y)†'],
                ],
                '19-49' => [
                    'level' => 'published', 'value' => -12.26, 'min' => -18.07, 'max' => -6.45,
                    'source' => 'Published systematic review — adult pelvic tilt (−12.26 ± 5.81)**',
                ],
                '50+' => ['level' => 'individual_baseline'],
            ],
        ],
        'D5' => [
            'label' => 'Knee',
            'region' => 'lower_limb',
            'sex_if_required' => true,
            'sections' => [
                ['section' => 'C5', 'view' => 'right_side'],
                ['section' => 'D5', 'view' => 'left_side'],
            ],
            'groups' => [
                '6-8' => [
                    'male' => ['level' => 'published', 'value' => 173.1, 'min' => 167.1, 'max' => 179.1, 'source' => '2025 paediatric photogrammetry — knee angle, boys (7–9 y)†'],
                    'female' => ['level' => 'published', 'value' => 179.0, 'min' => 178.5, 'max' => 179.5, 'source' => '2025 paediatric photogrammetry — knee angle, girls (7–9 y)†'],
                ],
                '9-12' => [
                    'male' => ['level' => 'published', 'value' => 172.3, 'min' => 167.7, 'max' => 176.9, 'source' => '2025 paediatric photogrammetry — knee angle, boys (10–14 y)†'],
                    'female' => ['level' => 'published', 'value' => 174.9, 'min' => 170.1, 'max' => 179.7, 'source' => '2025 paediatric photogrammetry — knee angle, girls (10–14 y)†'],
                ],
                '13-18' => [
                    'male' => ['level' => 'published', 'value' => 174.0, 'min' => 169.5, 'max' => 178.5, 'source' => '2025 paediatric photogrammetry — knee angle, boys (15–17 y)†'],
                    'female' => ['level' => 'published', 'value' => 173.1, 'min' => 166.9, 'max' => 179.3, 'source' => '2025 paediatric photogrammetry — knee angle, girls (15–17 y)†'],
                ],
                '19-49' => ['level' => 'individual_baseline'],
                '50+' => ['level' => 'individual_baseline'],
            ],
        ],
        'D6' => [
            'label' => 'Tibia Alignment',
            'region' => 'lower_limb',
            'sex_if_required' => false,
            'sections' => [
                ['section' => 'C6', 'view' => 'right_side'],
                ['section' => 'D6', 'view' => 'left_side'],
            ],
            'groups' => [
                '6-8' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — tibia alignment (0° neutral)'],
                '9-12' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — tibia alignment (0° neutral)'],
                '13-18' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — tibia alignment (0° neutral)'],
                '19-49' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — tibia alignment (0° neutral)'],
                '50+' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — tibia alignment (0° neutral)'],
            ],
        ],
        'D7' => [
            'label' => 'Foot/Ankle Alignment',
            'region' => 'lower_limb',
            'sex_if_required' => false,
            'sections' => [
                ['section' => 'C7', 'view' => 'right_side'],
                ['section' => 'D7', 'view' => 'left_side'],
            ],
            'groups' => [
                '6-8' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — foot/ankle angle (0° neutral)'],
                '9-12' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — foot/ankle angle (0° neutral)'],
                '13-18' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — foot/ankle angle (0° neutral)'],
                '19-49' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — foot/ankle angle (0° neutral)'],
                '50+' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — foot/ankle angle (0° neutral)'],
            ],
        ],

        /*
        | The side-view parameters below were previously Evidence Gaps (no entry
        | in this table => the seeder stored a NULL reference). Their SATA v1.1
        | fixed references are added here so the engine can compute a deviation
        | instead of falling back to baseline/change tracking.
        |
        | Band: the SATA v1.1 locked classification defines NORMAL as a 0–4.9°
        | deviation for every parameter, so the tolerance is ±4.9° (on_point =
        | NORMAL, near_point = MILD, off_point = MODERATE/SEVERE).
        */
        'head_shift' => [
            'label' => 'Head Shift',
            'region' => 'head',
            'sex_if_required' => false,
            'sections' => [
                ['section' => 'C2', 'view' => 'right_side'],
                ['section' => 'D2', 'view' => 'left_side'],
            ],
            'groups' => [
                '6-8' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — head shift (0° neutral)'],
                '9-12' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — head shift (0° neutral)'],
                '13-18' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — head shift (0° neutral)'],
                '19-49' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — head shift (0° neutral)'],
                '50+' => ['level' => 'sata_validated', 'value' => 0.0, 'min' => -4.9, 'max' => 4.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — head shift (0° neutral)'],
            ],
        ],
        'thoracic_kyphosis' => [
            'label' => 'Upper-Back Posture Indicator',
            'region' => 'thoracic',
            'sex_if_required' => false,
            'sections' => [
                ['section' => 'side_kyphosis', 'view' => 'right_side'],
            ],
            'groups' => [
                '6-8' => ['level' => 'sata_validated', 'value' => 40.0, 'min' => 35.1, 'max' => 44.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — thoracic kyphosis (6–8 y, 40°)'],
                '9-12' => ['level' => 'sata_validated', 'value' => 44.0, 'min' => 39.1, 'max' => 48.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — thoracic kyphosis (9–12 y, 44°)'],
                '13-18' => ['level' => 'sata_validated', 'value' => 45.0, 'min' => 40.1, 'max' => 49.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — thoracic kyphosis (13–18 y, 45°)'],
                '19-49' => ['level' => 'sata_validated', 'value' => 40.0, 'min' => 35.1, 'max' => 44.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — thoracic kyphosis (19–49 y, 40°)'],
                '50+' => ['level' => 'sata_validated', 'value' => 46.0, 'min' => 41.1, 'max' => 50.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — thoracic kyphosis (≥50 y, 46°)'],
            ],
        ],
        'lumbar_lordosis' => [
            'label' => 'Lower-Back Posture Indicator',
            'region' => 'pelvis',
            'sex_if_required' => false,
            'sections' => [
                ['section' => 'side_lordosis', 'view' => 'right_side'],
            ],
            'groups' => [
                '6-8' => ['level' => 'sata_validated', 'value' => 54.0, 'min' => 49.1, 'max' => 58.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — lumbar lordosis (6–8 y, 54°)'],
                '9-12' => ['level' => 'sata_validated', 'value' => 55.0, 'min' => 50.1, 'max' => 59.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — lumbar lordosis (9–12 y, 55°)'],
                '13-18' => ['level' => 'sata_validated', 'value' => 55.0, 'min' => 50.1, 'max' => 59.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — lumbar lordosis (13–18 y, 55°)'],
                '19-49' => ['level' => 'sata_validated', 'value' => 45.0, 'min' => 40.1, 'max' => 49.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — lumbar lordosis (19–49 y, 45°)'],
                '50+' => ['level' => 'sata_validated', 'value' => 40.0, 'min' => 35.1, 'max' => 44.9, 'source' => 'SATA Age-Based Posture Reference v1.1 — lumbar lordosis (≥50 y, 40°)'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sagittal Classification Parameters
    |--------------------------------------------------------------------------
    | Kyphosis, Lordosis, Flat Back and Sway Back are classification outputs
    | derived from a COMBINATION of the D1–D7 sagittal deviation angles, never
    | from a single angle. These are the parameter keys used by the decision
    | rules. `translation` highlights the one parameter that separates Sway Back
    | (forward/anterior pelvic translation) from Flat Back (posterior pelvic
    | deviation without dominant forward translation).
    */
    'sagittal_parameters' => [
        'D1' => ['label' => 'Body Alignment',        'sections' => ['C1', 'D1']],
        'D2' => ['label' => 'Head',                  'sections' => ['side_cva']],
        'D3' => ['label' => 'Shoulder',              'sections' => ['C3', 'D3']],
        'D4' => ['label' => 'Pelvic Tilt',           'sections' => ['C4', 'D4']],
        'D5' => ['label' => 'Knee',                  'sections' => ['C5', 'D5']],
        'D6' => ['label' => 'Tibia Alignment',       'sections' => ['C6', 'D6']],
        'D7' => ['label' => 'Foot / Ankle Alignment','sections' => ['C7', 'D7']],
    ],

    /*
    |--------------------------------------------------------------------------
    | SATA Posture Output Status (user-facing)
    |--------------------------------------------------------------------------
    | The ONLY user-facing alignment status for a deviation angle. On Point /
    | Near Point / Off Point may only be generated when the parameter has an
    | APPROVED cut-off (a Level 1 or Level 2 reference). When no approved
    | reference exists the engine reports `baseline_tracking` and never forces a
    | point status.
    |
    | `deviation_bands` maps the size of the deviation to an output status. Bands
    | are expressed as a multiple of the reference's own tolerance (the standard
    | deviation published with the reference, or half the published range). This
    | avoids arbitrary universal cut-offs such as ±5° / ±10°.
    */
    'posture_output_statuses' => [
        'on_point'          => ['label' => 'On Point',                    'color' => 'green',  'correction' => 'ON POINT'],
        'near_point'        => ['label' => 'Near Point',                  'color' => 'yellow', 'correction' => 'NEAR POINT'],
        'off_point'         => ['label' => 'Off Point',                   'color' => 'orange', 'correction' => 'OFF POINT'],
        'baseline_tracking' => ['label' => 'Baseline / Change Tracking',  'color' => 'blue',   'correction' => 'BASELINE TRACKING'],
        'review'            => ['label' => 'Review',                      'color' => 'grey',   'correction' => 'REVIEW'],
    ],

    'deviation_bands' => [
        // |deviation| <= tolerance            => on_point
        // tolerance < |deviation| <= 2·tol     => near_point
        // |deviation| > 2·tolerance            => off_point
        'on_point'   => 1.0,
        'near_point' => 2.0,
    ],

    // Internal severity level kept for the decision engine, derived from the
    // output status so the existing rule conditions (normal/mild/moderate/
    // severe) continue to work without hard-coded cut-offs.
    'status_to_level' => [
        'on_point'          => 'normal',
        'near_point'        => 'mild',
        'off_point'         => 'moderate',
        'baseline_tracking' => 'baseline',
        'review'            => 'review',
    ],

    'level_to_status' => [
        'normal'   => 'on_point',
        'mild'     => 'near_point',
        'moderate' => 'off_point',
        'severe'   => 'off_point',
        'baseline' => 'baseline_tracking',
        'review'   => 'review',
    ],

    /*
    |--------------------------------------------------------------------------
    | Confidence Levels (user-facing wording)
    |--------------------------------------------------------------------------
    | Plain-language wording for the decision engine's confidence level. The
    | internal values stay HIGH / MODERATE / LOW in the database.
    */
    'confidence_labels' => [
        'HIGH'     => 'High Confidence',
        'MODERATE' => 'Moderate Confidence',
        'LOW'      => 'Low Confidence',
        'REVIEW'   => 'Review Required',
    ],

    /*
    |--------------------------------------------------------------------------
    | Posture Pattern Labels (user-facing wording)
    |--------------------------------------------------------------------------
    | The classification keys stay exactly as they are used by the decision
    | rules and the database. These labels are only what the user reads, and
    | are worded as screening patterns rather than clinical diagnoses.
    */
    'pattern_labels' => [
        'Normal'                => 'No Dominant Postural Pattern Detected',
        'Kyphosis'              => 'Possible Kyphotic Pattern',
        'Lordosis'              => 'Possible Lordotic Pattern',
        'Kyphosis-Lordosis'     => 'Possible Kyphosis–Lordosis Pattern',
        'Swayback'              => 'Possible Swayback Pattern',
        'Flat Back'             => 'Possible Flatback Pattern',
        'Flexed Knee'           => 'Possible Flexed-Knee Pattern',
        'Genu Recurvatum'       => 'Possible Genu Recurvatum Pattern',
        'Forward Head'          => 'Possible Forward-Head Pattern',
        'Rounded Shoulder'      => 'Possible Rounded-Shoulder Pattern',
        'Pelvic Obliquity'      => 'Possible Pelvic Obliquity Pattern',
        'Lower Limb Deformity'  => 'Possible Lower-Limb Alignment Pattern',
        'Mild Asymmetry'        => 'Possible Mild Asymmetry',
        'Global Postural Imbalance' => 'Possible Global Postural Imbalance',
        'Upper Body Instability'    => 'Possible Upper-Body Instability',
        'Lower Body Compensation'   => 'Possible Lower-Body Compensation',
        'Mixed Pattern'         => 'Possible Mixed Postural Pattern',
    ],

    /*
    |--------------------------------------------------------------------------
    | Measurement Definitions
    |--------------------------------------------------------------------------
    | Maps section codes to labels, views, body region and the angle
    | interpretation mode. Reference values NO LONGER live here: each section
    | resolves its reference at run time through the SATA reference hierarchy
    | (see `posture_reference_table` + `reference_levels`). `reference_level` is
    | the DEFAULT level used when no reference row is stored for a parameter.
    |
    | `angle_mode`:
    |   - `angle`       : full 0-360 circle. Deviation is normalised to the
    |                     shortest arc (max 180°). Used for measures where the
    |                     direction (left/right, forward/back) is meaningful.
    |   - `orientation` : line orientation modulo 180°. A value of -179.97° and
    |                     +0.03° describe the SAME alignment, so they must not be
    |                     treated as a severe deviation. Used for measures built
    |                     from `atan2` line angles (shoulder/hip lines, knee
    |                     angles, foot rotation).
    */
    'measurements' => [
        'A1'  => ['view' => 'front', 'label' => 'Body Alignment', 'region' => 'alignment', 'reference_level' => 'sata_validated'],
        'A2'  => ['view' => 'front', 'label' => 'Head Tilt', 'region' => 'head', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'A3'  => ['view' => 'front', 'label' => 'Shoulder Alignment', 'region' => 'shoulder', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'A4'  => ['view' => 'front', 'label' => 'Axillae Alignment', 'region' => 'thoracic', 'reference_level' => 'sata_validated'],
        'A5'  => ['view' => 'front', 'label' => 'Ribcage Tilt', 'region' => 'thoracic', 'reference_level' => 'sata_validated'],
        'A6'  => ['view' => 'front', 'label' => 'Trunk Alignment', 'region' => 'thoracic', 'reference_level' => 'sata_validated'],
        'A7'  => ['view' => 'front', 'label' => 'Estimated Pelvic Alignment', 'region' => 'pelvis', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'A8'  => ['view' => 'front', 'label' => 'Right Knee Angle', 'region' => 'lower_limb', 'side' => 'right', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'A9'  => ['view' => 'front', 'label' => 'Left Knee Angle', 'region' => 'lower_limb', 'side' => 'left', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'A10' => ['view' => 'front', 'label' => 'Right Foot Rotation', 'region' => 'lower_limb', 'side' => 'right', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'A11' => ['view' => 'front', 'label' => 'Left Foot Rotation', 'region' => 'lower_limb', 'side' => 'left', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'B1'  => ['view' => 'back', 'label' => 'Body Alignment', 'region' => 'alignment', 'reference_level' => 'sata_validated'],
        'B2'  => ['view' => 'back', 'label' => 'Head Tilt', 'region' => 'head', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'B3'  => ['view' => 'back', 'label' => 'Shoulder Alignment', 'region' => 'shoulder', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'B4'  => ['view' => 'back', 'label' => 'Axillae Alignment', 'region' => 'thoracic', 'reference_level' => 'sata_validated'],
        'B5'  => ['view' => 'back', 'label' => 'Trunk Alignment', 'region' => 'thoracic', 'reference_level' => 'sata_validated'],
        'B6'  => ['view' => 'back', 'label' => 'Estimated Pelvic Alignment', 'region' => 'pelvis', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'B7'  => ['view' => 'back', 'label' => 'Knees', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'B8'  => ['view' => 'back', 'label' => 'Feet', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'reference_level' => 'sata_validated'],
        'C1'  => ['view' => 'right_side', 'label' => 'Body Alignment', 'region' => 'alignment', 'reference_level' => 'individual_baseline'],
        'C2'  => ['view' => 'right_side', 'label' => 'Head Shift', 'region' => 'head', 'reference_level' => 'individual_baseline'],
        'C3'  => ['view' => 'right_side', 'label' => 'Shoulder Angle', 'region' => 'shoulder', 'reference_level' => 'individual_baseline'],
        'C4'  => ['view' => 'right_side', 'label' => 'Estimated Pelvic Alignment', 'region' => 'pelvis', 'reference_level' => 'individual_baseline'],
        'C5'  => ['view' => 'right_side', 'label' => 'Knee', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'reference_level' => 'individual_baseline'],
        'C6'  => ['view' => 'right_side', 'label' => 'Tibia', 'region' => 'lower_limb', 'reference_level' => 'individual_baseline'],
        'C7'  => ['view' => 'right_side', 'label' => 'Foot Angle', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'reference_level' => 'individual_baseline'],
        'D1'  => ['view' => 'left_side', 'label' => 'Body Alignment', 'region' => 'alignment', 'reference_level' => 'individual_baseline'],
        'D2'  => ['view' => 'left_side', 'label' => 'Head Shift', 'region' => 'head', 'reference_level' => 'individual_baseline'],
        'D3'  => ['view' => 'left_side', 'label' => 'Shoulder Angle', 'region' => 'shoulder', 'reference_level' => 'individual_baseline'],
        'D4'  => ['view' => 'left_side', 'label' => 'Estimated Pelvic Alignment', 'region' => 'pelvis', 'reference_level' => 'individual_baseline'],
        'D5'  => ['view' => 'left_side', 'label' => 'Knee', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'reference_level' => 'individual_baseline'],
        'D6'  => ['view' => 'left_side', 'label' => 'Tibia', 'region' => 'lower_limb', 'reference_level' => 'individual_baseline'],
        'D7'  => ['view' => 'left_side', 'label' => 'Foot Angle', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'reference_level' => 'individual_baseline'],
        'side_cva'       => ['view' => 'right_side', 'label' => 'CVA / Head Reference', 'region' => 'head', 'reference_level' => 'sata_validated'],
        'side_kyphosis'  => ['view' => 'right_side', 'label' => 'Upper-Back Posture Indicator', 'region' => 'thoracic', 'reference_level' => 'sata_validated'],
        'side_lordosis'  => ['view' => 'right_side', 'label' => 'Lower-Back Posture Indicator', 'region' => 'pelvis', 'reference_level' => 'sata_validated'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Measurement Semantics (dynamic interpretation templates)
    |--------------------------------------------------------------------------
    | For each section, the sentence used to explain a deviation. `:degree`
    | is replaced with the degree adverb (slightly / clearly / significantly)
    | in the interpretation, and removed when generating the short Position
    | Note. `positive` is used when the normalised deviation is above the age
    | reference, `negative` when it is below.
    */
    'measurement_semantics' => [
        'A1'  => ['positive' => 'The body is shifted:degree to the right.', 'negative' => 'The body is shifted:degree to the left.'],
        'A2'  => ['positive' => 'The head is tilted:degree to the right.', 'negative' => 'The head is tilted:degree to the left.'],
        'A3'  => ['positive' => 'The left shoulder is:degree higher than the right shoulder.', 'negative' => 'The right shoulder is:degree higher than the left shoulder.'],
        'A4'  => ['positive' => 'The upper trunk is shifted:degree to the right of the pelvis.', 'negative' => 'The upper trunk is shifted:degree to the left of the pelvis.'],
        'A5'  => ['positive' => 'The ribcage is tilted:degree to the right.', 'negative' => 'The ribcage is tilted:degree to the left.'],
        'A6'  => ['positive' => 'The trunk is leaning:degree to the right.', 'negative' => 'The trunk is leaning:degree to the left.'],
        'A7'  => ['positive' => 'The left side of the pelvis is:degree higher than the right side.', 'negative' => 'The right side of the pelvis is:degree higher than the left side.'],
        'A8'  => ['positive' => 'The right knee is:degree extended beyond neutral.', 'negative' => 'The right knee is:degree flexed or deviating from neutral.'],
        'A9'  => ['positive' => 'The left knee is:degree extended beyond neutral.', 'negative' => 'The left knee is:degree flexed or deviating from neutral.'],
        'A10' => ['positive' => 'The right foot is rotated:degree outward.', 'negative' => 'The right foot is rotated:degree inward.'],
        'A11' => ['positive' => 'The left foot is rotated:degree outward.', 'negative' => 'The left foot is rotated:degree inward.'],
        'B1'  => ['positive' => 'The body is shifted:degree to the right.', 'negative' => 'The body is shifted:degree to the left.'],
        'B2'  => ['positive' => 'The head is tilted:degree to the right.', 'negative' => 'The head is tilted:degree to the left.'],
        'B3'  => ['positive' => 'The left shoulder is:degree higher than the right shoulder.', 'negative' => 'The right shoulder is:degree higher than the left shoulder.'],
        'B4'  => ['positive' => 'The upper trunk is shifted:degree to the right of the pelvis.', 'negative' => 'The upper trunk is shifted:degree to the left of the pelvis.'],
        'B5'  => ['positive' => 'The trunk is leaning:degree to the right.', 'negative' => 'The trunk is leaning:degree to the left.'],
        'B6'  => ['positive' => 'The left side of the pelvis is:degree higher than the right side.', 'negative' => 'The right side of the pelvis is:degree higher than the left side.'],
        'B7'  => ['positive' => 'The knees are:degree extended beyond neutral.', 'negative' => 'The knees are:degree flexed or deviating from neutral.'],
        'B8'  => ['positive' => 'The feet are rotated:degree outward.', 'negative' => 'The feet are rotated:degree inward.'],
        'C1'  => ['positive' => 'The body is leaning:degree forward (anteriorly).', 'negative' => 'The body is leaning:degree backward (posteriorly).'],
        'C2'  => ['positive' => 'The head is positioned:degree forward of the shoulders (forward head).', 'negative' => 'The head is positioned:degree behind the shoulders.'],
        'C3'  => ['positive' => 'The shoulder is:degree rounded forward (protracted).', 'negative' => 'The shoulder is:degree pulled back (retracted).'],
        'C4'  => ['positive' => 'The pelvis is tilted:degree anteriorly (forward).', 'negative' => 'The pelvis is tilted:degree posteriorly (backward).'],
        'C5'  => ['positive' => 'The knee is:degree extended beyond neutral.', 'negative' => 'The knee is:degree flexed.'],
        'C6'  => ['positive' => 'The lower leg is leaning:degree forward.', 'negative' => 'The lower leg is leaning:degree backward.'],
        'C7'  => ['positive' => 'The foot is angled:degree upward (toes up).', 'negative' => 'The foot is angled:degree downward (toes down).'],
        'D1'  => ['positive' => 'The body is leaning:degree forward (anteriorly).', 'negative' => 'The body is leaning:degree backward (posteriorly).'],
        'D2'  => ['positive' => 'The head is positioned:degree forward of the shoulders (forward head).', 'negative' => 'The head is positioned:degree behind the shoulders.'],
        'D3'  => ['positive' => 'The shoulder is:degree rounded forward (protracted).', 'negative' => 'The shoulder is:degree pulled back (retracted).'],
        'D4'  => ['positive' => 'The pelvis is tilted:degree anteriorly (forward).', 'negative' => 'The pelvis is tilted:degree posteriorly (backward).'],
        'D5'  => ['positive' => 'The knee is:degree extended beyond neutral.', 'negative' => 'The knee is:degree flexed.'],
        'D6'  => ['positive' => 'The lower leg is leaning:degree forward.', 'negative' => 'The lower leg is leaning:degree backward.'],
        'D7'  => ['positive' => 'The foot is angled:degree upward (toes up).', 'negative' => 'The foot is angled:degree downward (toes down).'],
        'side_cva'      => ['positive' => 'The head sits:degree more upright than the target.', 'negative' => 'The head sits:degree further forward than the target.'],
        'side_kyphosis' => ['positive' => 'The upper-back curve is:degree greater than the target.', 'negative' => 'The upper-back curve is:degree flatter than the target.'],
        'side_lordosis' => ['positive' => 'The lower-back curve is:degree greater than the target.', 'negative' => 'The lower-back curve is:degree flatter than the target.'],
        '_default' => ['positive' => 'The measured :label is:degree outside the target alignment.', 'negative' => 'The measured :label is:degree outside the target alignment.'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Deviation Degree Adverbs
    |--------------------------------------------------------------------------
    | Maps an alignment status level to the adverb injected into a semantics
    | template. `normal` and `review` deliberately use an empty string.
    */
    'deviation_adverbs' => [
        'normal'   => '',
        'mild'     => ' slightly',
        'moderate' => ' clearly',
        'severe'   => ' significantly',
        'review'   => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Left / Right Symmetry Pairs
    |--------------------------------------------------------------------------
    | Pairs compared to determine whether the two sides are symmetrical.
    */
    'symmetry_pairs' => [
        ['left' => 'A9', 'right' => 'A8', 'label' => 'Knee alignment'],
        ['left' => 'A11', 'right' => 'A10', 'label' => 'Foot rotation'],
        ['left' => 'D5', 'right' => 'C5', 'label' => 'Knee angle'],
        ['left' => 'D4', 'right' => 'C4', 'label' => 'Pelvic tilt'],
        ['left' => 'D3', 'right' => 'C3', 'label' => 'Shoulder angle'],
        ['left' => 'D6', 'right' => 'C6', 'label' => 'Tibia alignment'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symmetry Comparison
    |--------------------------------------------------------------------------
    | The difference between the left and right value of a pair is mapped to a
    | symmetry state. `slight` and `marked` are the exclusive upper bounds of
    | the difference in degrees.
    */
    'symmetry_thresholds' => [
        'slight' => 5,
        'marked' => 10,
    ],

    'symmetry_states' => [
        'symmetrical'            => ['label' => 'Even',            'color' => 'green'],
        'slightly_asymmetrical'  => ['label' => 'Slightly Uneven', 'color' => 'yellow'],
        'markedly_asymmetrical'  => ['label' => 'Clearly Uneven',  'color' => 'orange'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Severity Thresholds
    |--------------------------------------------------------------------------
    | Defines when a deviation becomes mild, moderate, or severe.
    */
    'severity_thresholds' => [
        'head' => [
            'mild'    => 10,
            'moderate' => 20,
            'severe'  => 30,
        ],
        'shoulder' => [
            'mild'    => 5,
            'moderate' => 15,
            'severe'  => 25,
        ],
        'pelvic' => [
            'mild'    => 5,
            'moderate' => 15,
            'severe'  => 25,
        ],
        'foot' => [
            'mild'    => 10,
            'moderate' => 20,
            'severe'  => 30,
        ],
        'knee' => [
            'mild'    => 5,
            'moderate' => 10,
            'severe'  => 15,
        ],
        'trunk' => [
            'mild'    => 3,
            'moderate' => 6,
            'severe'  => 10,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Posture Classifications
    |--------------------------------------------------------------------------
    */
    'classifications' => [
        'Normal' => [
            'description' => 'Postural alignment is within the target range',
            'key_indicators' => [],
        ],
        'Kyphosis' => [
            'description' => 'Excessive thoracic curvature (hunched upper back)',
            'key_indicators' => ['D1_forward', 'D2_forward', 'D3_forward'],
        ],
        'Lordosis' => [
            'description' => 'Excessive lumbar curvature (swayback)',
            'key_indicators' => ['D4_anterior'],
        ],
        'Kyphosis-Lordosis' => [
            'description' => 'Combined excessive thoracic and lumbar curvature',
            'key_indicators' => ['D1_forward', 'D2_forward', 'D3_forward', 'D4_anterior'],
        ],
        'Flat Back' => [
            'description' => 'Reduced spinal curves',
            'key_indicators' => ['D4_posterior', 'D6'],
        ],
        'Swayback' => [
            'description' => 'Posterior pelvic shift with anterior upper trunk',
            'key_indicators' => ['D1_forward', 'D3_forward', 'D5_extension'],
        ],
        'Flexed Knee' => [
            'description' => 'The knee is not fully extended in standing alignment',
            'key_indicators' => ['C5_low', 'D5_low', 'A8_low', 'A9_low'],
        ],
        'Genu Recurvatum' => [
            'description' => 'The knee extends beyond neutral alignment',
            'key_indicators' => ['C5_high', 'D5_high'],
        ],
        'Forward Head' => [
            'description' => 'Head positioned forward of shoulders',
            'key_indicators' => ['C2_high', 'D2_high'],
        ],
        'Rounded Shoulder' => [
            'description' => 'Shoulders protracted forward',
            'key_indicators' => ['C3_high', 'D3_high'],
        ],
        'Pelvic Obliquity' => [
            'description' => 'Uneven pelvic height',
            'key_indicators' => ['A7_high', 'B6_high'],
        ],
        'Lower Limb Deformity' => [
            'description' => 'Knee and/or foot alignment issues',
            'key_indicators' => ['A8_high', 'A9_high', 'A10_high', 'A11_high'],
        ],
        'Mild Asymmetry' => [
            'description' => 'Slight bilateral differences',
            'key_indicators' => [],
        ],
        'Global Postural Imbalance' => [
            'description' => 'Multiple systems affected across head, trunk, and lower limbs',
            'key_indicators' => ['multi_region'],
        ],
        'Upper Body Instability' => [
            'description' => 'Head and shoulder control issues',
            'key_indicators' => ['C2_high', 'C3_high', 'D2_high', 'D3_high'],
        ],
        'Lower Body Compensation' => [
            'description' => 'Pelvic and foot compensatory patterns',
            'key_indicators' => ['C4_high', 'D4_high', 'C7_high', 'D7_high'],
        ],
        'Mixed Pattern' => [
            'description' => 'Combination of multiple postural deviations',
            'key_indicators' => ['multi_class'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SPARISK Classification Rules
    |--------------------------------------------------------------------------
    | A posture type is only confirmed when the COMBINATION of measurements
    | supports it. Each rule declares:
    |   - `requires` : sections that must be present and reliable. If any is
    |                  missing or flagged for review the rule cannot be decided.
    |   - `all`      : every condition must match.
    |   - `any`      : at least one condition must match.
    | A condition matches when the section's alignment level is in `level` AND,
    | when given, its deviation direction (above/below the age reference)
    | matches `direction`. The direction is what separates Kyphosis (curve
    | above target) from Flatback (curve below target).
    |
    | A rule only produces a confirmed classification when it matches at least
    | `classification_policy.min_evidence` independent measurements with a
    | confidence of at least `classification_policy.min_confidence`. Otherwise
    | the pattern is reported as a suspicion and posture stays UNCLASSIFIED.
    */
    'decision_rules' => [
        [
            'id' => 'KYPHOSIS',
            'classification' => 'Kyphosis',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'C1', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D1', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'side_cva', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'C3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Kyphotic Posture — forward trunk translation with a rounded shoulder/forward head pattern.',
        ],
        [
            'id' => 'LORDOSIS',
            'classification' => 'Lordosis',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'C4', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D4', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Lordotic Posture — anterior pelvic deviation with an increased lumbar curve.',
        ],
        [
            'id' => 'KYPHOSIS_LORDOSIS',
            'classification' => 'Kyphosis-Lordosis',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'C1', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D1', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'side_cva', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'C3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'any2' => [
                ['section' => 'C4', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D4', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Combined kyphosis and lordosis — forward upper trunk together with an anterior pelvic deviation.',
        ],
        [
            'id' => 'FLATBACK',
            'classification' => 'Flat Back',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'C4', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D4', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'C6', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D6', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'none' => [
                ['section' => 'C1', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D1', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'C3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Flat back — reduced lumbar curve with a posterior pelvic deviation and no dominant forward translation.',
        ],
        [
            'id' => 'SWAYBACK',
            'classification' => 'Swayback',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'C1', 'direction' => 'above', 'level' => ['moderate', 'severe']],
                ['section' => 'D1', 'direction' => 'above', 'level' => ['moderate', 'severe']],
            ],
            'any2' => [
                ['section' => 'C3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'C5', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D5', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Swayback — forward pelvic/trunk translation with an altered upper trunk and lower-limb extension.',
        ],
        [
            'id' => 'FLEXED_KNEE',
            'classification' => 'Flexed Knee',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'C5', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D5', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'A8', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'A9', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Flexed knee — the knee is not fully extended in standing alignment.',
        ],
        [
            'id' => 'GENU_RECURVATUM',
            'classification' => 'Genu Recurvatum',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'C5', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D5', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Genu recurvatum — the knee extends beyond neutral alignment.',
        ],
        [
            'id' => 'FORWARD_HEAD',
            'classification' => 'Forward Head',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'C2', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D2', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'side_cva', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Forward head posture — the head sits ahead of the shoulder line.',
        ],
        [
            'id' => 'ROUNDED_SHOULDER',
            'classification' => 'Rounded Shoulder',
            'pattern' => 'Forward Shoulder',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'C3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'A3', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'B3', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Rounded shoulder posture — the shoulders are protracted forward.',
        ],
        [
            'id' => 'PELVIC_OBLIQUITY',
            'classification' => 'Pelvic Obliquity',
            'severity' => 'moderate',
            'requires' => [],
            'all' => [],
            'any' => [
                ['section' => 'A7', 'direction' => 'above', 'level' => ['moderate', 'severe']],
                ['section' => 'A7', 'direction' => 'below', 'level' => ['moderate', 'severe']],
                ['section' => 'B6', 'direction' => 'above', 'level' => ['moderate', 'severe']],
                ['section' => 'B6', 'direction' => 'below', 'level' => ['moderate', 'severe']],
            ],
            'conclusion' => 'Pelvic obliquity — one side of the pelvis sits higher than the other.',
        ],
        [
            'id' => 'NORMAL_ALIGNMENT',
            'classification' => 'Normal',
            'severity' => 'normal',
            'requires' => [],
            'all' => [],
            'any' => [],
            'normal_when_no_deviation' => true,
            'conclusion' => 'Postural alignment is within the target range for all measured parameters.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Classification Policy
    |--------------------------------------------------------------------------
    | Guards that prevent a posture type being confirmed from a single
    | measurement or from unreliable data.
    */
    'classification_policy' => [
        // Independent measurements that must support a rule before it can be
        // confirmed as a posture classification.
        'min_evidence' => 2,
        // Minimum rule confidence (%) required for a confirmed classification.
        'min_confidence' => 55,
        // Share of expected measurements that must be present and reliable
        // before any posture type can be confirmed.
        'min_data_quality' => 0.6,
        // Percentages used to map the final confidence score to a level.
        'confidence_levels' => [
            'high' => 75,
            'moderate' => 55,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Confirmable Posture Classifications
    |--------------------------------------------------------------------------
    | The complete list of posture types the system is allowed to confirm.
    | Any other matched pattern (rounded shoulder, pelvic obliquity, ...) is
    | reported as a suspected or secondary finding only and never stored as a
    | posture diagnosis.
    */
    'confirmable_classifications' => [
        'Normal',
        'Kyphosis',
        'Lordosis',
        'Kyphosis-Lordosis',
        'Swayback',
        'Flat Back',
        'Flexed Knee',
        'Genu Recurvatum',
        'Forward Head',
    ],

    /*
    |--------------------------------------------------------------------------
    | Unclassified Result
    |--------------------------------------------------------------------------
    | Returned when the measurements are missing, unreliable, or do not support
    | a clear postural pattern. The system must never force a diagnosis.
    */
    'unclassified' => [
        'classification' => 'Unclassified',
        'code' => 'UNCLASSIFIED',
        'display' => 'Unclassified – Measurement Review Required',
        'review_status' => 'MEASUREMENT_REVIEW_REQUIRED',
    ],

    /*
    |--------------------------------------------------------------------------
    | Aquatic Exercise Recommendations (SARE)
    |--------------------------------------------------------------------------
    */
    'aquatic_exercises' => [
        'Global Postural Imbalance' => [
            ['name' => 'Floating Practice', 'level' => 'Stage 1', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 1],
            ['name' => 'Vestibular Training', 'level' => 'Stage 2', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 2],
            ['name' => 'Balance Drills', 'level' => 'Stage 3', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 3],
            ['name' => 'Core Stability', 'level' => 'Stage 4', 'difficulty' => 'intermediate', 'duration' => 20, 'stage' => 4],
            ['name' => 'Cross Rotation', 'level' => 'Stage 5', 'difficulty' => 'advanced', 'duration' => 20, 'stage' => 5],
            ['name' => 'Swimming Progression', 'level' => 'Stage 6', 'difficulty' => 'advanced', 'duration' => 20, 'stage' => 6],
        ],
        'Kyphosis' => [
            ['name' => 'Thoracic Extension Float', 'level' => 'Stage 1', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 1],
            ['name' => 'Back Float', 'level' => 'Stage 2', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 2],
            ['name' => 'Scapular Control Drill', 'level' => 'Stage 3', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 3],
            ['name' => 'Arm Extension Reach', 'level' => 'Stage 4', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 4],
            ['name' => 'Chest Opener Stretch', 'level' => 'Stage 5', 'difficulty' => 'beginner', 'duration' => 10, 'stage' => 5],
        ],
        'Lordosis' => [
            ['name' => 'Pelvic Neutral Float', 'level' => 'Stage 1', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 1],
            ['name' => 'Hip Mobility Drill', 'level' => 'Stage 2', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 2],
            ['name' => 'Core Stability Hold', 'level' => 'Stage 3', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 3],
            ['name' => 'Lower Back Release', 'level' => 'Stage 4', 'difficulty' => 'beginner', 'duration' => 10, 'stage' => 4],
        ],
        'Flat Back' => [
            ['name' => 'Lumbar Mobility Drill', 'level' => 'Stage 1', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 1],
            ['name' => 'Dynamic Rotation', 'level' => 'Stage 2', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 2],
            ['name' => 'Balance Training', 'level' => 'Stage 3', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 3],
            ['name' => 'Spinal Extension Flow', 'level' => 'Stage 4', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 4],
        ],
        'Forward Head' => [
            ['name' => 'Neck Control Drill', 'level' => 'Stage 1', 'difficulty' => 'beginner', 'duration' => 10, 'stage' => 1],
            ['name' => 'Vestibular Exercise', 'level' => 'Stage 2', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 2],
            ['name' => 'Balance Training', 'level' => 'Stage 3', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 3],
            ['name' => 'Chin Tuck Float', 'level' => 'Stage 4', 'difficulty' => 'beginner', 'duration' => 10, 'stage' => 4],
        ],
        'Rounded Shoulder' => [
            ['name' => 'Chest Stretch Float', 'level' => 'Stage 1', 'difficulty' => 'beginner', 'duration' => 10, 'stage' => 1],
            ['name' => 'Scapular Retraction', 'level' => 'Stage 2', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 2],
            ['name' => 'Arm Circles', 'level' => 'Stage 3', 'difficulty' => 'intermediate', 'duration' => 10, 'stage' => 3],
            ['name' => 'Backstroke Drill', 'level' => 'Stage 4', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 4],
        ],
        'Upper Body Instability' => [
            ['name' => 'Neck Stabilization', 'level' => 'Stage 1', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 1],
            ['name' => 'Shoulder Girdle Control', 'level' => 'Stage 2', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 2],
            ['name' => 'Trunk Balance', 'level' => 'Stage 3', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 3],
            ['name' => 'Coordinated Breathing', 'level' => 'Stage 4', 'difficulty' => 'intermediate', 'duration' => 10, 'stage' => 4],
        ],
        'Lower Body Compensation' => [
            ['name' => 'Pelvic Control', 'level' => 'Stage 1', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 1],
            ['name' => 'Foot Alignment', 'level' => 'Stage 2', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 2],
            ['name' => 'Hip Stability', 'level' => 'Stage 3', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 3],
            ['name' => 'Kicking Coordination', 'level' => 'Stage 4', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 4],
        ],
        'default' => [
            ['name' => 'Water Adaptation', 'level' => 'Stage 1', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 1],
            ['name' => 'Floating Basics', 'level' => 'Stage 2', 'difficulty' => 'beginner', 'duration' => 15, 'stage' => 2],
            ['name' => 'Balance Drills', 'level' => 'Stage 3', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 3],
            ['name' => 'Core Stability', 'level' => 'Stage 4', 'difficulty' => 'intermediate', 'duration' => 15, 'stage' => 4],
            ['name' => 'Swimming Progression', 'level' => 'Stage 5', 'difficulty' => 'advanced', 'duration' => 20, 'stage' => 5],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Massage Recommendations (SMRE)
    |--------------------------------------------------------------------------
    */
    'massage_recommendations' => [
        'Rounded Shoulder' => [
            ['area' => 'Neck', 'stars' => 4, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Chest', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Thoracic', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Hip Flexor', 'stars' => 4, 'duration' => 10, 'frequency' => '3x per week'],
            ['area' => 'Hamstring', 'stars' => 3, 'duration' => 10, 'frequency' => '3x per week'],
            ['area' => 'Calf', 'stars' => 2, 'duration' => 5, 'frequency' => '2x per week'],
        ],
        'Anterior Pelvic Tilt' => [
            ['area' => 'Hip Flexor', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Lower Back', 'stars' => 4, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Glute', 'stars' => 3, 'duration' => 10, 'frequency' => '3x per week'],
            ['area' => 'Hamstring', 'stars' => 3, 'duration' => 10, 'frequency' => '3x per week'],
        ],
        'Head Shift' => [
            ['area' => 'SCM', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Upper Trapezius', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Suboccipital', 'stars' => 4, 'duration' => 10, 'frequency' => 'daily'],
        ],
        'Forward Head' => [
            ['area' => 'Neck', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Chest', 'stars' => 4, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Upper Trap', 'stars' => 4, 'duration' => 10, 'frequency' => 'daily'],
        ],
        'Kyphosis' => [
            ['area' => 'Chest', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Thoracic', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Upper Trap', 'stars' => 3, 'duration' => 10, 'frequency' => '3x per week'],
        ],
        'Lordosis' => [
            ['area' => 'Lower Back', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Hip Flexor', 'stars' => 4, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Hamstring', 'stars' => 3, 'duration' => 10, 'frequency' => '3x per week'],
        ],
        'Lower Body Compensation' => [
            ['area' => 'Calf', 'stars' => 5, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Hamstring', 'stars' => 4, 'duration' => 10, 'frequency' => 'daily'],
            ['area' => 'Hip Flexor', 'stars' => 3, 'duration' => 10, 'frequency' => '3x per week'],
        ],
        'default' => [
            ['area' => 'Neck', 'stars' => 3, 'duration' => 10, 'frequency' => '3x per week'],
            ['area' => 'Chest', 'stars' => 3, 'duration' => 10, 'frequency' => '3x per week'],
            ['area' => 'Lower Back', 'stars' => 3, 'duration' => 10, 'frequency' => '3x per week'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Weekly Program Templates (SPPE)
    |--------------------------------------------------------------------------
    */
    'weekly_program_templates' => [
        'standard' => [
            ['day' => 'monday', 'activity_type' => 'aquatic_therapy', 'title' => 'Aquatic Therapy'],
            ['day' => 'tuesday', 'activity_type' => 'massage', 'title' => 'Massage Therapy'],
            ['day' => 'wednesday', 'activity_type' => 'stretching', 'title' => 'Stretching Session'],
            ['day' => 'thursday', 'activity_type' => 'aquatic_therapy', 'title' => 'Aquatic Therapy'],
            ['day' => 'friday', 'activity_type' => 'balance_exercise', 'title' => 'Balance Exercise'],
            ['day' => 'saturday', 'activity_type' => 'rest', 'title' => 'Rest Day'],
            ['day' => 'sunday', 'activity_type' => 'family_activity', 'title' => 'Family Activity'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SPARISK Progression Stages
    |--------------------------------------------------------------------------
    */
    'progression_stages' => [
        1 => ['name' => 'Water Adaptation', 'focus' => 'Comfort in water, basic floating'],
        2 => ['name' => 'Balance', 'focus' => 'Static and dynamic balance in water'],
        3 => ['name' => 'Rotation', 'focus' => 'Trunk rotation and cross-body coordination'],
        4 => ['name' => 'Core Stability', 'focus' => 'Deep core engagement and postural control'],
        5 => ['name' => 'Swimming Coordination', 'focus' => 'Integrated swimming movements'],
        6 => ['name' => 'Independent Swimming', 'focus' => 'Full swimming independence'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Clinical Summary Templates
    |--------------------------------------------------------------------------
    */
    'clinical_summaries' => [
        'high_improvement' => 'The participant demonstrates significant improvement in postural alignment. Recommended interventions show positive effect. Continue current program.',
        'moderate_improvement' => 'The participant shows moderate improvement in several postural parameters. Consistency with the prescribed program is recommended for continued progress.',
        'stable' => 'Posture parameters remain stable. Review intervention plan and consider adjusting exercise intensity or frequency.',
        'regression' => 'Some postural parameters show regression from previous assessment. A review of the intervention plan and potential contributing factors is recommended.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Health Screening Questions
    |--------------------------------------------------------------------------
    */
    'screening_questions' => [
        'fear_of_water' => 'Fear of water?',
        'history_of_seizure' => 'History of seizure?',
        'heart_disease' => 'Heart disease?',
        'asthma' => 'Asthma?',
        'neck_pain' => 'Neck pain?',
        'back_pain' => 'Back pain?',
        'hip_pain' => 'Hip pain?',
        'can_follow_instruction' => 'Can follow simple instruction?',
        'can_stand_independently' => 'Can stand independently?',
    ],

    /*
    |--------------------------------------------------------------------------
    | Intervention Posture Types (CADANGAN INTERVENSI)
    |--------------------------------------------------------------------------
    | The postural conditions an admin can set up specific exercises /
    | interventions for. Each key is a stable slug; the value is the label
    | shown in the admin page. Interventions are further scoped by age group.
    */
    'intervention_posture_types' => [
        'normal_neutral'     => 'Normal Neutral Posture',
        'forward_head'       => 'Forward Head Posture',
        'kyphosis'           => 'Kyphosis Posture (Increased Thoracic Kyphosis)',
        'lordosis'           => 'Lordosis Posture (Increased Lumbar Lordosis)',
        'kyphosis_lordosis'  => 'Kyphosis - Lordosis Posture',
        'flatback'           => 'Flatback Posture',
        'swayback'           => 'Swayback Posture',
        'flexed_knee'        => 'Flexed Knee Posture',
        'genu_recurvatum'    => 'Genu Recurvatum Posture',
        'frontal_asymmetry'  => 'Frontal Postural Asymmetry',
        'pronated_foot'      => 'Pronated Foot Posture',
    ],

    /*
    |--------------------------------------------------------------------------
    | Muscle Marker Regions
    |--------------------------------------------------------------------------
    | Which measurement region grades each body marker. The app colours a
    | muscle with the worst output status found in its region, so one photo can
    | show a yellow neck next to an orange chest and a red thigh. A marker with
    | no region (the biceps has no angle of its own) keeps its set's band.
    */
    'marker_regions' => [
        'front' => [
            'Neck / SCM' => ['head'],
            'Chest L' => ['shoulder', 'thoracic'],
            'Chest R' => ['shoulder', 'thoracic'],
            'Biceps L' => [],
            'Biceps R' => [],
            'Core' => ['thoracic', 'pelvis'],
            'Thigh L' => ['lower_limb'],
            'Thigh R' => ['lower_limb'],
            'Calf L' => ['lower_limb'],
            'Calf R' => ['lower_limb'],
        ],
        'back' => [
            'Upper Trap' => ['head', 'shoulder'],
            'Rhomboids' => ['thoracic'],
            'Lat L' => ['thoracic'],
            'Lat R' => ['thoracic'],
            'Erector Spinae' => ['thoracic'],
            'Hamstring L' => ['lower_limb'],
            'Hamstring R' => ['lower_limb'],
            'Calf L' => ['lower_limb'],
            'Calf R' => ['lower_limb'],
        ],
        'right_side' => [
            'Head / Neck' => ['head'],
            'Chest' => ['shoulder', 'thoracic'],
            'Thoracic' => ['thoracic'],
            'Abdominal' => ['thoracic', 'pelvis'],
            'Gluteus' => ['pelvis'],
            'Upper Leg' => ['lower_limb'],
            'Lower Leg' => ['lower_limb'],
        ],
        'left_side' => [
            'Head / Neck' => ['head'],
            'Chest' => ['shoulder', 'thoracic'],
            'Thoracic' => ['thoracic'],
            'Abdominal' => ['thoracic', 'pelvis'],
            'Gluteus' => ['pelvis'],
            'Upper Leg' => ['lower_limb'],
            'Lower Leg' => ['lower_limb'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Muscle Marker Status Labels
    |--------------------------------------------------------------------------
    | The ONE label a body marker prints. The highlight colour already says how
    | marked the finding is (normal = yellow, moderate = orange, severe = red),
    | so the label names the muscle's own state instead of repeating the band:
    | a weak muscle (Core, Glutes, Lats) is never called tight, while a tight
    | muscle reads "Tight" at the moderate band.
    */
    'marker_status_labels' => [
        'normal' => ['tight' => 'Normal', 'weak' => 'Normal'],
        'moderate' => ['tight' => 'Tight', 'weak' => 'Weak'],
        'severe' => ['tight' => 'Severe', 'weak' => 'Severe'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Posture Conditions (SATA Figures 1–12)
    |--------------------------------------------------------------------------
    | The posture classifications used by the body-marker editor. Each key is a
    | stable slug; the value is the label shown in the admin page.
    */
    'posture_conditions' => [
        'normal_neutral'    => 'Normal / Neutral Posture',
        'forward_head'      => 'Forward Head Posture',
        'rounded_shoulder'  => 'Rounded Shoulder Posture',
        'kyphosis'          => 'Kyphosis Posture (Increased Thoracic Kyphosis)',
        'lordosis'          => 'Lordosis Posture (Increased Lumbar Lordosis)',
        'kyphosis_lordosis' => 'Kyphosis - Lordosis Posture',
        'flatback'          => 'Flatback Posture',
        'sway_back'         => 'Sway-Back Posture',
        'flexed_knee'       => 'Flexed-Knee Posture',
        'genu_recurvatum'   => 'Genu Recurvatum Posture',
        'frontal_asymmetry' => 'Frontal Postural Asymmetry',
        'pronated_foot'     => 'Pronated Foot Posture',
    ],

    /*
    |--------------------------------------------------------------------------
    | Intervention Categories
    |--------------------------------------------------------------------------
    | The types of intervention/exercise an admin can assign to a posture type.
    */
    'intervention_categories' => [
        'exercise'            => 'Exercise',
        'stretching'          => 'Stretching',
        'strengthening'       => 'Strengthening',
        'postural_awareness'  => 'Postural Awareness',
        'manual_therapy'      => 'Manual Therapy',
        'lifestyle'           => 'Lifestyle / Ergonomics',
    ],

    /*
    |--------------------------------------------------------------------------
    | Intervention Programs
    |--------------------------------------------------------------------------
    | The program a CADANGAN INTERVENSI entry belongs to. Each program is shown
    | as its own tab on the admin page and as its own section in the app's
    | Program tab, in this order.
    */
    'intervention_programs' => [
        'aquatic_exercise' => 'Aquatic Exercise',
        'massage_therapy'  => 'Massage Therapy Plan',
        'general_exercise' => 'General Exercise',
    ],

    /*
    |--------------------------------------------------------------------------
    | Intervention Levels
    |--------------------------------------------------------------------------
    | Optional difficulty chip shown on the exercise card in the app.
    */
    'intervention_levels' => [
        'beginner'     => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced'     => 'Advanced',
    ],

    /*
    |--------------------------------------------------------------------------
    | Classification to Intervention Posture Type
    |--------------------------------------------------------------------------
    | The assessment stores a human readable classification ("Kyphosis"), while
    | Intervention Recommendations is keyed by a slug ("kyphosis"). This maps one
    | to the other so the recommendation engines can read the admin-authored
    | program. Keys are the assessment's `posture_classification` value, so legacy
    | synonyms ("Normal Alignment" for "Normal") need their own entry.
    |
    | A classification that is missing here falls back to `fallback` below rather
    | than skipping the admin catalogue, so uploaded images and content always
    | reach the app.
    */
    'intervention_classification_map' => [
        'Normal'            => 'normal_neutral',
        'Normal Alignment'  => 'normal_neutral',
        'Forward Head'      => 'forward_head',
        'Kyphosis'          => 'kyphosis',
        'Lordosis'          => 'lordosis',
        'Kyphosis-Lordosis' => 'kyphosis_lordosis',
        'Flat Back'         => 'flatback',
        'Swayback'          => 'swayback',
        'Flexed Knee'       => 'flexed_knee',
        'Genu Recurvatum'   => 'genu_recurvatum',
    ],

    /*
    |--------------------------------------------------------------------------
    | Intervention Fallback Posture Type
    |--------------------------------------------------------------------------
    | Used when the assessment's classification has no entry in the map above,
    | which is the case for UNCLASSIFIED results. The system deliberately does
    | not confirm a pattern there, so the patient is shown the neutral,
    | general-conditioning catalogue instead of a condition-specific program.
    */
    'intervention_fallback_posture_type' => 'normal_neutral',

    /*
    |--------------------------------------------------------------------------
    | SATA Aquatic Skills Assessment Checklist
    |--------------------------------------------------------------------------
    | The 35-item checklist a student is assessed against before each posture
    | capture. Yes = 1 point, No = 0, so the maximum score is one point per
    | item. Kept in config because the checklist is a fixed, versioned
    | instrument: the item numbers are the keys stored against an assessment,
    | so retitling an item must never renumber it.
    |
    | `categories` carries the order and the denominator each category is
    | scored out of, which the progress chart needs to show every category as
    | a percentage — the four hold different numbers of items, so raw counts
    | would flatter the longest category.
    */
    'aquatic_skills' => [
        'version' => 'Draft 1.0 (14 October 2026)',
        'categories' => [
            'readiness' => 'Readiness to Participate in Swimming Lessons',
            'basic_control' => 'Basic Control and Fundamental Skills',
            'basic_swimming' => 'Basic Swimming Skills',
            'advanced' => 'Advanced Swimming Movements',
        ],
        'items' => [
            1 => ['category' => 'readiness', 'text' => 'Can the student independently walk to the swimming pool and sit in the designated area?'],
            2 => ['category' => 'readiness', 'text' => 'Can the student enter the pool calmly?'],
            3 => ['category' => 'readiness', 'text' => "Can the student tolerate the instructor's touch?"],
            4 => ['category' => 'readiness', 'text' => 'Can the student wear a swim cap and goggles?'],
            5 => ['category' => 'readiness', 'text' => 'Can the student make eye contact with the instructor during class?'],
            6 => ['category' => 'readiness', 'text' => "Can the student focus on and follow the instructor's directions?"],
            7 => ['category' => 'readiness', 'text' => 'Can the student understand and respond to nonverbal cues, such as hand signals or touch?'],
            8 => ['category' => 'readiness', 'text' => 'Can the student understand and follow brief verbal instructions consisting of three to five words?'],
            9 => ['category' => 'readiness', 'text' => 'Can the student respond calmly to firm instructions from the instructor?'],
            10 => ['category' => 'basic_control', 'text' => 'Can the student enter the swimming pool at the deep end while being supported by the instructor?'],
            11 => ['category' => 'basic_control', 'text' => 'Can the student allow water to be poured over their head?'],
            12 => ['category' => 'basic_control', 'text' => 'Can the student submerge their face in the water with assistance for 2-5 seconds?'],
            13 => ['category' => 'basic_control', 'text' => "Can the student tolerate the instructor's touch during stretching and massage activities?"],
            14 => ['category' => 'basic_control', 'text' => 'Can the student understand how to use and hold a flotation device while in the water?'],
            15 => ['category' => 'basic_control', 'text' => 'Can the student relax their muscles during gentle push-and-pull movements?'],
            16 => ['category' => 'basic_swimming', 'text' => 'Can the student blow bubbles by exhaling into the water?'],
            17 => ['category' => 'basic_swimming', 'text' => 'Can the student lift and lower their head in a controlled manner?'],
            18 => ['category' => 'basic_swimming', 'text' => 'Can the student maintain a streamlined body position with their legs lifted in the water?'],
            19 => ['category' => 'basic_swimming', 'text' => 'Can the student use their arms to paddle through the water and move forward?'],
            20 => ['category' => 'basic_swimming', 'text' => 'Can the student kick or tread water to maintain their body position?'],
            21 => ['category' => 'basic_swimming', 'text' => 'Can the student tread water without assistance for 30 seconds?'],
            22 => ['category' => 'basic_swimming', 'text' => 'Can the student swim four metres in a straight line from the instructor to the pool wall, without a flotation device or physical assistance?'],
            23 => ['category' => 'basic_swimming', 'text' => 'Can the student roll their body to the left and right in the water?'],
            24 => ['category' => 'basic_swimming', 'text' => 'Can the student rotate from a prone to a supine position in the water?'],
            25 => ['category' => 'advanced', 'text' => 'Can the student swim 5 meters in a straight line from the instructor to the pool wall without a flotation device or physical assistance?'],
            26 => ['category' => 'advanced', 'text' => "Can the student swim a longer distance following the instructor's directions?"],
            27 => ['category' => 'advanced', 'text' => 'Can the student perform bilateral arm movements?'],
            28 => ['category' => 'advanced', 'text' => 'Can the student perform flutter kicks independently?'],
            29 => ['category' => 'advanced', 'text' => 'Can the student coordinate bilateral arm movements and flutter kicks simultaneously?'],
            30 => ['category' => 'advanced', 'text' => 'Can the student perform basic breaststroke arm movements using the sequence clap, push, and open in a circular motion?'],
            31 => ['category' => 'advanced', 'text' => 'Can the student perform basic breaststroke leg movements using the sequence draw in, open, kick, and bring the legs together?'],
            32 => ['category' => 'advanced', 'text' => 'Can the student coordinate breaststroke arm and leg movements?'],
            33 => ['category' => 'advanced', 'text' => 'Can the student maintain a horizontal body position while floating on their back?'],
            34 => ['category' => 'advanced', 'text' => 'Can the student move one arm backward in a circular motion?'],
            35 => ['category' => 'advanced', 'text' => 'Can the student coordinate alternating arm movements with flutter kicks?'],
        ],
    ],
];
