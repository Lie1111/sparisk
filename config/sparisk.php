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
    | Measurement Definitions
    |--------------------------------------------------------------------------
    | Maps section codes to labels, views, and normal ranges.
    */
    'measurements' => [
        'A1'  => ['view' => 'front', 'label' => 'Body Alignment', 'normal_min' => -1, 'normal_max' => 1],
        'A2'  => ['view' => 'front', 'label' => 'Head Tilt', 'normal_min' => -1, 'normal_max' => 1],
        'A3'  => ['view' => 'front', 'label' => 'Shoulder Alignment', 'normal_min' => -1, 'normal_max' => 1],
        'A4'  => ['view' => 'front', 'label' => 'Axillae Alignment', 'normal_min' => -1, 'normal_max' => 1],
        'A5'  => ['view' => 'front', 'label' => 'Ribcage Tilt', 'normal_min' => -1, 'normal_max' => 1],
        'A6'  => ['view' => 'front', 'label' => 'Trunk Alignment', 'normal_min' => -1, 'normal_max' => 1],
        'A7'  => ['view' => 'front', 'label' => 'Pelvic Tilt', 'normal_min' => -1, 'normal_max' => 1],
        'A8'  => ['view' => 'front', 'label' => 'Right Knee Angle', 'normal_min' => -2, 'normal_max' => 2],
        'A9'  => ['view' => 'front', 'label' => 'Left Knee Angle', 'normal_min' => -2, 'normal_max' => 2],
        'A10' => ['view' => 'front', 'label' => 'Right Foot Rotation', 'normal_min' => -5, 'normal_max' => 5],
        'A11' => ['view' => 'front', 'label' => 'Left Foot Rotation', 'normal_min' => -5, 'normal_max' => 5],
        'B1'  => ['view' => 'back', 'label' => 'Body Alignment', 'normal_min' => -1, 'normal_max' => 1],
        'B2'  => ['view' => 'back', 'label' => 'Head Tilt', 'normal_min' => -1, 'normal_max' => 1],
        'B3'  => ['view' => 'back', 'label' => 'Shoulder Alignment', 'normal_min' => -1, 'normal_max' => 1],
        'B4'  => ['view' => 'back', 'label' => 'Axillae Alignment', 'normal_min' => -1, 'normal_max' => 1],
        'B5'  => ['view' => 'back', 'label' => 'Trunk Alignment', 'normal_min' => -1, 'normal_max' => 1],
        'B6'  => ['view' => 'back', 'label' => 'Pelvic Tilt', 'normal_min' => -1, 'normal_max' => 1],
        'B7'  => ['view' => 'back', 'label' => 'Knees', 'normal_min' => -2, 'normal_max' => 2],
        'B8'  => ['view' => 'back', 'label' => 'Feet', 'normal_min' => -2, 'normal_max' => 2],
        'C1'  => ['view' => 'right_side', 'label' => 'Body Alignment', 'normal_min' => -2, 'normal_max' => 2],
        'C2'  => ['view' => 'right_side', 'label' => 'Head Shift', 'normal_min' => -5, 'normal_max' => 10],
        'C3'  => ['view' => 'right_side', 'label' => 'Shoulder Angle', 'normal_min' => -5, 'normal_max' => 10],
        'C4'  => ['view' => 'right_side', 'label' => 'Pelvic Tilt', 'normal_min' => -2, 'normal_max' => 5],
        'C5'  => ['view' => 'right_side', 'label' => 'Knee', 'normal_min' => -2, 'normal_max' => 2],
        'C6'  => ['view' => 'right_side', 'label' => 'Tibia', 'normal_min' => -5, 'normal_max' => 5],
        'C7'  => ['view' => 'right_side', 'label' => 'Foot Angle', 'normal_min' => -5, 'normal_max' => 10],
        'D1'  => ['view' => 'left_side', 'label' => 'Body Alignment', 'normal_min' => -2, 'normal_max' => 2],
        'D2'  => ['view' => 'left_side', 'label' => 'Head Shift', 'normal_min' => -5, 'normal_max' => 10],
        'D3'  => ['view' => 'left_side', 'label' => 'Shoulder Angle', 'normal_min' => -5, 'normal_max' => 10],
        'D4'  => ['view' => 'left_side', 'label' => 'Pelvic Tilt', 'normal_min' => -2, 'normal_max' => 5],
        'D5'  => ['view' => 'left_side', 'label' => 'Knee', 'normal_min' => -2, 'normal_max' => 2],
        'D6'  => ['view' => 'left_side', 'label' => 'Tibia', 'normal_min' => -5, 'normal_max' => 5],
        'D7'  => ['view' => 'left_side', 'label' => 'Foot Angle', 'normal_min' => -5, 'normal_max' => 10],
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
        'Kyphosis' => [
            'description' => 'Excessive thoracic curvature (hunched upper back)',
            'key_indicators' => ['C3_high', 'D3_high', 'C2_high', 'D2_high'],
        ],
        'Lordosis' => [
            'description' => 'Excessive lumbar curvature (swayback)',
            'key_indicators' => ['C4_high', 'D4_high', 'A7_high'],
        ],
        'Flat Back' => [
            'description' => 'Reduced spinal curves',
            'key_indicators' => ['C4_low', 'D4_low', 'C3_low'],
        ],
        'Swayback' => [
            'description' => 'Posterior pelvic shift with anterior upper trunk',
            'key_indicators' => ['C1_forward', 'D1_forward', 'C4_high'],
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
    | SPARISK Decision Rules
    |--------------------------------------------------------------------------
    | IF-THEN rules for the Decision Engine.
    */
    'decision_rules' => [
        [
            'id' => 'UPPER_BODY_INSTABILITY',
            'conditions' => [
                ['section' => 'C2', 'severity' => ['severe']],
                ['section' => 'D2', 'severity' => ['severe']],
                ['section' => 'C3', 'severity' => ['severe']],
                ['section' => 'D3', 'severity' => ['severe']],
            ],
            'condition_logic' => 'ALL',
            'conclusion' => 'Upper Body Instability',
            'classification' => 'Upper Body Instability',
            'severity' => 'severe',
        ],
        [
            'id' => 'LOWER_BODY_COMPENSATION',
            'conditions' => [
                ['section' => 'C4', 'severity' => ['severe']],
                ['section' => 'D4', 'severity' => ['severe']],
                ['section' => 'C7', 'severity' => ['moderate', 'severe']],
                ['section' => 'D7', 'severity' => ['moderate', 'severe']],
            ],
            'condition_logic' => 'ALL',
            'conclusion' => 'Lower Body Compensation',
            'classification' => 'Lower Body Compensation',
            'severity' => 'severe',
        ],
        [
            'id' => 'GLOBAL_POSTURAL_IMBALANCE',
            'conditions' => [
                ['section' => 'C2', 'severity' => ['severe']],
                ['section' => 'C4', 'severity' => ['severe']],
                ['section' => 'C7', 'severity' => ['severe']],
            ],
            'condition_logic' => 'ALL',
            'conclusion' => 'Global Postural Imbalance',
            'classification' => 'Global Postural Imbalance',
            'severity' => 'severe',
        ],
        [
            'id' => 'FORWARD_HEAD',
            'conditions' => [
                ['section' => 'C2', 'severity' => ['moderate', 'severe']],
                ['section' => 'D2', 'severity' => ['moderate', 'severe']],
            ],
            'condition_logic' => 'ANY',
            'conclusion' => 'Forward Head Posture',
            'classification' => 'Forward Head',
            'severity' => 'moderate',
        ],
        [
            'id' => 'ROUNDED_SHOULDER',
            'conditions' => [
                ['section' => 'C3', 'severity' => ['moderate', 'severe']],
                ['section' => 'D3', 'severity' => ['moderate', 'severe']],
            ],
            'condition_logic' => 'ANY',
            'conclusion' => 'Rounded Shoulder Posture',
            'classification' => 'Rounded Shoulder',
            'severity' => 'moderate',
        ],
        [
            'id' => 'KYPHOSIS',
            'conditions' => [
                ['section' => 'C3', 'severity' => ['moderate', 'severe']],
            ],
            'condition_logic' => 'ANY',
            'conclusion' => 'Kyphotic Posture',
            'classification' => 'Kyphosis',
            'severity' => 'moderate',
        ],
        [
            'id' => 'LORDOSIS',
            'conditions' => [
                ['section' => 'C4', 'severity' => ['moderate', 'severe']],
                ['section' => 'D4', 'severity' => ['moderate', 'severe']],
            ],
            'condition_logic' => 'ALL',
            'conclusion' => 'Lordotic Posture',
            'classification' => 'Lordosis',
            'severity' => 'moderate',
        ],
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
];
