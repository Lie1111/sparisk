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
        'normal'   => ['label' => 'On Point',            'color' => 'green'],
        'mild'     => ['label' => 'Slightly Off Point',  'color' => 'yellow'],
        'moderate' => ['label' => 'Off Point',           'color' => 'orange'],
        'severe'   => ['label' => 'Far Off Point',       'color' => 'red'],
        'review'   => ['label' => 'Check Measurement',   'color' => 'grey'],
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
    | Maps section codes to labels, views, normal ranges, body region and the
    | angle interpretation mode.
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
        'A1'  => ['view' => 'front', 'label' => 'Body Alignment', 'region' => 'alignment', 'normal_min' => -1, 'normal_max' => 1],
        'A2'  => ['view' => 'front', 'label' => 'Head Tilt', 'region' => 'head', 'angle_mode' => 'orientation', 'normal_min' => -1, 'normal_max' => 1],
        'A3'  => ['view' => 'front', 'label' => 'Shoulder Alignment', 'region' => 'shoulder', 'angle_mode' => 'orientation', 'normal_min' => -1, 'normal_max' => 1],
        'A4'  => ['view' => 'front', 'label' => 'Axillae Alignment', 'region' => 'thoracic', 'normal_min' => -1, 'normal_max' => 1],
        'A5'  => ['view' => 'front', 'label' => 'Ribcage Tilt', 'region' => 'thoracic', 'normal_min' => -1, 'normal_max' => 1],
        'A6'  => ['view' => 'front', 'label' => 'Trunk Alignment', 'region' => 'thoracic', 'normal_min' => -1, 'normal_max' => 1],
        'A7'  => ['view' => 'front', 'label' => 'Estimated Pelvic Alignment', 'region' => 'pelvis', 'angle_mode' => 'orientation', 'normal_min' => -1, 'normal_max' => 1],
        'A8'  => ['view' => 'front', 'label' => 'Right Knee Angle', 'region' => 'lower_limb', 'side' => 'right', 'angle_mode' => 'orientation', 'normal_min' => -2, 'normal_max' => 2],
        'A9'  => ['view' => 'front', 'label' => 'Left Knee Angle', 'region' => 'lower_limb', 'side' => 'left', 'angle_mode' => 'orientation', 'normal_min' => -2, 'normal_max' => 2],
        'A10' => ['view' => 'front', 'label' => 'Right Foot Rotation', 'region' => 'lower_limb', 'side' => 'right', 'angle_mode' => 'orientation', 'normal_min' => -5, 'normal_max' => 5],
        'A11' => ['view' => 'front', 'label' => 'Left Foot Rotation', 'region' => 'lower_limb', 'side' => 'left', 'angle_mode' => 'orientation', 'normal_min' => -5, 'normal_max' => 5],
        'B1'  => ['view' => 'back', 'label' => 'Body Alignment', 'region' => 'alignment', 'normal_min' => -1, 'normal_max' => 1],
        'B2'  => ['view' => 'back', 'label' => 'Head Tilt', 'region' => 'head', 'angle_mode' => 'orientation', 'normal_min' => -1, 'normal_max' => 1],
        'B3'  => ['view' => 'back', 'label' => 'Shoulder Alignment', 'region' => 'shoulder', 'angle_mode' => 'orientation', 'normal_min' => -1, 'normal_max' => 1],
        'B4'  => ['view' => 'back', 'label' => 'Axillae Alignment', 'region' => 'thoracic', 'normal_min' => -1, 'normal_max' => 1],
        'B5'  => ['view' => 'back', 'label' => 'Trunk Alignment', 'region' => 'thoracic', 'normal_min' => -1, 'normal_max' => 1],
        'B6'  => ['view' => 'back', 'label' => 'Estimated Pelvic Alignment', 'region' => 'pelvis', 'angle_mode' => 'orientation', 'normal_min' => -1, 'normal_max' => 1],
        'B7'  => ['view' => 'back', 'label' => 'Knees', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'normal_min' => -2, 'normal_max' => 2],
        'B8'  => ['view' => 'back', 'label' => 'Feet', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'normal_min' => -2, 'normal_max' => 2],
        'C1'  => ['view' => 'right_side', 'label' => 'Body Alignment', 'region' => 'alignment', 'normal_min' => -2, 'normal_max' => 2],
        'C2'  => ['view' => 'right_side', 'label' => 'Head Shift', 'region' => 'head', 'normal_min' => -5, 'normal_max' => 10],
        'C3'  => ['view' => 'right_side', 'label' => 'Shoulder Angle', 'region' => 'shoulder', 'normal_min' => -5, 'normal_max' => 10],
        'C4'  => ['view' => 'right_side', 'label' => 'Estimated Pelvic Alignment', 'region' => 'pelvis', 'normal_min' => -2, 'normal_max' => 5],
        'C5'  => ['view' => 'right_side', 'label' => 'Knee', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'normal_min' => -2, 'normal_max' => 2],
        'C6'  => ['view' => 'right_side', 'label' => 'Tibia', 'region' => 'lower_limb', 'normal_min' => -5, 'normal_max' => 5],
        'C7'  => ['view' => 'right_side', 'label' => 'Foot Angle', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'normal_min' => -5, 'normal_max' => 10],
        'D1'  => ['view' => 'left_side', 'label' => 'Body Alignment', 'region' => 'alignment', 'normal_min' => -2, 'normal_max' => 2],
        'D2'  => ['view' => 'left_side', 'label' => 'Head Shift', 'region' => 'head', 'normal_min' => -5, 'normal_max' => 10],
        'D3'  => ['view' => 'left_side', 'label' => 'Shoulder Angle', 'region' => 'shoulder', 'normal_min' => -5, 'normal_max' => 10],
        'D4'  => ['view' => 'left_side', 'label' => 'Estimated Pelvic Alignment', 'region' => 'pelvis', 'normal_min' => -2, 'normal_max' => 5],
        'D5'  => ['view' => 'left_side', 'label' => 'Knee', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'normal_min' => -2, 'normal_max' => 2],
        'D6'  => ['view' => 'left_side', 'label' => 'Tibia', 'region' => 'lower_limb', 'normal_min' => -5, 'normal_max' => 5],
        'D7'  => ['view' => 'left_side', 'label' => 'Foot Angle', 'region' => 'lower_limb', 'angle_mode' => 'orientation', 'normal_min' => -5, 'normal_max' => 10],
        'side_cva'       => ['view' => 'right_side', 'label' => 'CVA / Head Reference', 'region' => 'head', 'normal_min' => 45, 'normal_max' => 55],
        'side_kyphosis'  => ['view' => 'right_side', 'label' => 'Upper-Back Posture Indicator', 'region' => 'thoracic', 'normal_min' => 35, 'normal_max' => 50],
        'side_lordosis'  => ['view' => 'right_side', 'label' => 'Lower-Back Posture Indicator', 'region' => 'pelvis', 'normal_min' => 35, 'normal_max' => 55],
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
        'symmetrical'            => ['label' => 'Symmetrical',            'color' => 'green'],
        'slightly_asymmetrical'  => ['label' => 'Slightly asymmetrical',  'color' => 'yellow'],
        'markedly_asymmetrical'  => ['label' => 'Markedly asymmetrical',  'color' => 'orange'],
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
            'key_indicators' => ['C3_high', 'D3_high', 'C2_high', 'D2_high'],
        ],
        'Lordosis' => [
            'description' => 'Excessive lumbar curvature (swayback)',
            'key_indicators' => ['C4_high', 'D4_high', 'A7_high'],
        ],
        'Kyphosis-Lordosis' => [
            'description' => 'Combined excessive thoracic and lumbar curvature',
            'key_indicators' => ['side_kyphosis_high', 'side_lordosis_high'],
        ],
        'Flat Back' => [
            'description' => 'Reduced spinal curves',
            'key_indicators' => ['C4_low', 'D4_low', 'C3_low'],
        ],
        'Swayback' => [
            'description' => 'Posterior pelvic shift with anterior upper trunk',
            'key_indicators' => ['C1_forward', 'D1_forward', 'C4_high'],
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
            'requires' => ['side_kyphosis'],
            'all' => [
                ['section' => 'side_kyphosis', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'any' => [
                ['section' => 'C3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'side_cva', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Kyphotic Posture — increased thoracic curvature with forward shoulder/head alignment.',
        ],
        [
            'id' => 'LORDOSIS',
            'classification' => 'Lordosis',
            'severity' => 'moderate',
            'requires' => ['side_lordosis'],
            'all' => [
                ['section' => 'side_lordosis', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'any' => [
                ['section' => 'C4', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D4', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Lordotic Posture — increased lumbar curve with anterior pelvic tilt.',
        ],
        [
            'id' => 'KYPHOSIS_LORDOSIS',
            'classification' => 'Kyphosis-Lordosis',
            'severity' => 'moderate',
            'requires' => ['side_kyphosis', 'side_lordosis'],
            'all' => [
                ['section' => 'side_kyphosis', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'side_lordosis', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'any' => [],
            'conclusion' => 'Combined kyphosis and lordosis — increased thoracic and lumbar curves.',
        ],
        [
            'id' => 'FLATBACK',
            'classification' => 'Flat Back',
            'severity' => 'moderate',
            'requires' => ['side_kyphosis', 'side_lordosis'],
            'all' => [
                ['section' => 'side_kyphosis', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'side_lordosis', 'direction' => 'below', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'any' => [],
            'conclusion' => 'Flat back — both the thoracic and lumbar curves are reduced.',
        ],
        [
            'id' => 'SWAYBACK',
            'classification' => 'Swayback',
            'severity' => 'moderate',
            'requires' => ['C1', 'D1'],
            'all' => [],
            'any' => [
                ['section' => 'C1', 'direction' => 'above', 'level' => ['moderate', 'severe']],
                ['section' => 'D1', 'direction' => 'above', 'level' => ['moderate', 'severe']],
            ],
            'any2' => [
                ['section' => 'C4', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D4', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'C3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
                ['section' => 'D3', 'direction' => 'above', 'level' => ['mild', 'moderate', 'severe']],
            ],
            'conclusion' => 'Swayback — posterior pelvic shift with an anterior upper trunk.',
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
    | Any other matched pattern (forward head, rounded shoulder, pelvic
    | obliquity, ...) is reported as a suspected or secondary finding only and
    | never stored as a posture diagnosis.
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
];
