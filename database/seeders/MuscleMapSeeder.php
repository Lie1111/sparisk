<?php

namespace Database\Seeders;

use App\Models\MuscleCondition;
use App\Models\MuscleHighlight;
use Illuminate\Database\Seeder;

class MuscleMapSeeder extends Seeder
{
    private const CONDITIONS = [
        'kyphosis' => [
            'name' => 'Kyphosis',
            'description' => 'An increased forward rounding of the upper back. The shoulders and head drift forward and the chest closes in.',
            'effects' => 'Rounded upper back and shoulders
Neck and upper-back stiffness or pain
Reduced chest expansion when breathing
Head carried forward of the body',
            'detection' => [
                'sections' => [],
                'classifications' => [
                    'KYPHOSIS',
                    'KYPHOSIS_LORDOSIS'
                ]
            ],
            'order_index' => 1,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Pectoralis Major',
                            'status' => 'tight',
                            'x' => 0.4169,
                            'y' => 0.2157,
                            'width' => 0.1641,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4864,
                                    -0.4882
                                ],
                                [
                                    0.1803,
                                    -0.5
                                ],
                                [
                                    -0.1939,
                                    -0.4765
                                ],
                                [
                                    -0.3776,
                                    -0.3588
                                ],
                                [
                                    -0.466,
                                    -0.1647
                                ],
                                [
                                    -0.5,
                                    0.0824
                                ],
                                [
                                    -0.3776,
                                    0.2882
                                ],
                                [
                                    -0.1599,
                                    0.4235
                                ],
                                [
                                    0.1463,
                                    0.5
                                ],
                                [
                                    0.4524,
                                    0.4824
                                ],
                                [
                                    0.5,
                                    0.2176
                                ],
                                [
                                    0.5,
                                    -0.1941
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Pectoralis Major',
                            'status' => 'tight',
                            'x' => 0.5876,
                            'y' => 0.2157,
                            'width' => 0.1641,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4864,
                                    -0.4882
                                ],
                                [
                                    -0.1803,
                                    -0.5
                                ],
                                [
                                    0.1939,
                                    -0.4765
                                ],
                                [
                                    0.3776,
                                    -0.3588
                                ],
                                [
                                    0.466,
                                    -0.1647
                                ],
                                [
                                    0.5,
                                    0.0824
                                ],
                                [
                                    0.3776,
                                    0.2882
                                ],
                                [
                                    0.1599,
                                    0.4235
                                ],
                                [
                                    -0.1463,
                                    0.5
                                ],
                                [
                                    -0.4524,
                                    0.4824
                                ],
                                [
                                    -0.5,
                                    0.2176
                                ],
                                [
                                    -0.5,
                                    -0.1941
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.4085,
                            'y' => 0.2092,
                            'width' => 0.0982,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4659,
                                    -0.4626
                                ],
                                [
                                    -0.2386,
                                    -0.5
                                ],
                                [
                                    0.4432,
                                    0.0327
                                ],
                                [
                                    0.5,
                                    0.4533
                                ],
                                [
                                    0.2727,
                                    0.5
                                ],
                                [
                                    -0.2386,
                                    -0.0607
                                ],
                                [
                                    -0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.596,
                            'y' => 0.2092,
                            'width' => 0.0982,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4659,
                                    -0.4626
                                ],
                                [
                                    0.2386,
                                    -0.5
                                ],
                                [
                                    -0.4432,
                                    0.0327
                                ],
                                [
                                    -0.5,
                                    0.4533
                                ],
                                [
                                    -0.2727,
                                    0.5
                                ],
                                [
                                    0.2386,
                                    -0.0607
                                ],
                                [
                                    0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4196,
                            'y' => 0.1441,
                            'width' => 0.1585,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3451,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4554
                                ],
                                [
                                    0.5,
                                    0.3929
                                ],
                                [
                                    0.3099,
                                    0.5
                                ],
                                [
                                    0.0282,
                                    0.4375
                                ],
                                [
                                    -0.3239,
                                    0.3929
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    -0.3239,
                                    0.2321
                                ],
                                [
                                    0.0282,
                                    0.0179
                                ],
                                [
                                    0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5848,
                            'y' => 0.1441,
                            'width' => 0.1585,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3451,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4554
                                ],
                                [
                                    -0.5,
                                    0.3929
                                ],
                                [
                                    -0.3099,
                                    0.5
                                ],
                                [
                                    -0.0282,
                                    0.4375
                                ],
                                [
                                    0.3239,
                                    0.3929
                                ],
                                [
                                    0.5,
                                    0.375
                                ],
                                [
                                    0.3239,
                                    0.2321
                                ],
                                [
                                    -0.0282,
                                    0.0179
                                ],
                                [
                                    -0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.4788,
                            'y' => 0.2872,
                            'width' => 0.0379,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0294,
                                    -0.5
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2647,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3056
                                ],
                                [
                                    -0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.5257,
                            'y' => 0.2872,
                            'width' => 0.0379,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0294,
                                    -0.5
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2647,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3056
                                ],
                                [
                                    0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rhomboid',
                            'status' => 'weak',
                            'x' => 0.4554,
                            'y' => 0.2041,
                            'width' => 0.0759,
                            'height' => 0.0704,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4118,
                                    -0.46
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.14
                                ],
                                [
                                    0.3971,
                                    0.26
                                ],
                                [
                                    -0.1912,
                                    0.5
                                ],
                                [
                                    -0.4412,
                                    0.46
                                ],
                                [
                                    -0.5,
                                    -0.02
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Rhomboid',
                            'status' => 'weak',
                            'x' => 0.5491,
                            'y' => 0.2041,
                            'width' => 0.0759,
                            'height' => 0.0704,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4118,
                                    -0.46
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.14
                                ],
                                [
                                    -0.3971,
                                    0.26
                                ],
                                [
                                    0.1912,
                                    0.5
                                ],
                                [
                                    0.4412,
                                    0.46
                                ],
                                [
                                    0.5,
                                    -0.02
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Latissimus Dorsi',
                            'status' => 'weak',
                            'x' => 0.4102,
                            'y' => 0.2928,
                            'width' => 0.1507,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.2778,
                                    -0.4167
                                ],
                                [
                                    0.0556,
                                    -0.3333
                                ],
                                [
                                    0.3519,
                                    -0.1667
                                ],
                                [
                                    0.5,
                                    0.1667
                                ],
                                [
                                    0.463,
                                    0.4167
                                ],
                                [
                                    0.2407,
                                    0.5
                                ],
                                [
                                    -0.0556,
                                    0.4167
                                ],
                                [
                                    -0.2778,
                                    0.1667
                                ],
                                [
                                    -0.3889,
                                    -0.0833
                                ],
                                [
                                    -0.463,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Latissimus Dorsi',
                            'status' => 'weak',
                            'x' => 0.5943,
                            'y' => 0.2928,
                            'width' => 0.1507,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.2778,
                                    -0.4167
                                ],
                                [
                                    -0.0556,
                                    -0.3333
                                ],
                                [
                                    -0.3519,
                                    -0.1667
                                ],
                                [
                                    -0.5,
                                    0.1667
                                ],
                                [
                                    -0.463,
                                    0.4167
                                ],
                                [
                                    -0.2407,
                                    0.5
                                ],
                                [
                                    0.0556,
                                    0.4167
                                ],
                                [
                                    0.2778,
                                    0.1667
                                ],
                                [
                                    0.3889,
                                    -0.0833
                                ],
                                [
                                    0.463,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4353,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.05,
                                    -0.5
                                ],
                                [
                                    0.3,
                                    -0.4231
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.1,
                                    0.4231
                                ],
                                [
                                    -0.3,
                                    0.4231
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Pectoralis Major',
                            'status' => 'tight',
                            'x' => 0.5737,
                            'y' => 0.2111,
                            'width' => 0.1027,
                            'height' => 0.0845,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4783,
                                    -0.5
                                ],
                                [
                                    -0.1522,
                                    -0.4
                                ],
                                [
                                    0.1739,
                                    -0.2
                                ],
                                [
                                    0.4457,
                                    0.0667
                                ],
                                [
                                    0.5,
                                    0.3
                                ],
                                [
                                    0.337,
                                    0.4667
                                ],
                                [
                                    -0.0435,
                                    0.5
                                ],
                                [
                                    -0.4239,
                                    0.3667
                                ],
                                [
                                    -0.4783,
                                    0.1
                                ],
                                [
                                    -0.5,
                                    -0.2333
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.3929,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    0.25,
                                    0.197
                                ],
                                [
                                    0.5,
                                    0.4697
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.1346,
                                    0.197
                                ],
                                [
                                    -0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rhomboid',
                            'status' => 'weak',
                            'x' => 0.3789,
                            'y' => 0.1971,
                            'width' => 0.0346,
                            'height' => 0.0338,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.371,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.4032,
                                    0.5
                                ],
                                [
                                    -0.3065,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Latissimus Dorsi',
                            'status' => 'weak',
                            'x' => 0.385,
                            'y' => 0.2759,
                            'width' => 0.0335,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.4286
                                ],
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0714
                                ],
                                [
                                    0.3333,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    -0.4333,
                                    0.0714
                                ]
                            ],
                            'order_index' => 4
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5647,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.05,
                                    -0.5
                                ],
                                [
                                    -0.3,
                                    -0.4231
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.1,
                                    0.4231
                                ],
                                [
                                    0.3,
                                    0.4231
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Pectoralis Major',
                            'status' => 'tight',
                            'x' => 0.4263,
                            'y' => 0.2111,
                            'width' => 0.1027,
                            'height' => 0.0845,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4783,
                                    -0.5
                                ],
                                [
                                    0.1522,
                                    -0.4
                                ],
                                [
                                    -0.1739,
                                    -0.2
                                ],
                                [
                                    -0.4457,
                                    0.0667
                                ],
                                [
                                    -0.5,
                                    0.3
                                ],
                                [
                                    -0.337,
                                    0.4667
                                ],
                                [
                                    0.0435,
                                    0.5
                                ],
                                [
                                    0.4239,
                                    0.3667
                                ],
                                [
                                    0.4783,
                                    0.1
                                ],
                                [
                                    0.5,
                                    -0.2333
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.6071,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    -0.25,
                                    0.197
                                ],
                                [
                                    -0.5,
                                    0.4697
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.1346,
                                    0.197
                                ],
                                [
                                    0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rhomboid',
                            'status' => 'weak',
                            'x' => 0.6211,
                            'y' => 0.1971,
                            'width' => 0.0346,
                            'height' => 0.0338,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.371,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.4032,
                                    0.5
                                ],
                                [
                                    0.3065,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Latissimus Dorsi',
                            'status' => 'weak',
                            'x' => 0.615,
                            'y' => 0.2759,
                            'width' => 0.0335,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.4286
                                ],
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0714
                                ],
                                [
                                    -0.3333,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    0.4333,
                                    0.0714
                                ]
                            ],
                            'order_index' => 4
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Pectoralis Major',
                            'status' => 'tight',
                            'x' => 0.4237,
                            'y' => 0.2157,
                            'width' => 0.1509,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4864,
                                    -0.4882
                                ],
                                [
                                    0.1803,
                                    -0.5
                                ],
                                [
                                    -0.1939,
                                    -0.4765
                                ],
                                [
                                    -0.3776,
                                    -0.3588
                                ],
                                [
                                    -0.466,
                                    -0.1647
                                ],
                                [
                                    -0.5,
                                    0.0824
                                ],
                                [
                                    -0.3776,
                                    0.2882
                                ],
                                [
                                    -0.1599,
                                    0.4235
                                ],
                                [
                                    0.1463,
                                    0.5
                                ],
                                [
                                    0.4524,
                                    0.4824
                                ],
                                [
                                    0.5,
                                    0.2176
                                ],
                                [
                                    0.5,
                                    -0.1941
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Pectoralis Major',
                            'status' => 'tight',
                            'x' => 0.5808,
                            'y' => 0.2157,
                            'width' => 0.1509,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4864,
                                    -0.4882
                                ],
                                [
                                    -0.1803,
                                    -0.5
                                ],
                                [
                                    0.1939,
                                    -0.4765
                                ],
                                [
                                    0.3776,
                                    -0.3588
                                ],
                                [
                                    0.466,
                                    -0.1647
                                ],
                                [
                                    0.5,
                                    0.0824
                                ],
                                [
                                    0.3776,
                                    0.2882
                                ],
                                [
                                    0.1599,
                                    0.4235
                                ],
                                [
                                    -0.1463,
                                    0.5
                                ],
                                [
                                    -0.4524,
                                    0.4824
                                ],
                                [
                                    -0.5,
                                    0.2176
                                ],
                                [
                                    -0.5,
                                    -0.1941
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.416,
                            'y' => 0.2092,
                            'width' => 0.0904,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4659,
                                    -0.4626
                                ],
                                [
                                    -0.2386,
                                    -0.5
                                ],
                                [
                                    0.4432,
                                    0.0327
                                ],
                                [
                                    0.5,
                                    0.4533
                                ],
                                [
                                    0.2727,
                                    0.5
                                ],
                                [
                                    -0.2386,
                                    -0.0607
                                ],
                                [
                                    -0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.5885,
                            'y' => 0.2092,
                            'width' => 0.0904,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4659,
                                    -0.4626
                                ],
                                [
                                    0.2386,
                                    -0.5
                                ],
                                [
                                    -0.4432,
                                    0.0327
                                ],
                                [
                                    -0.5,
                                    0.4533
                                ],
                                [
                                    -0.2727,
                                    0.5
                                ],
                                [
                                    0.2386,
                                    -0.0607
                                ],
                                [
                                    0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4263,
                            'y' => 0.1441,
                            'width' => 0.1458,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3451,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4554
                                ],
                                [
                                    0.5,
                                    0.3929
                                ],
                                [
                                    0.3099,
                                    0.5
                                ],
                                [
                                    0.0282,
                                    0.4375
                                ],
                                [
                                    -0.3239,
                                    0.3929
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    -0.3239,
                                    0.2321
                                ],
                                [
                                    0.0282,
                                    0.0179
                                ],
                                [
                                    0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5782,
                            'y' => 0.1441,
                            'width' => 0.1458,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3451,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4554
                                ],
                                [
                                    -0.5,
                                    0.3929
                                ],
                                [
                                    -0.3099,
                                    0.5
                                ],
                                [
                                    -0.0282,
                                    0.4375
                                ],
                                [
                                    0.3239,
                                    0.3929
                                ],
                                [
                                    0.5,
                                    0.375
                                ],
                                [
                                    0.3239,
                                    0.2321
                                ],
                                [
                                    -0.0282,
                                    0.0179
                                ],
                                [
                                    -0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.4807,
                            'y' => 0.2872,
                            'width' => 0.0349,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0294,
                                    -0.5
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2647,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3056
                                ],
                                [
                                    -0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.5238,
                            'y' => 0.2872,
                            'width' => 0.0349,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0294,
                                    -0.5
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2647,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3056
                                ],
                                [
                                    0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rhomboid',
                            'status' => 'weak',
                            'x' => 0.4591,
                            'y' => 0.2041,
                            'width' => 0.0698,
                            'height' => 0.0704,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4118,
                                    -0.46
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.14
                                ],
                                [
                                    0.3971,
                                    0.26
                                ],
                                [
                                    -0.1912,
                                    0.5
                                ],
                                [
                                    -0.4412,
                                    0.46
                                ],
                                [
                                    -0.5,
                                    -0.02
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Rhomboid',
                            'status' => 'weak',
                            'x' => 0.5454,
                            'y' => 0.2041,
                            'width' => 0.0698,
                            'height' => 0.0704,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4118,
                                    -0.46
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.14
                                ],
                                [
                                    -0.3971,
                                    0.26
                                ],
                                [
                                    0.1912,
                                    0.5
                                ],
                                [
                                    0.4412,
                                    0.46
                                ],
                                [
                                    0.5,
                                    -0.02
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Latissimus Dorsi',
                            'status' => 'weak',
                            'x' => 0.4175,
                            'y' => 0.2928,
                            'width' => 0.1386,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.2778,
                                    -0.4167
                                ],
                                [
                                    0.0556,
                                    -0.3333
                                ],
                                [
                                    0.3519,
                                    -0.1667
                                ],
                                [
                                    0.5,
                                    0.1667
                                ],
                                [
                                    0.463,
                                    0.4167
                                ],
                                [
                                    0.2407,
                                    0.5
                                ],
                                [
                                    -0.0556,
                                    0.4167
                                ],
                                [
                                    -0.2778,
                                    0.1667
                                ],
                                [
                                    -0.3889,
                                    -0.0833
                                ],
                                [
                                    -0.463,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Latissimus Dorsi',
                            'status' => 'weak',
                            'x' => 0.5869,
                            'y' => 0.2928,
                            'width' => 0.1386,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.2778,
                                    -0.4167
                                ],
                                [
                                    -0.0556,
                                    -0.3333
                                ],
                                [
                                    -0.3519,
                                    -0.1667
                                ],
                                [
                                    -0.5,
                                    0.1667
                                ],
                                [
                                    -0.463,
                                    0.4167
                                ],
                                [
                                    -0.2407,
                                    0.5
                                ],
                                [
                                    0.0556,
                                    0.4167
                                ],
                                [
                                    0.2778,
                                    0.1667
                                ],
                                [
                                    0.3889,
                                    -0.0833
                                ],
                                [
                                    0.463,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4353,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.05,
                                    -0.5
                                ],
                                [
                                    0.3,
                                    -0.4231
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.1,
                                    0.4231
                                ],
                                [
                                    -0.3,
                                    0.4231
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Pectoralis Major',
                            'status' => 'tight',
                            'x' => 0.5737,
                            'y' => 0.2111,
                            'width' => 0.1027,
                            'height' => 0.0845,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4783,
                                    -0.5
                                ],
                                [
                                    -0.1522,
                                    -0.4
                                ],
                                [
                                    0.1739,
                                    -0.2
                                ],
                                [
                                    0.4457,
                                    0.0667
                                ],
                                [
                                    0.5,
                                    0.3
                                ],
                                [
                                    0.337,
                                    0.4667
                                ],
                                [
                                    -0.0435,
                                    0.5
                                ],
                                [
                                    -0.4239,
                                    0.3667
                                ],
                                [
                                    -0.4783,
                                    0.1
                                ],
                                [
                                    -0.5,
                                    -0.2333
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.3929,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    0.25,
                                    0.197
                                ],
                                [
                                    0.5,
                                    0.4697
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.1346,
                                    0.197
                                ],
                                [
                                    -0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rhomboid',
                            'status' => 'weak',
                            'x' => 0.3789,
                            'y' => 0.1971,
                            'width' => 0.0346,
                            'height' => 0.0338,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.371,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.4032,
                                    0.5
                                ],
                                [
                                    -0.3065,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Latissimus Dorsi',
                            'status' => 'weak',
                            'x' => 0.385,
                            'y' => 0.2759,
                            'width' => 0.0335,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.4286
                                ],
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0714
                                ],
                                [
                                    0.3333,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    -0.4333,
                                    0.0714
                                ]
                            ],
                            'order_index' => 4
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5647,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.05,
                                    -0.5
                                ],
                                [
                                    -0.3,
                                    -0.4231
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.1,
                                    0.4231
                                ],
                                [
                                    0.3,
                                    0.4231
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Pectoralis Major',
                            'status' => 'tight',
                            'x' => 0.4263,
                            'y' => 0.2111,
                            'width' => 0.1027,
                            'height' => 0.0845,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4783,
                                    -0.5
                                ],
                                [
                                    0.1522,
                                    -0.4
                                ],
                                [
                                    -0.1739,
                                    -0.2
                                ],
                                [
                                    -0.4457,
                                    0.0667
                                ],
                                [
                                    -0.5,
                                    0.3
                                ],
                                [
                                    -0.337,
                                    0.4667
                                ],
                                [
                                    0.0435,
                                    0.5
                                ],
                                [
                                    0.4239,
                                    0.3667
                                ],
                                [
                                    0.4783,
                                    0.1
                                ],
                                [
                                    0.5,
                                    -0.2333
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.6071,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    -0.25,
                                    0.197
                                ],
                                [
                                    -0.5,
                                    0.4697
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.1346,
                                    0.197
                                ],
                                [
                                    0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rhomboid',
                            'status' => 'weak',
                            'x' => 0.6211,
                            'y' => 0.1971,
                            'width' => 0.0346,
                            'height' => 0.0338,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.371,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.4032,
                                    0.5
                                ],
                                [
                                    0.3065,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Latissimus Dorsi',
                            'status' => 'weak',
                            'x' => 0.615,
                            'y' => 0.2759,
                            'width' => 0.0335,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.4286
                                ],
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0714
                                ],
                                [
                                    -0.3333,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    0.4333,
                                    0.0714
                                ]
                            ],
                            'order_index' => 4
                        ]
                    ]
                ]
            ]
        ],
        'lordosis' => [
            'name' => 'Lordosis',
            'description' => 'An increased inward curve of the lower back. The pelvis tips forward and the stomach and buttocks push out.',
            'effects' => 'Lower-back aches after standing or walking
Stomach pushes forward
Tight hip flexors and lower back
Weak core and buttock muscles',
            'detection' => [
                'sections' => [],
                'classifications' => [
                    'LORDOSIS',
                    'KYPHOSIS_LORDOSIS'
                ]
            ],
            'order_index' => 2,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.5022,
                            'y' => 0.3373,
                            'width' => 0.1518,
                            'height' => 0.1475,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3309,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4924
                                ],
                                [
                                    0.3309,
                                    -0.5
                                ],
                                [
                                    0.4559,
                                    -0.3015
                                ],
                                [
                                    0.5,
                                    0.0038
                                ],
                                [
                                    0.4412,
                                    0.3092
                                ],
                                [
                                    0.2941,
                                    0.4618
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2941,
                                    0.4618
                                ],
                                [
                                    -0.4412,
                                    0.3092
                                ],
                                [
                                    -0.5,
                                    0.0038
                                ],
                                [
                                    -0.4559,
                                    -0.3015
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 2
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.4788,
                            'y' => 0.2872,
                            'width' => 0.0379,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0294,
                                    -0.5
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2647,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3056
                                ],
                                [
                                    -0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.5257,
                            'y' => 0.2872,
                            'width' => 0.0379,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0294,
                                    -0.5
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2647,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3056
                                ],
                                [
                                    0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.3929,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    0.25,
                                    0.197
                                ],
                                [
                                    0.5,
                                    0.4697
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.1346,
                                    0.197
                                ],
                                [
                                    -0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.5932,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ],
                                [
                                    0.3868,
                                    0.125
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.125
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 4
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.6071,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    -0.25,
                                    0.197
                                ],
                                [
                                    -0.5,
                                    0.4697
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.1346,
                                    0.197
                                ],
                                [
                                    0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.4068,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ],
                                [
                                    -0.3868,
                                    0.125
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.125
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 4
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.5022,
                            'y' => 0.3373,
                            'width' => 0.1396,
                            'height' => 0.1475,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3309,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4924
                                ],
                                [
                                    0.3309,
                                    -0.5
                                ],
                                [
                                    0.4559,
                                    -0.3015
                                ],
                                [
                                    0.5,
                                    0.0038
                                ],
                                [
                                    0.4412,
                                    0.3092
                                ],
                                [
                                    0.3197,
                                    0.4618
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.3197,
                                    0.4618
                                ],
                                [
                                    -0.4412,
                                    0.3092
                                ],
                                [
                                    -0.5,
                                    0.0038
                                ],
                                [
                                    -0.4559,
                                    -0.3015
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 2
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.4807,
                            'y' => 0.2872,
                            'width' => 0.0349,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0294,
                                    -0.5
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2647,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3056
                                ],
                                [
                                    -0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.5238,
                            'y' => 0.2872,
                            'width' => 0.0349,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0294,
                                    -0.5
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2647,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3056
                                ],
                                [
                                    0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.3929,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    0.25,
                                    0.197
                                ],
                                [
                                    0.5,
                                    0.4697
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.1346,
                                    0.197
                                ],
                                [
                                    -0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.5932,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ],
                                [
                                    0.3868,
                                    0.125
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.125
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 4
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'tight',
                            'x' => 0.6071,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    -0.25,
                                    0.197
                                ],
                                [
                                    -0.5,
                                    0.4697
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.1346,
                                    0.197
                                ],
                                [
                                    0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.4068,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ],
                                [
                                    -0.3868,
                                    0.125
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.125
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 4
                        ]
                    ]
                ]
            ]
        ],
        'flat_back' => [
            'name' => 'Flat Back',
            'description' => 'The natural inward curve of the lower back is reduced, so the spine looks straight and the pelvis tucks under.',
            'effects' => 'Difficulty standing upright for long periods
Lower-back and thigh fatigue
Body leans slightly forward
Reduced shock absorption of the spine',
            'detection' => [
                'sections' => [],
                'classifications' => [
                    'FLAT_BACK',
                    'FLATBACK'
                ]
            ],
            'order_index' => 3,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.3373,
                            'width' => 0.1518,
                            'height' => 0.1475,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3309,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4924
                                ],
                                [
                                    0.3309,
                                    -0.5
                                ],
                                [
                                    0.4559,
                                    -0.3015
                                ],
                                [
                                    0.5,
                                    0.0038
                                ],
                                [
                                    0.4412,
                                    0.3092
                                ],
                                [
                                    0.2941,
                                    0.4618
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2941,
                                    0.4618
                                ],
                                [
                                    -0.4412,
                                    0.3092
                                ],
                                [
                                    -0.5,
                                    0.0038
                                ],
                                [
                                    -0.4559,
                                    -0.3015
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.3878,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.0333,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.6166,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.0333,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 6
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.4788,
                            'y' => 0.2872,
                            'width' => 0.0379,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0294,
                                    -0.5
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2647,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3056
                                ],
                                [
                                    -0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.5257,
                            'y' => 0.2872,
                            'width' => 0.0379,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0294,
                                    -0.5
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2647,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3056
                                ],
                                [
                                    0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Multifidus',
                            'status' => 'weak',
                            'x' => 0.4916,
                            'y' => 0.3688,
                            'width' => 0.0145,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Multifidus',
                            'status' => 'weak',
                            'x' => 0.5128,
                            'y' => 0.3688,
                            'width' => 0.0145,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.3929,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    0.25,
                                    0.197
                                ],
                                [
                                    0.5,
                                    0.4697
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.1346,
                                    0.197
                                ],
                                [
                                    -0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.5932,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ],
                                [
                                    0.3868,
                                    0.125
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.125
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.6071,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    -0.25,
                                    0.197
                                ],
                                [
                                    -0.5,
                                    0.4697
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.1346,
                                    0.197
                                ],
                                [
                                    0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.4068,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ],
                                [
                                    -0.3868,
                                    0.125
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.125
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.3373,
                            'width' => 0.1396,
                            'height' => 0.1475,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3309,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4924
                                ],
                                [
                                    0.3309,
                                    -0.5
                                ],
                                [
                                    0.4559,
                                    -0.3015
                                ],
                                [
                                    0.5,
                                    0.0038
                                ],
                                [
                                    0.4412,
                                    0.3092
                                ],
                                [
                                    0.3197,
                                    0.4618
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.3197,
                                    0.4618
                                ],
                                [
                                    -0.4412,
                                    0.3092
                                ],
                                [
                                    -0.5,
                                    0.0038
                                ],
                                [
                                    -0.4559,
                                    -0.3015
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.397,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.1551,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.6075,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.1551,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 6
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.4807,
                            'y' => 0.2872,
                            'width' => 0.0349,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0294,
                                    -0.5
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2647,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3056
                                ],
                                [
                                    -0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.5238,
                            'y' => 0.2872,
                            'width' => 0.0349,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0294,
                                    -0.5
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2647,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3056
                                ],
                                [
                                    0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Multifidus',
                            'status' => 'weak',
                            'x' => 0.4925,
                            'y' => 0.3688,
                            'width' => 0.0133,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Multifidus',
                            'status' => 'weak',
                            'x' => 0.512,
                            'y' => 0.3688,
                            'width' => 0.0133,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.3929,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    0.25,
                                    0.197
                                ],
                                [
                                    0.5,
                                    0.4697
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.1346,
                                    0.197
                                ],
                                [
                                    -0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.5932,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ],
                                [
                                    0.3868,
                                    0.125
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.125
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.6071,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    -0.25,
                                    0.197
                                ],
                                [
                                    -0.5,
                                    0.4697
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.1346,
                                    0.197
                                ],
                                [
                                    0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.4068,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ],
                                [
                                    -0.3868,
                                    0.125
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.125
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ]
                ]
            ]
        ],
        'sway_back' => [
            'name' => 'Sway Back',
            'description' => 'The pelvis shifts forward and the upper body leans back behind it, giving a swayed standing posture.',
            'effects' => 'Upper body leans backwards
Hips pushed forward of the feet
Lower-back and hip discomfort
Weak buttock and hip flexor muscles',
            'detection' => [
                'sections' => [],
                'classifications' => [
                    'SWAYBACK',
                    'SWAY_BACK'
                ]
            ],
            'order_index' => 4,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis (Upper)',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.2894,
                            'width' => 0.1451,
                            'height' => 0.0518,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4783
                                ],
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    0.4769,
                                    0.0652
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.4769,
                                    0.0652
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Obliques (Upper)',
                            'status' => 'tight',
                            'x' => 0.3878,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.0333,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Obliques (Upper)',
                            'status' => 'tight',
                            'x' => 0.6166,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.0333,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 6
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.4788,
                            'y' => 0.3632,
                            'width' => 0.0379,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2647,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.3235,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.5257,
                            'y' => 0.3632,
                            'width' => 0.0379,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2647,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.3235,
                                    0.5
                                ],
                                [
                                    0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Rectus Abdominis (Upper)',
                            'status' => 'tight',
                            'x' => 0.5932,
                            'y' => 0.2843,
                            'width' => 0.0592,
                            'height' => 0.0619,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.0455
                                ],
                                [
                                    0.4623,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    -0.0455
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.4085,
                            'y' => 0.3378,
                            'width' => 0.0357,
                            'height' => 0.0676,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.0625,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.3333
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.4375,
                                    0
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Rectus Abdominis (Upper)',
                            'status' => 'tight',
                            'x' => 0.4068,
                            'y' => 0.2843,
                            'width' => 0.0592,
                            'height' => 0.0619,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.0455
                                ],
                                [
                                    -0.4623,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.5,
                                    -0.0455
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.5915,
                            'y' => 0.3378,
                            'width' => 0.0357,
                            'height' => 0.0676,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.0625,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.3333
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.4375,
                                    0
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis (Upper)',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.2894,
                            'width' => 0.1335,
                            'height' => 0.0518,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4783
                                ],
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    0.4769,
                                    0.0652
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.4769,
                                    0.0652
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Obliques (Upper)',
                            'status' => 'tight',
                            'x' => 0.397,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.1551,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Obliques (Upper)',
                            'status' => 'tight',
                            'x' => 0.6075,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.1551,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 6
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.4807,
                            'y' => 0.3632,
                            'width' => 0.0349,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2647,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.3235,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.5238,
                            'y' => 0.3632,
                            'width' => 0.0349,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2647,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.3235,
                                    0.5
                                ],
                                [
                                    0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Rectus Abdominis (Upper)',
                            'status' => 'tight',
                            'x' => 0.5932,
                            'y' => 0.2843,
                            'width' => 0.0592,
                            'height' => 0.0619,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.0455
                                ],
                                [
                                    0.4623,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    -0.0455
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.4085,
                            'y' => 0.3378,
                            'width' => 0.0357,
                            'height' => 0.0676,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.0625,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.3333
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.4375,
                                    0
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Rectus Abdominis (Upper)',
                            'status' => 'tight',
                            'x' => 0.4068,
                            'y' => 0.2843,
                            'width' => 0.0592,
                            'height' => 0.0619,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.0455
                                ],
                                [
                                    -0.4623,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.5,
                                    -0.0455
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.5915,
                            'y' => 0.3378,
                            'width' => 0.0357,
                            'height' => 0.0676,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.0625,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.3333
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.4375,
                                    0
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ]
                ]
            ]
        ],
        'postural_scoliosis' => [
            'name' => 'Postural Scoliosis',
            'description' => 'A sideways shift of the spine caused by muscle imbalance. The muscles on the inner (concave) side shorten and the outer (convex) side lengthens.',
            'effects' => 'Shoulders sit at different heights
Uneven waist line
One-sided back tightness
Body shifts to one side when standing',
            'detection' => [
                'sections' => [
                    [
                        'level' => [
                            'moderate',
                            'severe'
                        ],
                        'section' => 'A5'
                    ],
                    [
                        'level' => [
                            'moderate',
                            'severe'
                        ],
                        'section' => 'A4'
                    ],
                    [
                        'level' => [
                            'moderate',
                            'severe'
                        ],
                        'section' => 'B4'
                    ]
                ],
                'classifications' => [
                    'PELVIC_OBLIQUITY'
                ]
            ],
            'order_index' => 5,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'External Oblique (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.3878,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.0333,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Internal Oblique (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.3702,
                            'width' => 0.0725,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.3462,
                                    -0.413
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.2692,
                                    0.3696
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'External Oblique (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.6166,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.0333,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Internal Oblique (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.6055,
                            'y' => 0.3702,
                            'width' => 0.0725,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.3462,
                                    -0.413
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.2692,
                                    0.3696
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.4788,
                            'y' => 0.2872,
                            'width' => 0.0379,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0294,
                                    -0.5
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2647,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3056
                                ],
                                [
                                    -0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.5257,
                            'y' => 0.2872,
                            'width' => 0.0379,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0294,
                                    -0.5
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2647,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3056
                                ],
                                [
                                    0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Multifidus (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.4916,
                            'y' => 0.3688,
                            'width' => 0.0145,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Multifidus (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.5128,
                            'y' => 0.3688,
                            'width' => 0.0145,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Quadratus Lumborum (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.4503,
                            'y' => 0.3674,
                            'width' => 0.0413,
                            'height' => 0.0422,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2297,
                                    -0.4333
                                ],
                                [
                                    0.4459,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2297,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Quadratus Lumborum (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.5541,
                            'y' => 0.3674,
                            'width' => 0.0413,
                            'height' => 0.0422,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2297,
                                    -0.4333
                                ],
                                [
                                    -0.4459,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2297,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.1
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Obliques (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.5424,
                            'y' => 0.3238,
                            'width' => 0.0536,
                            'height' => 0.1295,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.3958,
                                    -0.4783
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ],
                                [
                                    0.5,
                                    0.4565
                                ],
                                [
                                    -0.125,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.4375,
                                    -0.1522
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.3929,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    0.25,
                                    0.197
                                ],
                                [
                                    0.5,
                                    0.4697
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.1346,
                                    0.197
                                ],
                                [
                                    -0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Obliques (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.4576,
                            'y' => 0.3238,
                            'width' => 0.0536,
                            'height' => 0.1295,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.3958,
                                    -0.4783
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ],
                                [
                                    -0.5,
                                    0.4565
                                ],
                                [
                                    0.125,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.4375,
                                    -0.1522
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.6071,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    -0.25,
                                    0.197
                                ],
                                [
                                    -0.5,
                                    0.4697
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.1346,
                                    0.197
                                ],
                                [
                                    0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'External Oblique (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.397,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.1551,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Internal Oblique (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.4073,
                            'y' => 0.3702,
                            'width' => 0.0667,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.3462,
                                    -0.413
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.0853,
                                    0.5
                                ],
                                [
                                    -0.4164,
                                    0.3696
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'External Oblique (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.6075,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.1551,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Internal Oblique (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.5972,
                            'y' => 0.3702,
                            'width' => 0.0667,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.3462,
                                    -0.413
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.0853,
                                    0.5
                                ],
                                [
                                    0.4164,
                                    0.3696
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.4807,
                            'y' => 0.2872,
                            'width' => 0.0349,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0294,
                                    -0.5
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2647,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3056
                                ],
                                [
                                    -0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.5238,
                            'y' => 0.2872,
                            'width' => 0.0349,
                            'height' => 0.2027,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0294,
                                    -0.5
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2647,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3056
                                ],
                                [
                                    0.3235,
                                    -0.1667
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Multifidus (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.4925,
                            'y' => 0.3688,
                            'width' => 0.0133,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Multifidus (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.512,
                            'y' => 0.3688,
                            'width' => 0.0133,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Quadratus Lumborum (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.4545,
                            'y' => 0.3674,
                            'width' => 0.038,
                            'height' => 0.0422,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2297,
                                    -0.4333
                                ],
                                [
                                    0.4459,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2297,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Quadratus Lumborum (Convex Side)',
                            'status' => 'weak',
                            'x' => 0.55,
                            'y' => 0.3674,
                            'width' => 0.038,
                            'height' => 0.0422,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2297,
                                    -0.4333
                                ],
                                [
                                    -0.4459,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2297,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.1
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Obliques (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.5424,
                            'y' => 0.3238,
                            'width' => 0.0536,
                            'height' => 0.1295,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.3958,
                                    -0.4783
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ],
                                [
                                    0.5,
                                    0.4565
                                ],
                                [
                                    -0.125,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.4375,
                                    -0.1522
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.3929,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    0.25,
                                    0.197
                                ],
                                [
                                    0.5,
                                    0.4697
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.1346,
                                    0.197
                                ],
                                [
                                    -0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Obliques (Concave Side)',
                            'status' => 'tight',
                            'x' => 0.4576,
                            'y' => 0.3238,
                            'width' => 0.0536,
                            'height' => 0.1295,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.3958,
                                    -0.4783
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ],
                                [
                                    -0.5,
                                    0.4565
                                ],
                                [
                                    0.125,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.4375,
                                    -0.1522
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae',
                            'status' => 'weak',
                            'x' => 0.6071,
                            'y' => 0.2787,
                            'width' => 0.058,
                            'height' => 0.1858,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.1346,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.1364
                                ],
                                [
                                    -0.25,
                                    0.197
                                ],
                                [
                                    -0.5,
                                    0.4697
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.1346,
                                    0.197
                                ],
                                [
                                    0.4231,
                                    -0.1364
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ]
                ]
            ]
        ],
        'anterior_pelvic_tilt' => [
            'name' => 'Anterior Pelvic Tilt',
            'description' => 'The front of the pelvis drops and the back of the pelvis rises, increasing the lower-back curve.',
            'effects' => 'Lower-back tightness or pain
Stomach and buttocks stick out
Tight front of the hips
Weak core, buttock and hamstring muscles',
            'detection' => [
                'sections' => [
                    [
                        'level' => [
                            'moderate',
                            'severe'
                        ],
                        'section' => 'side_lordosis',
                        'direction' => 'above'
                    ]
                ],
                'classifications' => []
            ],
            'order_index' => 6,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.5022,
                            'y' => 0.3373,
                            'width' => 0.1518,
                            'height' => 0.1475,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3309,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4924
                                ],
                                [
                                    0.3309,
                                    -0.5
                                ],
                                [
                                    0.4559,
                                    -0.3015
                                ],
                                [
                                    0.5,
                                    0.0038
                                ],
                                [
                                    0.4412,
                                    0.3092
                                ],
                                [
                                    0.2941,
                                    0.4618
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2941,
                                    0.4618
                                ],
                                [
                                    -0.4412,
                                    0.3092
                                ],
                                [
                                    -0.5,
                                    0.0038
                                ],
                                [
                                    -0.4559,
                                    -0.3015
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'weak',
                            'x' => 0.3878,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.0333,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'weak',
                            'x' => 0.6166,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.0333,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Internal Oblique',
                            'status' => 'weak',
                            'x' => 0.399,
                            'y' => 0.3702,
                            'width' => 0.0725,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.3462,
                                    -0.413
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.2692,
                                    0.3696
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Internal Oblique',
                            'status' => 'weak',
                            'x' => 0.6055,
                            'y' => 0.3702,
                            'width' => 0.0725,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.3462,
                                    -0.413
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.2692,
                                    0.3696
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.3432,
                            'y' => 0.4744,
                            'width' => 0.0413,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3108,
                                    -0.5
                                ],
                                [
                                    0.2838,
                                    -0.4744
                                ],
                                [
                                    0.5,
                                    -0.2179
                                ],
                                [
                                    0.3378,
                                    0.141
                                ],
                                [
                                    0.0676,
                                    0.5
                                ],
                                [
                                    -0.2838,
                                    0.5
                                ],
                                [
                                    -0.473,
                                    0.0897
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.6613,
                            'y' => 0.4744,
                            'width' => 0.0413,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3108,
                                    -0.5
                                ],
                                [
                                    -0.2838,
                                    -0.4744
                                ],
                                [
                                    -0.5,
                                    -0.2179
                                ],
                                [
                                    -0.3378,
                                    0.141
                                ],
                                [
                                    -0.0676,
                                    0.5
                                ],
                                [
                                    0.2838,
                                    0.5
                                ],
                                [
                                    0.473,
                                    0.0897
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'tight',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 7
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'tight',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 8
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'tight',
                            'x' => 0.4788,
                            'y' => 0.3632,
                            'width' => 0.0379,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2647,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.3235,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'tight',
                            'x' => 0.5257,
                            'y' => 0.3632,
                            'width' => 0.0379,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2647,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.3235,
                                    0.5
                                ],
                                [
                                    0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Quadratus Lumborum (QL)',
                            'status' => 'tight',
                            'x' => 0.4503,
                            'y' => 0.3674,
                            'width' => 0.0413,
                            'height' => 0.0422,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2297,
                                    -0.4333
                                ],
                                [
                                    0.4459,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2297,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.1
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Quadratus Lumborum (QL)',
                            'status' => 'tight',
                            'x' => 0.5541,
                            'y' => 0.3674,
                            'width' => 0.0413,
                            'height' => 0.0422,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2297,
                                    -0.4333
                                ],
                                [
                                    -0.4459,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2297,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.1
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'tight',
                            'x' => 0.4085,
                            'y' => 0.3378,
                            'width' => 0.0357,
                            'height' => 0.0676,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.0625,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.3333
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.4375,
                                    0
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.4448,
                            'width' => 0.0446,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.25,
                                    -0.4643
                                ],
                                [
                                    0.5,
                                    0
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    -0.45,
                                    0
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'tight',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.5932,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ],
                                [
                                    0.3868,
                                    0.125
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.125
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 6
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'tight',
                            'x' => 0.5915,
                            'y' => 0.3378,
                            'width' => 0.0357,
                            'height' => 0.0676,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.0625,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.3333
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.4375,
                                    0
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.4978,
                            'y' => 0.4448,
                            'width' => 0.0446,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.25,
                                    -0.4643
                                ],
                                [
                                    -0.5,
                                    0
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    0.45,
                                    0
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'tight',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.4068,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ],
                                [
                                    -0.3868,
                                    0.125
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.125
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 6
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.5022,
                            'y' => 0.3373,
                            'width' => 0.1396,
                            'height' => 0.1475,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3309,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4924
                                ],
                                [
                                    0.3309,
                                    -0.5
                                ],
                                [
                                    0.4559,
                                    -0.3015
                                ],
                                [
                                    0.5,
                                    0.0038
                                ],
                                [
                                    0.4412,
                                    0.3092
                                ],
                                [
                                    0.3197,
                                    0.4618
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.3197,
                                    0.4618
                                ],
                                [
                                    -0.4412,
                                    0.3092
                                ],
                                [
                                    -0.5,
                                    0.0038
                                ],
                                [
                                    -0.4559,
                                    -0.3015
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'weak',
                            'x' => 0.397,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.1551,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'weak',
                            'x' => 0.6075,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.1551,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Internal Oblique',
                            'status' => 'weak',
                            'x' => 0.4073,
                            'y' => 0.3702,
                            'width' => 0.0667,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.3462,
                                    -0.413
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.0853,
                                    0.5
                                ],
                                [
                                    -0.4164,
                                    0.3696
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Internal Oblique',
                            'status' => 'weak',
                            'x' => 0.5972,
                            'y' => 0.3702,
                            'width' => 0.0667,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.3462,
                                    -0.413
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.0853,
                                    0.5
                                ],
                                [
                                    0.4164,
                                    0.3696
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.3432,
                            'y' => 0.4744,
                            'width' => 0.0413,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3108,
                                    -0.5
                                ],
                                [
                                    0.2838,
                                    -0.4744
                                ],
                                [
                                    0.5,
                                    -0.2179
                                ],
                                [
                                    0.3378,
                                    0.141
                                ],
                                [
                                    0.0676,
                                    0.5
                                ],
                                [
                                    -0.2838,
                                    0.5
                                ],
                                [
                                    -0.473,
                                    0.0897
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.6613,
                            'y' => 0.4744,
                            'width' => 0.0413,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3108,
                                    -0.5
                                ],
                                [
                                    -0.2838,
                                    -0.4744
                                ],
                                [
                                    -0.5,
                                    -0.2179
                                ],
                                [
                                    -0.3378,
                                    0.141
                                ],
                                [
                                    -0.0676,
                                    0.5
                                ],
                                [
                                    0.2838,
                                    0.5
                                ],
                                [
                                    0.473,
                                    0.0897
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'tight',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 7
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'tight',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 8
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'tight',
                            'x' => 0.4807,
                            'y' => 0.3632,
                            'width' => 0.0349,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2647,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.3235,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'tight',
                            'x' => 0.5238,
                            'y' => 0.3632,
                            'width' => 0.0349,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2647,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.3235,
                                    0.5
                                ],
                                [
                                    0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Quadratus Lumborum (QL)',
                            'status' => 'tight',
                            'x' => 0.4545,
                            'y' => 0.3674,
                            'width' => 0.038,
                            'height' => 0.0422,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2297,
                                    -0.4333
                                ],
                                [
                                    0.4459,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.2297,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.1
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Quadratus Lumborum (QL)',
                            'status' => 'tight',
                            'x' => 0.55,
                            'y' => 0.3674,
                            'width' => 0.038,
                            'height' => 0.0422,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2297,
                                    -0.4333
                                ],
                                [
                                    -0.4459,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.2297,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.1
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'tight',
                            'x' => 0.4085,
                            'y' => 0.3378,
                            'width' => 0.0357,
                            'height' => 0.0676,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.0625,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.3333
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.4375,
                                    0
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.4448,
                            'width' => 0.0446,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.25,
                                    -0.4643
                                ],
                                [
                                    0.5,
                                    0
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    -0.45,
                                    0
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'tight',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.5932,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ],
                                [
                                    0.3868,
                                    0.125
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.125
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 6
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'tight',
                            'x' => 0.5915,
                            'y' => 0.3378,
                            'width' => 0.0357,
                            'height' => 0.0676,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.0625,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.3333
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.4375,
                                    0
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.4978,
                            'y' => 0.4448,
                            'width' => 0.0446,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.25,
                                    -0.4643
                                ],
                                [
                                    -0.5,
                                    0
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    0.45,
                                    0
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'tight',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'weak',
                            'x' => 0.4068,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ],
                                [
                                    -0.3868,
                                    0.125
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.125
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 6
                        ]
                    ]
                ]
            ]
        ],
        'posterior_pelvic_tilt' => [
            'name' => 'Posterior Pelvic Tilt',
            'description' => 'The front of the pelvis rises and the back of the pelvis drops, flattening the lower back.',
            'effects' => 'Flattened lower back
Tucked-under buttocks
Tight hamstrings and abdominals
Lower-back fatigue when sitting or standing',
            'detection' => [
                'sections' => [
                    [
                        'level' => [
                            'moderate',
                            'severe'
                        ],
                        'section' => 'side_lordosis',
                        'direction' => 'below'
                    ]
                ],
                'classifications' => []
            ],
            'order_index' => 7,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.3373,
                            'width' => 0.1518,
                            'height' => 0.1475,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3309,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4924
                                ],
                                [
                                    0.3309,
                                    -0.5
                                ],
                                [
                                    0.4559,
                                    -0.3015
                                ],
                                [
                                    0.5,
                                    0.0038
                                ],
                                [
                                    0.4412,
                                    0.3092
                                ],
                                [
                                    0.2941,
                                    0.4618
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2941,
                                    0.4618
                                ],
                                [
                                    -0.4412,
                                    0.3092
                                ],
                                [
                                    -0.5,
                                    0.0038
                                ],
                                [
                                    -0.4559,
                                    -0.3015
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.3878,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.0333,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.6166,
                            'y' => 0.3209,
                            'width' => 0.0837,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.0333,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Internal Oblique',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.3702,
                            'width' => 0.0725,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.3462,
                                    -0.413
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.1923,
                                    0.5
                                ],
                                [
                                    -0.2692,
                                    0.3696
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Internal Oblique',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.3702,
                            'width' => 0.0725,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.3462,
                                    -0.413
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.1923,
                                    0.5
                                ],
                                [
                                    0.2692,
                                    0.3696
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 7
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 8
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.4788,
                            'y' => 0.3632,
                            'width' => 0.0379,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2647,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.3235,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.5257,
                            'y' => 0.3632,
                            'width' => 0.0379,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2647,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.3235,
                                    0.5
                                ],
                                [
                                    0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Multifidus (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.4916,
                            'y' => 0.3688,
                            'width' => 0.0145,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Multifidus (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.5128,
                            'y' => 0.3688,
                            'width' => 0.0145,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'tight',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'tight',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.5932,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ],
                                [
                                    0.3868,
                                    0.125
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.125
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.5424,
                            'y' => 0.3238,
                            'width' => 0.0536,
                            'height' => 0.1295,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.3958,
                                    -0.4783
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ],
                                [
                                    0.5,
                                    0.4565
                                ],
                                [
                                    -0.125,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.4375,
                                    -0.1522
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'tight',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.4068,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ],
                                [
                                    -0.3868,
                                    0.125
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.125
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.4576,
                            'y' => 0.3238,
                            'width' => 0.0536,
                            'height' => 0.1295,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.3958,
                                    -0.4783
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ],
                                [
                                    -0.5,
                                    0.4565
                                ],
                                [
                                    0.125,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.4375,
                                    -0.1522
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'tight',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.3373,
                            'width' => 0.1396,
                            'height' => 0.1475,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3309,
                                    -0.5
                                ],
                                [
                                    0,
                                    -0.4924
                                ],
                                [
                                    0.3309,
                                    -0.5
                                ],
                                [
                                    0.4559,
                                    -0.3015
                                ],
                                [
                                    0.5,
                                    0.0038
                                ],
                                [
                                    0.4412,
                                    0.3092
                                ],
                                [
                                    0.3197,
                                    0.4618
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.3197,
                                    0.4618
                                ],
                                [
                                    -0.4412,
                                    0.3092
                                ],
                                [
                                    -0.5,
                                    0.0038
                                ],
                                [
                                    -0.4559,
                                    -0.3015
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.397,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3,
                                    -0.5
                                ],
                                [
                                    0.0333,
                                    -0.3846
                                ],
                                [
                                    0.5,
                                    -0.1923
                                ],
                                [
                                    0.5,
                                    0.2692
                                ],
                                [
                                    0.4067,
                                    0.4615
                                ],
                                [
                                    -0.1551,
                                    0.5
                                ],
                                [
                                    -0.34,
                                    0.3462
                                ],
                                [
                                    -0.4733,
                                    0.0385
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.6075,
                            'y' => 0.3209,
                            'width' => 0.077,
                            'height' => 0.1464,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3,
                                    -0.5
                                ],
                                [
                                    -0.0333,
                                    -0.3846
                                ],
                                [
                                    -0.5,
                                    -0.1923
                                ],
                                [
                                    -0.5,
                                    0.2692
                                ],
                                [
                                    -0.4067,
                                    0.4615
                                ],
                                [
                                    0.1551,
                                    0.5
                                ],
                                [
                                    0.34,
                                    0.3462
                                ],
                                [
                                    0.4733,
                                    0.0385
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Internal Oblique',
                            'status' => 'tight',
                            'x' => 0.4073,
                            'y' => 0.3702,
                            'width' => 0.0667,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.3462,
                                    -0.413
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.0853,
                                    0.5
                                ],
                                [
                                    -0.4164,
                                    0.3696
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Internal Oblique',
                            'status' => 'tight',
                            'x' => 0.5972,
                            'y' => 0.3702,
                            'width' => 0.0667,
                            'height' => 0.0648,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.3462,
                                    -0.413
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.0853,
                                    0.5
                                ],
                                [
                                    0.4164,
                                    0.3696
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 7
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 8
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.4807,
                            'y' => 0.3632,
                            'width' => 0.0349,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2647,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.3235,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Erector Spinae (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.5238,
                            'y' => 0.3632,
                            'width' => 0.0349,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2647,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.3235,
                                    0.5
                                ],
                                [
                                    0.5,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Multifidus (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.4925,
                            'y' => 0.3688,
                            'width' => 0.0133,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3462,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Multifidus (Lumbar)',
                            'status' => 'weak',
                            'x' => 0.512,
                            'y' => 0.3688,
                            'width' => 0.0133,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3462,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'tight',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'tight',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.5932,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4057,
                                    -0.5
                                ],
                                [
                                    0.2547,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ],
                                [
                                    0.3868,
                                    0.125
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.125
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.5424,
                            'y' => 0.3238,
                            'width' => 0.0536,
                            'height' => 0.1295,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.3958,
                                    -0.4783
                                ],
                                [
                                    0.5,
                                    -0.0652
                                ],
                                [
                                    0.5,
                                    0.4565
                                ],
                                [
                                    -0.125,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.1957
                                ],
                                [
                                    -0.4375,
                                    -0.1522
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'tight',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Rectus Abdominis',
                            'status' => 'tight',
                            'x' => 0.4068,
                            'y' => 0.3209,
                            'width' => 0.0592,
                            'height' => 0.1351,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4057,
                                    -0.5
                                ],
                                [
                                    -0.2547,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2917
                                ],
                                [
                                    -0.3868,
                                    0.125
                                ],
                                [
                                    -0.3113,
                                    0.5
                                ],
                                [
                                    0.3113,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.125
                                ],
                                [
                                    0.5,
                                    -0.2917
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'External Oblique',
                            'status' => 'tight',
                            'x' => 0.4576,
                            'y' => 0.3238,
                            'width' => 0.0536,
                            'height' => 0.1295,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.3958,
                                    -0.4783
                                ],
                                [
                                    -0.5,
                                    -0.0652
                                ],
                                [
                                    -0.5,
                                    0.4565
                                ],
                                [
                                    0.125,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.1957
                                ],
                                [
                                    0.4375,
                                    -0.1522
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'weak',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Rectus Femoris',
                            'status' => 'weak',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'tight',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ]
                ]
            ]
        ],
        'uneven_shoulder' => [
            'name' => 'Uneven Shoulders',
            'description' => 'One shoulder sits higher than the other because the neck and upper-shoulder muscles pull unevenly.',
            'effects' => 'One shoulder visibly higher
Neck and shoulder tension on one side
Headaches or neck stiffness
Uneven arm swing and carrying posture',
            'detection' => [
                'sections' => [
                    [
                        'level' => [
                            'moderate',
                            'severe'
                        ],
                        'section' => 'A3'
                    ],
                    [
                        'level' => [
                            'moderate',
                            'severe'
                        ],
                        'section' => 'B3'
                    ]
                ],
                'classifications' => []
            ],
            'order_index' => 8,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Sternocleidomastoid (SCM)',
                            'status' => 'tight',
                            'x' => 0.4715,
                            'y' => 0.1484,
                            'width' => 0.0547,
                            'height' => 0.0467,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.2143,
                                    -0.4518
                                ],
                                [
                                    0.3571,
                                    0.259
                                ],
                                [
                                    0.5,
                                    0.4639
                                ],
                                [
                                    0.1531,
                                    0.5
                                ],
                                [
                                    -0.1531,
                                    0.1988
                                ],
                                [
                                    -0.4184,
                                    -0.1627
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Scalenes',
                            'status' => 'tight',
                            'x' => 0.4436,
                            'y' => 0.1515,
                            'width' => 0.0391,
                            'height' => 0.0349,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    0.1286,
                                    -0.4677
                                ],
                                [
                                    0.5,
                                    0.0968
                                ],
                                [
                                    0.2143,
                                    0.5
                                ],
                                [
                                    -0.3571,
                                    0.4355
                                ],
                                [
                                    -0.5,
                                    0.0161
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor (if involved)',
                            'status' => 'tight',
                            'x' => 0.4085,
                            'y' => 0.2092,
                            'width' => 0.0982,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4659,
                                    -0.4626
                                ],
                                [
                                    -0.2386,
                                    -0.5
                                ],
                                [
                                    0.4432,
                                    0.0327
                                ],
                                [
                                    0.5,
                                    0.4533
                                ],
                                [
                                    0.2727,
                                    0.5
                                ],
                                [
                                    -0.2386,
                                    -0.0607
                                ],
                                [
                                    -0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Serratus Anterior',
                            'status' => 'weak',
                            'x' => 0.6429,
                            'y' => 0.2604,
                            'width' => 0.0469,
                            'height' => 0.0479,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.5
                                ],
                                [
                                    -0.1429,
                                    -0.3824
                                ],
                                [
                                    -0.5,
                                    -0.0882
                                ],
                                [
                                    -0.5,
                                    0.2647
                                ],
                                [
                                    -0.1905,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.4412
                                ],
                                [
                                    0.5,
                                    0.0882
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4196,
                            'y' => 0.1441,
                            'width' => 0.1585,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3451,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4554
                                ],
                                [
                                    0.5,
                                    0.3929
                                ],
                                [
                                    0.3099,
                                    0.5
                                ],
                                [
                                    0.0282,
                                    0.4375
                                ],
                                [
                                    -0.3239,
                                    0.3929
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    -0.3239,
                                    0.2321
                                ],
                                [
                                    0.0282,
                                    0.0179
                                ],
                                [
                                    0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.5647,
                            'y' => 0.1436,
                            'width' => 0.0536,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.1875,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4778
                                ],
                                [
                                    -0.3333,
                                    0.0556
                                ],
                                [
                                    0.1875,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.4556
                                ],
                                [
                                    0.0417,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Rhomboid Minor (if involved)',
                            'status' => 'tight',
                            'x' => 0.4554,
                            'y' => 0.2041,
                            'width' => 0.0759,
                            'height' => 0.0704,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4118,
                                    -0.46
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.14
                                ],
                                [
                                    0.3971,
                                    0.26
                                ],
                                [
                                    -0.1912,
                                    0.5
                                ],
                                [
                                    -0.4412,
                                    0.46
                                ],
                                [
                                    -0.5,
                                    -0.02
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.5831,
                            'y' => 0.1838,
                            'width' => 0.1507,
                            'height' => 0.0321,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.3246
                                ],
                                [
                                    0.1667,
                                    0.0614
                                ],
                                [
                                    0.4852,
                                    -0.2018
                                ],
                                [
                                    0.5,
                                    -0.4649
                                ],
                                [
                                    0.1296,
                                    -0.4649
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.5402,
                            'y' => 0.2379,
                            'width' => 0.0692,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4677,
                                    -0.4487
                                ],
                                [
                                    -0.5,
                                    -0.0128
                                ],
                                [
                                    -0.4677,
                                    0.5
                                ],
                                [
                                    -0.1452,
                                    0.2436
                                ],
                                [
                                    0.2581,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Rhomboid Major',
                            'status' => 'weak',
                            'x' => 0.5491,
                            'y' => 0.2041,
                            'width' => 0.0759,
                            'height' => 0.0704,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4118,
                                    -0.46
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.14
                                ],
                                [
                                    -0.3971,
                                    0.26
                                ],
                                [
                                    0.1912,
                                    0.5
                                ],
                                [
                                    0.4412,
                                    0.46
                                ],
                                [
                                    0.5,
                                    -0.02
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4353,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.05,
                                    -0.5
                                ],
                                [
                                    0.3,
                                    -0.4231
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.1,
                                    0.4231
                                ],
                                [
                                    -0.3,
                                    0.4231
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.4799,
                            'y' => 0.1394,
                            'width' => 0.0335,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.1154
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.3895,
                            'y' => 0.1745,
                            'width' => 0.0469,
                            'height' => 0.0225,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.4524,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.3772,
                            'y' => 0.2309,
                            'width' => 0.0268,
                            'height' => 0.045,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.375
                                ],
                                [
                                    -0.125,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5647,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.05,
                                    -0.5
                                ],
                                [
                                    -0.3,
                                    -0.4231
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.1,
                                    0.4231
                                ],
                                [
                                    0.3,
                                    0.4231
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.5201,
                            'y' => 0.1394,
                            'width' => 0.0335,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.1154
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.6105,
                            'y' => 0.1745,
                            'width' => 0.0469,
                            'height' => 0.0225,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.4524,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.6228,
                            'y' => 0.2309,
                            'width' => 0.0268,
                            'height' => 0.045,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    0.125,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Sternocleidomastoid (SCM)',
                            'status' => 'tight',
                            'x' => 0.474,
                            'y' => 0.1484,
                            'width' => 0.0503,
                            'height' => 0.0467,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.2143,
                                    -0.4518
                                ],
                                [
                                    0.3571,
                                    0.259
                                ],
                                [
                                    0.5,
                                    0.4639
                                ],
                                [
                                    0.1531,
                                    0.5
                                ],
                                [
                                    -0.1531,
                                    0.1988
                                ],
                                [
                                    -0.4184,
                                    -0.1627
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Scalenes',
                            'status' => 'tight',
                            'x' => 0.4483,
                            'y' => 0.1515,
                            'width' => 0.0359,
                            'height' => 0.0349,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    0.1286,
                                    -0.4677
                                ],
                                [
                                    0.5,
                                    0.0968
                                ],
                                [
                                    0.2143,
                                    0.5
                                ],
                                [
                                    -0.3571,
                                    0.4355
                                ],
                                [
                                    -0.5,
                                    0.0161
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor (if involved)',
                            'status' => 'tight',
                            'x' => 0.416,
                            'y' => 0.2092,
                            'width' => 0.0904,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4659,
                                    -0.4626
                                ],
                                [
                                    -0.2386,
                                    -0.5
                                ],
                                [
                                    0.4432,
                                    0.0327
                                ],
                                [
                                    0.5,
                                    0.4533
                                ],
                                [
                                    0.2727,
                                    0.5
                                ],
                                [
                                    -0.2386,
                                    -0.0607
                                ],
                                [
                                    -0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Serratus Anterior',
                            'status' => 'weak',
                            'x' => 0.6316,
                            'y' => 0.2604,
                            'width' => 0.0431,
                            'height' => 0.0479,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.5
                                ],
                                [
                                    -0.1429,
                                    -0.3824
                                ],
                                [
                                    -0.5,
                                    -0.0882
                                ],
                                [
                                    -0.5,
                                    0.2647
                                ],
                                [
                                    -0.1905,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.4412
                                ],
                                [
                                    0.5,
                                    0.0882
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4263,
                            'y' => 0.1441,
                            'width' => 0.1458,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3451,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4554
                                ],
                                [
                                    0.5,
                                    0.3929
                                ],
                                [
                                    0.3099,
                                    0.5
                                ],
                                [
                                    0.0282,
                                    0.4375
                                ],
                                [
                                    -0.3239,
                                    0.3929
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    -0.3239,
                                    0.2321
                                ],
                                [
                                    0.0282,
                                    0.0179
                                ],
                                [
                                    0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.5597,
                            'y' => 0.1436,
                            'width' => 0.0493,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.1875,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4778
                                ],
                                [
                                    -0.3333,
                                    0.0556
                                ],
                                [
                                    0.1875,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.4556
                                ],
                                [
                                    0.0417,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Rhomboid Minor (if involved)',
                            'status' => 'tight',
                            'x' => 0.4591,
                            'y' => 0.2041,
                            'width' => 0.0698,
                            'height' => 0.0704,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4118,
                                    -0.46
                                ],
                                [
                                    0.4706,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.14
                                ],
                                [
                                    0.3971,
                                    0.26
                                ],
                                [
                                    -0.1912,
                                    0.5
                                ],
                                [
                                    -0.4412,
                                    0.46
                                ],
                                [
                                    -0.5,
                                    -0.02
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.5767,
                            'y' => 0.1838,
                            'width' => 0.1386,
                            'height' => 0.0321,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.3246
                                ],
                                [
                                    0.1667,
                                    0.0614
                                ],
                                [
                                    0.4852,
                                    -0.2018
                                ],
                                [
                                    0.5,
                                    -0.4649
                                ],
                                [
                                    0.1296,
                                    -0.4649
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.5371,
                            'y' => 0.2379,
                            'width' => 0.0637,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4677,
                                    -0.4487
                                ],
                                [
                                    -0.5,
                                    -0.0128
                                ],
                                [
                                    -0.4677,
                                    0.5
                                ],
                                [
                                    -0.1452,
                                    0.2436
                                ],
                                [
                                    0.2581,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Rhomboid Major',
                            'status' => 'weak',
                            'x' => 0.5454,
                            'y' => 0.2041,
                            'width' => 0.0698,
                            'height' => 0.0704,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4118,
                                    -0.46
                                ],
                                [
                                    -0.4706,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.14
                                ],
                                [
                                    -0.3971,
                                    0.26
                                ],
                                [
                                    0.1912,
                                    0.5
                                ],
                                [
                                    0.4412,
                                    0.46
                                ],
                                [
                                    0.5,
                                    -0.02
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4353,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.05,
                                    -0.5
                                ],
                                [
                                    0.3,
                                    -0.4231
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.1,
                                    0.4231
                                ],
                                [
                                    -0.3,
                                    0.4231
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.4799,
                            'y' => 0.1394,
                            'width' => 0.0335,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.1154
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.3895,
                            'y' => 0.1745,
                            'width' => 0.0469,
                            'height' => 0.0225,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.4524,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.3772,
                            'y' => 0.2309,
                            'width' => 0.0268,
                            'height' => 0.045,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.375
                                ],
                                [
                                    -0.125,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5647,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.05,
                                    -0.5
                                ],
                                [
                                    -0.3,
                                    -0.4231
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.1,
                                    0.4231
                                ],
                                [
                                    0.3,
                                    0.4231
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.5201,
                            'y' => 0.1394,
                            'width' => 0.0335,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.1154
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.6105,
                            'y' => 0.1745,
                            'width' => 0.0469,
                            'height' => 0.0225,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.4524,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.6228,
                            'y' => 0.2309,
                            'width' => 0.0268,
                            'height' => 0.045,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    0.125,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ]
                ]
            ]
        ],
        'genu_valgum' => [
            'name' => 'Knock Knees (Genu Valgum)',
            'description' => 'The knees angle inwards and touch or nearly touch while the ankles stay apart.',
            'effects' => 'Knees collapse inwards when standing or squatting
Knee or hip discomfort with activity
Feet may roll inwards
Weak hip muscles that control the knee',
            'detection' => [
                'sections' => [],
                'classifications' => []
            ],
            'order_index' => 9,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Adductors (Longus / Brevis / Magnus)',
                            'status' => 'tight',
                            'x' => 0.4436,
                            'y' => 0.5785,
                            'width' => 0.0837,
                            'height' => 0.0816,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4793
                                ],
                                [
                                    0.46,
                                    -0.1897
                                ],
                                [
                                    0.3,
                                    0.2241
                                ],
                                [
                                    0.14,
                                    0.5
                                ],
                                [
                                    -0.1,
                                    0.4655
                                ],
                                [
                                    -0.34,
                                    0.0862
                                ],
                                [
                                    -0.4733,
                                    -0.2586
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Adductors (Longus / Brevis / Magnus)',
                            'status' => 'tight',
                            'x' => 0.5608,
                            'y' => 0.5785,
                            'width' => 0.0837,
                            'height' => 0.0816,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4793
                                ],
                                [
                                    -0.46,
                                    -0.1897
                                ],
                                [
                                    -0.3,
                                    0.2241
                                ],
                                [
                                    -0.14,
                                    0.5
                                ],
                                [
                                    0.1,
                                    0.4655
                                ],
                                [
                                    0.34,
                                    0.0862
                                ],
                                [
                                    0.4733,
                                    -0.2586
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.3432,
                            'y' => 0.4744,
                            'width' => 0.0413,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3108,
                                    -0.5
                                ],
                                [
                                    0.2838,
                                    -0.4744
                                ],
                                [
                                    0.5,
                                    -0.2179
                                ],
                                [
                                    0.3378,
                                    0.141
                                ],
                                [
                                    0.0676,
                                    0.5
                                ],
                                [
                                    -0.2838,
                                    0.5
                                ],
                                [
                                    -0.473,
                                    0.0897
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.6613,
                            'y' => 0.4744,
                            'width' => 0.0413,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3108,
                                    -0.5
                                ],
                                [
                                    -0.2838,
                                    -0.4744
                                ],
                                [
                                    -0.5,
                                    -0.2179
                                ],
                                [
                                    -0.3378,
                                    0.141
                                ],
                                [
                                    -0.0676,
                                    0.5
                                ],
                                [
                                    0.2838,
                                    0.5
                                ],
                                [
                                    0.473,
                                    0.0897
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Vastus Medialis Oblique (VMO)',
                            'status' => 'weak',
                            'x' => 0.4537,
                            'y' => 0.6354,
                            'width' => 0.048,
                            'height' => 0.049,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0349,
                                    -0.5
                                ],
                                [
                                    0.4302,
                                    -0.3276
                                ],
                                [
                                    0.5,
                                    0.1322
                                ],
                                [
                                    0.1977,
                                    0.5
                                ],
                                [
                                    -0.2674,
                                    0.477
                                ],
                                [
                                    -0.5,
                                    0.1322
                                ],
                                [
                                    -0.3372,
                                    -0.2701
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Vastus Medialis Oblique (VMO)',
                            'status' => 'weak',
                            'x' => 0.5508,
                            'y' => 0.6354,
                            'width' => 0.048,
                            'height' => 0.049,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0349,
                                    -0.5
                                ],
                                [
                                    -0.4302,
                                    -0.3276
                                ],
                                [
                                    -0.5,
                                    0.1322
                                ],
                                [
                                    -0.1977,
                                    0.5
                                ],
                                [
                                    0.2674,
                                    0.477
                                ],
                                [
                                    0.5,
                                    0.1322
                                ],
                                [
                                    0.3372,
                                    -0.2701
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Gluteus Medius',
                            'status' => 'weak',
                            'x' => 0.3884,
                            'y' => 0.4257,
                            'width' => 0.1161,
                            'height' => 0.0383,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4615,
                                    -0.2353
                                ],
                                [
                                    -0.0769,
                                    -0.5
                                ],
                                [
                                    0.4519,
                                    -0.4118
                                ],
                                [
                                    0.5,
                                    0.0588
                                ],
                                [
                                    0.0192,
                                    0.2794
                                ],
                                [
                                    -0.3654,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.2059
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Gluteus Medius',
                            'status' => 'weak',
                            'x' => 0.6161,
                            'y' => 0.4257,
                            'width' => 0.1161,
                            'height' => 0.0383,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4615,
                                    -0.2353
                                ],
                                [
                                    0.0769,
                                    -0.5
                                ],
                                [
                                    -0.4519,
                                    -0.4118
                                ],
                                [
                                    -0.5,
                                    0.0588
                                ],
                                [
                                    -0.0192,
                                    0.2794
                                ],
                                [
                                    0.3654,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.2059
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Minimus',
                            'status' => 'weak',
                            'x' => 0.3795,
                            'y' => 0.4274,
                            'width' => 0.0781,
                            'height' => 0.0236,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.2143
                                ],
                                [
                                    0.0714,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.3333
                                ],
                                [
                                    0.1429,
                                    0.2619
                                ],
                                [
                                    -0.4,
                                    0.5
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Minimus',
                            'status' => 'weak',
                            'x' => 0.625,
                            'y' => 0.4274,
                            'width' => 0.0781,
                            'height' => 0.0236,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.2143
                                ],
                                [
                                    -0.0714,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.3333
                                ],
                                [
                                    -0.1429,
                                    0.2619
                                ],
                                [
                                    0.4,
                                    0.5
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Deep Hip External Rotators',
                            'status' => 'weak',
                            'x' => 0.4353,
                            'y' => 0.4786,
                            'width' => 0.1116,
                            'height' => 0.0169,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.3333
                                ],
                                [
                                    0.1,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.48,
                                    0.5
                                ],
                                [
                                    0.05,
                                    0.4
                                ],
                                [
                                    -0.45,
                                    0.3333
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Deep Hip External Rotators',
                            'status' => 'weak',
                            'x' => 0.5692,
                            'y' => 0.4786,
                            'width' => 0.1116,
                            'height' => 0.0169,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.3333
                                ],
                                [
                                    -0.1,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.48,
                                    0.5
                                ],
                                [
                                    -0.05,
                                    0.4
                                ],
                                [
                                    0.45,
                                    0.3333
                                ]
                            ],
                            'order_index' => 7
                        ],
                        [
                            'muscle' => 'Biceps Femoris (may be involved)',
                            'status' => 'tight',
                            'x' => 0.3828,
                            'y' => 0.6033,
                            'width' => 0.1272,
                            'height' => 0.1278,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4649,
                                    -0.4912
                                ],
                                [
                                    0.4561,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.0947
                                ],
                                [
                                    0.4123,
                                    0.3018
                                ],
                                [
                                    0.2368,
                                    0.5
                                ],
                                [
                                    -0.114,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3458
                                ],
                                [
                                    -0.4474,
                                    0.0374
                                ],
                                [
                                    -0.5,
                                    -0.315
                                ]
                            ],
                            'order_index' => 8
                        ],
                        [
                            'muscle' => 'Biceps Femoris (may be involved)',
                            'status' => 'tight',
                            'x' => 0.6217,
                            'y' => 0.6033,
                            'width' => 0.1272,
                            'height' => 0.1278,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4649,
                                    -0.4912
                                ],
                                [
                                    -0.4561,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.0947
                                ],
                                [
                                    -0.4123,
                                    0.3018
                                ],
                                [
                                    -0.2368,
                                    0.5
                                ],
                                [
                                    0.114,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3458
                                ],
                                [
                                    0.4474,
                                    0.0374
                                ],
                                [
                                    0.5,
                                    -0.315
                                ]
                            ],
                            'order_index' => 9
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Gluteus Medius',
                            'status' => 'weak',
                            'x' => 0.4353,
                            'y' => 0.404,
                            'width' => 0.067,
                            'height' => 0.031,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.4091
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.4091
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.4448,
                            'width' => 0.0446,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.25,
                                    -0.4643
                                ],
                                [
                                    0.5,
                                    0
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    -0.45,
                                    0
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Adductor Magnus',
                            'status' => 'tight',
                            'x' => 0.4727,
                            'y' => 0.58,
                            'width' => 0.0413,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4189,
                                    -0.5
                                ],
                                [
                                    0.4459,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0714
                                ],
                                [
                                    0.1757,
                                    0.5
                                ],
                                [
                                    -0.3108,
                                    0.4286
                                ],
                                [
                                    -0.5,
                                    -0.0714
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Vastus Medialis Oblique (VMO)',
                            'status' => 'weak',
                            'x' => 0.5357,
                            'y' => 0.6419,
                            'width' => 0.0335,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.1429
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.2857
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Gluteus Medius',
                            'status' => 'weak',
                            'x' => 0.5647,
                            'y' => 0.404,
                            'width' => 0.067,
                            'height' => 0.031,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.4091
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.4091
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.4978,
                            'y' => 0.4448,
                            'width' => 0.0446,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.25,
                                    -0.4643
                                ],
                                [
                                    -0.5,
                                    0
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    0.45,
                                    0
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Adductor Magnus',
                            'status' => 'tight',
                            'x' => 0.5273,
                            'y' => 0.58,
                            'width' => 0.0413,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4189,
                                    -0.5
                                ],
                                [
                                    -0.4459,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0714
                                ],
                                [
                                    -0.1757,
                                    0.5
                                ],
                                [
                                    0.3108,
                                    0.4286
                                ],
                                [
                                    0.5,
                                    -0.0714
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Vastus Medialis Oblique (VMO)',
                            'status' => 'weak',
                            'x' => 0.4643,
                            'y' => 0.6419,
                            'width' => 0.0335,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.1429
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.2857
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Adductors (Longus / Brevis / Magnus)',
                            'status' => 'tight',
                            'x' => 0.4436,
                            'y' => 0.5785,
                            'width' => 0.0837,
                            'height' => 0.0816,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4793
                                ],
                                [
                                    0.46,
                                    -0.1897
                                ],
                                [
                                    0.3,
                                    0.2241
                                ],
                                [
                                    0.14,
                                    0.5
                                ],
                                [
                                    -0.1,
                                    0.4655
                                ],
                                [
                                    -0.34,
                                    0.0862
                                ],
                                [
                                    -0.4733,
                                    -0.2586
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Adductors (Longus / Brevis / Magnus)',
                            'status' => 'tight',
                            'x' => 0.5608,
                            'y' => 0.5785,
                            'width' => 0.0837,
                            'height' => 0.0816,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4793
                                ],
                                [
                                    -0.46,
                                    -0.1897
                                ],
                                [
                                    -0.3,
                                    0.2241
                                ],
                                [
                                    -0.14,
                                    0.5
                                ],
                                [
                                    0.1,
                                    0.4655
                                ],
                                [
                                    0.34,
                                    0.0862
                                ],
                                [
                                    0.4733,
                                    -0.2586
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.3432,
                            'y' => 0.4744,
                            'width' => 0.0413,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3108,
                                    -0.5
                                ],
                                [
                                    0.2838,
                                    -0.4744
                                ],
                                [
                                    0.5,
                                    -0.2179
                                ],
                                [
                                    0.3378,
                                    0.141
                                ],
                                [
                                    0.0676,
                                    0.5
                                ],
                                [
                                    -0.2838,
                                    0.5
                                ],
                                [
                                    -0.473,
                                    0.0897
                                ],
                                [
                                    -0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.6613,
                            'y' => 0.4744,
                            'width' => 0.0413,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3108,
                                    -0.5
                                ],
                                [
                                    -0.2838,
                                    -0.4744
                                ],
                                [
                                    -0.5,
                                    -0.2179
                                ],
                                [
                                    -0.3378,
                                    0.141
                                ],
                                [
                                    -0.0676,
                                    0.5
                                ],
                                [
                                    0.2838,
                                    0.5
                                ],
                                [
                                    0.473,
                                    0.0897
                                ],
                                [
                                    0.5,
                                    -0.2692
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Vastus Medialis Oblique (VMO)',
                            'status' => 'weak',
                            'x' => 0.4537,
                            'y' => 0.6354,
                            'width' => 0.048,
                            'height' => 0.049,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.0349,
                                    -0.5
                                ],
                                [
                                    0.4302,
                                    -0.3276
                                ],
                                [
                                    0.5,
                                    0.1322
                                ],
                                [
                                    0.1977,
                                    0.5
                                ],
                                [
                                    -0.2674,
                                    0.477
                                ],
                                [
                                    -0.5,
                                    0.1322
                                ],
                                [
                                    -0.3372,
                                    -0.2701
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Vastus Medialis Oblique (VMO)',
                            'status' => 'weak',
                            'x' => 0.5508,
                            'y' => 0.6354,
                            'width' => 0.048,
                            'height' => 0.049,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.0349,
                                    -0.5
                                ],
                                [
                                    -0.4302,
                                    -0.3276
                                ],
                                [
                                    -0.5,
                                    0.1322
                                ],
                                [
                                    -0.1977,
                                    0.5
                                ],
                                [
                                    0.2674,
                                    0.477
                                ],
                                [
                                    0.5,
                                    0.1322
                                ],
                                [
                                    0.3372,
                                    -0.2701
                                ]
                            ],
                            'order_index' => 5
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Gluteus Medius',
                            'status' => 'weak',
                            'x' => 0.3884,
                            'y' => 0.4257,
                            'width' => 0.1161,
                            'height' => 0.0383,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4615,
                                    -0.2353
                                ],
                                [
                                    -0.0769,
                                    -0.5
                                ],
                                [
                                    0.4519,
                                    -0.4118
                                ],
                                [
                                    0.5,
                                    0.0588
                                ],
                                [
                                    0.0192,
                                    0.2794
                                ],
                                [
                                    -0.3654,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.2059
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Gluteus Medius',
                            'status' => 'weak',
                            'x' => 0.6161,
                            'y' => 0.4257,
                            'width' => 0.1161,
                            'height' => 0.0383,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4615,
                                    -0.2353
                                ],
                                [
                                    0.0769,
                                    -0.5
                                ],
                                [
                                    -0.4519,
                                    -0.4118
                                ],
                                [
                                    -0.5,
                                    0.0588
                                ],
                                [
                                    -0.0192,
                                    0.2794
                                ],
                                [
                                    0.3654,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.2059
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Gluteus Minimus',
                            'status' => 'weak',
                            'x' => 0.3795,
                            'y' => 0.4274,
                            'width' => 0.0781,
                            'height' => 0.0236,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.2143
                                ],
                                [
                                    0.0714,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.3333
                                ],
                                [
                                    0.1429,
                                    0.2619
                                ],
                                [
                                    -0.4,
                                    0.5
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Gluteus Minimus',
                            'status' => 'weak',
                            'x' => 0.625,
                            'y' => 0.4274,
                            'width' => 0.0781,
                            'height' => 0.0236,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.2143
                                ],
                                [
                                    -0.0714,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.3333
                                ],
                                [
                                    -0.1429,
                                    0.2619
                                ],
                                [
                                    0.4,
                                    0.5
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Deep Hip External Rotators',
                            'status' => 'weak',
                            'x' => 0.4353,
                            'y' => 0.4786,
                            'width' => 0.1116,
                            'height' => 0.0169,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.3333
                                ],
                                [
                                    0.1,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.48,
                                    0.5
                                ],
                                [
                                    0.05,
                                    0.4
                                ],
                                [
                                    -0.45,
                                    0.3333
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Deep Hip External Rotators',
                            'status' => 'weak',
                            'x' => 0.5692,
                            'y' => 0.4786,
                            'width' => 0.1116,
                            'height' => 0.0169,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.3333
                                ],
                                [
                                    -0.1,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.48,
                                    0.5
                                ],
                                [
                                    -0.05,
                                    0.4
                                ],
                                [
                                    0.45,
                                    0.3333
                                ]
                            ],
                            'order_index' => 7
                        ],
                        [
                            'muscle' => 'Biceps Femoris (may be involved)',
                            'status' => 'tight',
                            'x' => 0.3828,
                            'y' => 0.6033,
                            'width' => 0.1272,
                            'height' => 0.1278,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4649,
                                    -0.4912
                                ],
                                [
                                    0.4561,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.0947
                                ],
                                [
                                    0.4123,
                                    0.3018
                                ],
                                [
                                    0.2368,
                                    0.5
                                ],
                                [
                                    -0.114,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3458
                                ],
                                [
                                    -0.4474,
                                    0.0374
                                ],
                                [
                                    -0.5,
                                    -0.315
                                ]
                            ],
                            'order_index' => 8
                        ],
                        [
                            'muscle' => 'Biceps Femoris (may be involved)',
                            'status' => 'tight',
                            'x' => 0.6217,
                            'y' => 0.6033,
                            'width' => 0.1272,
                            'height' => 0.1278,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4649,
                                    -0.4912
                                ],
                                [
                                    -0.4561,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.0947
                                ],
                                [
                                    -0.4123,
                                    0.3018
                                ],
                                [
                                    -0.2368,
                                    0.5
                                ],
                                [
                                    0.114,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3458
                                ],
                                [
                                    0.4474,
                                    0.0374
                                ],
                                [
                                    0.5,
                                    -0.315
                                ]
                            ],
                            'order_index' => 9
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Gluteus Medius',
                            'status' => 'weak',
                            'x' => 0.4353,
                            'y' => 0.404,
                            'width' => 0.067,
                            'height' => 0.031,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.4091
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.4091
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.5022,
                            'y' => 0.4448,
                            'width' => 0.0446,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.25,
                                    -0.4643
                                ],
                                [
                                    0.5,
                                    0
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    -0.45,
                                    0
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Adductor Magnus',
                            'status' => 'tight',
                            'x' => 0.4727,
                            'y' => 0.58,
                            'width' => 0.0413,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4189,
                                    -0.5
                                ],
                                [
                                    0.4459,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0714
                                ],
                                [
                                    0.1757,
                                    0.5
                                ],
                                [
                                    -0.3108,
                                    0.4286
                                ],
                                [
                                    -0.5,
                                    -0.0714
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Vastus Medialis Oblique (VMO)',
                            'status' => 'weak',
                            'x' => 0.5357,
                            'y' => 0.6419,
                            'width' => 0.0335,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.1429
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.2857
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Gluteus Medius',
                            'status' => 'weak',
                            'x' => 0.5647,
                            'y' => 0.404,
                            'width' => 0.067,
                            'height' => 0.031,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.4091
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.4091
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Tensor Fasciae Latae (TFL)',
                            'status' => 'tight',
                            'x' => 0.4978,
                            'y' => 0.4448,
                            'width' => 0.0446,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.25,
                                    -0.4643
                                ],
                                [
                                    -0.5,
                                    0
                                ],
                                [
                                    -0.25,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.5
                                ],
                                [
                                    0.45,
                                    0
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Adductor Magnus',
                            'status' => 'tight',
                            'x' => 0.5273,
                            'y' => 0.58,
                            'width' => 0.0413,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4189,
                                    -0.5
                                ],
                                [
                                    -0.4459,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0714
                                ],
                                [
                                    -0.1757,
                                    0.5
                                ],
                                [
                                    0.3108,
                                    0.4286
                                ],
                                [
                                    0.5,
                                    -0.0714
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Vastus Medialis Oblique (VMO)',
                            'status' => 'weak',
                            'x' => 0.4643,
                            'y' => 0.6419,
                            'width' => 0.0335,
                            'height' => 0.0394,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.1429
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.2857
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ]
                ]
            ]
        ],
        'forward_head' => [
            'name' => 'Forward Head Posture',
            'description' => 'The head sits in front of the shoulders instead of above them, so the neck and upper back work harder to hold it up.',
            'effects' => 'Neck and upper-shoulder tension
Headaches after long screen or study time
Chin pokes forward when sitting
Rounded upper back over time',
            'detection' => [
                'sections' => [],
                'classifications' => [
                    'FORWARD_HEAD'
                ]
            ],
            'order_index' => 10,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Sternocleidomastoid (SCM)',
                            'status' => 'tight',
                            'x' => 0.4715,
                            'y' => 0.1484,
                            'width' => 0.0547,
                            'height' => 0.0467,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.2143,
                                    -0.4518
                                ],
                                [
                                    0.3571,
                                    0.259
                                ],
                                [
                                    0.5,
                                    0.4639
                                ],
                                [
                                    0.1531,
                                    0.5
                                ],
                                [
                                    -0.1531,
                                    0.1988
                                ],
                                [
                                    -0.4184,
                                    -0.1627
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Sternocleidomastoid (SCM)',
                            'status' => 'tight',
                            'x' => 0.5329,
                            'y' => 0.1484,
                            'width' => 0.0547,
                            'height' => 0.0467,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.2143,
                                    -0.4518
                                ],
                                [
                                    -0.3571,
                                    0.259
                                ],
                                [
                                    -0.5,
                                    0.4639
                                ],
                                [
                                    -0.1531,
                                    0.5
                                ],
                                [
                                    0.1531,
                                    0.1988
                                ],
                                [
                                    0.4184,
                                    -0.1627
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.4085,
                            'y' => 0.2092,
                            'width' => 0.0982,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4659,
                                    -0.4626
                                ],
                                [
                                    -0.2386,
                                    -0.5
                                ],
                                [
                                    0.4432,
                                    0.0327
                                ],
                                [
                                    0.5,
                                    0.4533
                                ],
                                [
                                    0.2727,
                                    0.5
                                ],
                                [
                                    -0.2386,
                                    -0.0607
                                ],
                                [
                                    -0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.596,
                            'y' => 0.2092,
                            'width' => 0.0982,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4659,
                                    -0.4626
                                ],
                                [
                                    0.2386,
                                    -0.5
                                ],
                                [
                                    -0.4432,
                                    0.0327
                                ],
                                [
                                    -0.5,
                                    0.4533
                                ],
                                [
                                    -0.2727,
                                    0.5
                                ],
                                [
                                    0.2386,
                                    -0.0607
                                ],
                                [
                                    0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4196,
                            'y' => 0.1441,
                            'width' => 0.1585,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3451,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4554
                                ],
                                [
                                    0.5,
                                    0.3929
                                ],
                                [
                                    0.3099,
                                    0.5
                                ],
                                [
                                    0.0282,
                                    0.4375
                                ],
                                [
                                    -0.3239,
                                    0.3929
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    -0.3239,
                                    0.2321
                                ],
                                [
                                    0.0282,
                                    0.0179
                                ],
                                [
                                    0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5848,
                            'y' => 0.1441,
                            'width' => 0.1585,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3451,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4554
                                ],
                                [
                                    -0.5,
                                    0.3929
                                ],
                                [
                                    -0.3099,
                                    0.5
                                ],
                                [
                                    -0.0282,
                                    0.4375
                                ],
                                [
                                    0.3239,
                                    0.3929
                                ],
                                [
                                    0.5,
                                    0.375
                                ],
                                [
                                    0.3239,
                                    0.2321
                                ],
                                [
                                    -0.0282,
                                    0.0179
                                ],
                                [
                                    -0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.4397,
                            'y' => 0.1436,
                            'width' => 0.0536,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.1875,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4778
                                ],
                                [
                                    0.3333,
                                    0.0556
                                ],
                                [
                                    -0.1875,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.4556
                                ],
                                [
                                    -0.0417,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.5647,
                            'y' => 0.1436,
                            'width' => 0.0536,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.1875,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4778
                                ],
                                [
                                    -0.3333,
                                    0.0556
                                ],
                                [
                                    0.1875,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.4556
                                ],
                                [
                                    0.0417,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.4213,
                            'y' => 0.1838,
                            'width' => 0.1507,
                            'height' => 0.0321,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.3246
                                ],
                                [
                                    -0.1667,
                                    0.0614
                                ],
                                [
                                    -0.4852,
                                    -0.2018
                                ],
                                [
                                    -0.5,
                                    -0.4649
                                ],
                                [
                                    -0.1296,
                                    -0.4649
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.5831,
                            'y' => 0.1838,
                            'width' => 0.1507,
                            'height' => 0.0321,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.3246
                                ],
                                [
                                    0.1667,
                                    0.0614
                                ],
                                [
                                    0.4852,
                                    -0.2018
                                ],
                                [
                                    0.5,
                                    -0.4649
                                ],
                                [
                                    0.1296,
                                    -0.4649
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.4643,
                            'y' => 0.2379,
                            'width' => 0.0692,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4677,
                                    -0.4487
                                ],
                                [
                                    0.5,
                                    -0.0128
                                ],
                                [
                                    0.4677,
                                    0.5
                                ],
                                [
                                    0.1452,
                                    0.2436
                                ],
                                [
                                    -0.2581,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.5402,
                            'y' => 0.2379,
                            'width' => 0.0692,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4677,
                                    -0.4487
                                ],
                                [
                                    -0.5,
                                    -0.0128
                                ],
                                [
                                    -0.4677,
                                    0.5
                                ],
                                [
                                    -0.1452,
                                    0.2436
                                ],
                                [
                                    0.2581,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4353,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.05,
                                    -0.5
                                ],
                                [
                                    0.3,
                                    -0.4231
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.1,
                                    0.4231
                                ],
                                [
                                    -0.3,
                                    0.4231
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.4799,
                            'y' => 0.1394,
                            'width' => 0.0335,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.1154
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.5737,
                            'y' => 0.2111,
                            'width' => 0.1027,
                            'height' => 0.0845,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4783,
                                    -0.5
                                ],
                                [
                                    -0.1522,
                                    -0.4
                                ],
                                [
                                    0.1739,
                                    -0.2
                                ],
                                [
                                    0.4457,
                                    0.0667
                                ],
                                [
                                    0.5,
                                    0.3
                                ],
                                [
                                    0.337,
                                    0.4667
                                ],
                                [
                                    -0.0435,
                                    0.5
                                ],
                                [
                                    -0.4239,
                                    0.3667
                                ],
                                [
                                    -0.4783,
                                    0.1
                                ],
                                [
                                    -0.5,
                                    -0.2333
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.3772,
                            'y' => 0.2309,
                            'width' => 0.0268,
                            'height' => 0.045,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.375
                                ],
                                [
                                    -0.125,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5647,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.05,
                                    -0.5
                                ],
                                [
                                    -0.3,
                                    -0.4231
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.1,
                                    0.4231
                                ],
                                [
                                    0.3,
                                    0.4231
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.5201,
                            'y' => 0.1394,
                            'width' => 0.0335,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.1154
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.4263,
                            'y' => 0.2111,
                            'width' => 0.1027,
                            'height' => 0.0845,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4783,
                                    -0.5
                                ],
                                [
                                    0.1522,
                                    -0.4
                                ],
                                [
                                    -0.1739,
                                    -0.2
                                ],
                                [
                                    -0.4457,
                                    0.0667
                                ],
                                [
                                    -0.5,
                                    0.3
                                ],
                                [
                                    -0.337,
                                    0.4667
                                ],
                                [
                                    0.0435,
                                    0.5
                                ],
                                [
                                    0.4239,
                                    0.3667
                                ],
                                [
                                    0.4783,
                                    0.1
                                ],
                                [
                                    0.5,
                                    -0.2333
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.6228,
                            'y' => 0.2309,
                            'width' => 0.0268,
                            'height' => 0.045,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    0.125,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Sternocleidomastoid (SCM)',
                            'status' => 'tight',
                            'x' => 0.474,
                            'y' => 0.1484,
                            'width' => 0.0503,
                            'height' => 0.0467,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.2143,
                                    -0.4518
                                ],
                                [
                                    0.3571,
                                    0.259
                                ],
                                [
                                    0.5,
                                    0.4639
                                ],
                                [
                                    0.1531,
                                    0.5
                                ],
                                [
                                    -0.1531,
                                    0.1988
                                ],
                                [
                                    -0.4184,
                                    -0.1627
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Sternocleidomastoid (SCM)',
                            'status' => 'tight',
                            'x' => 0.5305,
                            'y' => 0.1484,
                            'width' => 0.0503,
                            'height' => 0.0467,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.2143,
                                    -0.4518
                                ],
                                [
                                    -0.3571,
                                    0.259
                                ],
                                [
                                    -0.5,
                                    0.4639
                                ],
                                [
                                    -0.1531,
                                    0.5
                                ],
                                [
                                    0.1531,
                                    0.1988
                                ],
                                [
                                    0.4184,
                                    -0.1627
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.416,
                            'y' => 0.2092,
                            'width' => 0.0904,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4659,
                                    -0.4626
                                ],
                                [
                                    -0.2386,
                                    -0.5
                                ],
                                [
                                    0.4432,
                                    0.0327
                                ],
                                [
                                    0.5,
                                    0.4533
                                ],
                                [
                                    0.2727,
                                    0.5
                                ],
                                [
                                    -0.2386,
                                    -0.0607
                                ],
                                [
                                    -0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.5885,
                            'y' => 0.2092,
                            'width' => 0.0904,
                            'height' => 0.0602,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4659,
                                    -0.4626
                                ],
                                [
                                    0.2386,
                                    -0.5
                                ],
                                [
                                    -0.4432,
                                    0.0327
                                ],
                                [
                                    -0.5,
                                    0.4533
                                ],
                                [
                                    -0.2727,
                                    0.5
                                ],
                                [
                                    0.2386,
                                    -0.0607
                                ],
                                [
                                    0.5,
                                    -0.2944
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4263,
                            'y' => 0.1441,
                            'width' => 0.1458,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3451,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4554
                                ],
                                [
                                    0.5,
                                    0.3929
                                ],
                                [
                                    0.3099,
                                    0.5
                                ],
                                [
                                    0.0282,
                                    0.4375
                                ],
                                [
                                    -0.3239,
                                    0.3929
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    -0.3239,
                                    0.2321
                                ],
                                [
                                    0.0282,
                                    0.0179
                                ],
                                [
                                    0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5782,
                            'y' => 0.1441,
                            'width' => 0.1458,
                            'height' => 0.0631,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3451,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4554
                                ],
                                [
                                    -0.5,
                                    0.3929
                                ],
                                [
                                    -0.3099,
                                    0.5
                                ],
                                [
                                    -0.0282,
                                    0.4375
                                ],
                                [
                                    0.3239,
                                    0.3929
                                ],
                                [
                                    0.5,
                                    0.375
                                ],
                                [
                                    0.3239,
                                    0.2321
                                ],
                                [
                                    -0.0282,
                                    0.0179
                                ],
                                [
                                    -0.169,
                                    -0.1875
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.4447,
                            'y' => 0.1436,
                            'width' => 0.0493,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.1875,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4778
                                ],
                                [
                                    0.3333,
                                    0.0556
                                ],
                                [
                                    -0.1875,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.4556
                                ],
                                [
                                    -0.0417,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.5597,
                            'y' => 0.1436,
                            'width' => 0.0493,
                            'height' => 0.0507,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.1875,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4778
                                ],
                                [
                                    -0.3333,
                                    0.0556
                                ],
                                [
                                    0.1875,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.4556
                                ],
                                [
                                    0.0417,
                                    -0.0556
                                ]
                            ],
                            'order_index' => 3
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.4278,
                            'y' => 0.1838,
                            'width' => 0.1386,
                            'height' => 0.0321,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.3246
                                ],
                                [
                                    -0.1667,
                                    0.0614
                                ],
                                [
                                    -0.4852,
                                    -0.2018
                                ],
                                [
                                    -0.5,
                                    -0.4649
                                ],
                                [
                                    -0.1296,
                                    -0.4649
                                ]
                            ],
                            'order_index' => 4
                        ],
                        [
                            'muscle' => 'Middle Trapezius',
                            'status' => 'weak',
                            'x' => 0.5767,
                            'y' => 0.1838,
                            'width' => 0.1386,
                            'height' => 0.0321,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.3246
                                ],
                                [
                                    0.1667,
                                    0.0614
                                ],
                                [
                                    0.4852,
                                    -0.2018
                                ],
                                [
                                    0.5,
                                    -0.4649
                                ],
                                [
                                    0.1296,
                                    -0.4649
                                ]
                            ],
                            'order_index' => 5
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.4673,
                            'y' => 0.2379,
                            'width' => 0.0637,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4677,
                                    -0.4487
                                ],
                                [
                                    0.5,
                                    -0.0128
                                ],
                                [
                                    0.4677,
                                    0.5
                                ],
                                [
                                    0.1452,
                                    0.2436
                                ],
                                [
                                    -0.2581,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 6
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.5371,
                            'y' => 0.2379,
                            'width' => 0.0637,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4677,
                                    -0.4487
                                ],
                                [
                                    -0.5,
                                    -0.0128
                                ],
                                [
                                    -0.4677,
                                    0.5
                                ],
                                [
                                    -0.1452,
                                    0.2436
                                ],
                                [
                                    0.2581,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 7
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.4353,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.05,
                                    -0.5
                                ],
                                [
                                    0.3,
                                    -0.4231
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.1,
                                    0.4231
                                ],
                                [
                                    -0.3,
                                    0.4231
                                ],
                                [
                                    -0.5,
                                    0.5
                                ],
                                [
                                    -0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.4799,
                            'y' => 0.1394,
                            'width' => 0.0335,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.1154
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.5737,
                            'y' => 0.2111,
                            'width' => 0.1027,
                            'height' => 0.0845,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4783,
                                    -0.5
                                ],
                                [
                                    -0.1522,
                                    -0.4
                                ],
                                [
                                    0.1739,
                                    -0.2
                                ],
                                [
                                    0.4457,
                                    0.0667
                                ],
                                [
                                    0.5,
                                    0.3
                                ],
                                [
                                    0.337,
                                    0.4667
                                ],
                                [
                                    -0.0435,
                                    0.5
                                ],
                                [
                                    -0.4239,
                                    0.3667
                                ],
                                [
                                    -0.4783,
                                    0.1
                                ],
                                [
                                    -0.5,
                                    -0.2333
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.3772,
                            'y' => 0.2309,
                            'width' => 0.0268,
                            'height' => 0.045,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.375
                                ],
                                [
                                    -0.125,
                                    0.5
                                ],
                                [
                                    -0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Upper Trapezius',
                            'status' => 'tight',
                            'x' => 0.5647,
                            'y' => 0.145,
                            'width' => 0.1116,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.05,
                                    -0.5
                                ],
                                [
                                    -0.3,
                                    -0.4231
                                ],
                                [
                                    -0.5,
                                    0.3462
                                ],
                                [
                                    -0.1,
                                    0.4231
                                ],
                                [
                                    0.3,
                                    0.4231
                                ],
                                [
                                    0.5,
                                    0.5
                                ],
                                [
                                    0.25,
                                    0.0692
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Levator Scapulae',
                            'status' => 'tight',
                            'x' => 0.5201,
                            'y' => 0.1394,
                            'width' => 0.0335,
                            'height' => 0.0366,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3333,
                                    -0.5
                                ],
                                [
                                    -0.3333,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.1154
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.1154
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Pectoralis Minor',
                            'status' => 'tight',
                            'x' => 0.4263,
                            'y' => 0.2111,
                            'width' => 0.1027,
                            'height' => 0.0845,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4783,
                                    -0.5
                                ],
                                [
                                    0.1522,
                                    -0.4
                                ],
                                [
                                    -0.1739,
                                    -0.2
                                ],
                                [
                                    -0.4457,
                                    0.0667
                                ],
                                [
                                    -0.5,
                                    0.3
                                ],
                                [
                                    -0.337,
                                    0.4667
                                ],
                                [
                                    0.0435,
                                    0.5
                                ],
                                [
                                    0.4239,
                                    0.3667
                                ],
                                [
                                    0.4783,
                                    0.1
                                ],
                                [
                                    0.5,
                                    -0.2333
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Lower Trapezius',
                            'status' => 'weak',
                            'x' => 0.6228,
                            'y' => 0.2309,
                            'width' => 0.0268,
                            'height' => 0.045,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.375
                                ],
                                [
                                    0.125,
                                    0.5
                                ],
                                [
                                    0.5,
                                    0
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ]
                ]
            ]
        ],
        'flexed_knee' => [
            'name' => 'Flexed Knee Posture',
            'description' => 'The knees stay slightly bent when standing, so the thigh and hip muscles never fully relax.',
            'effects' => 'Knees do not straighten fully when standing
Tired thighs after standing or walking
Hips and trunk lean slightly forward
Knee discomfort with stairs or squats',
            'detection' => [
                'sections' => [],
                'classifications' => [
                    'FLEXED_KNEE'
                ]
            ],
            'order_index' => 11,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'weak',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'weak',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'weak',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4632,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    -0.0833,
                                    -0.5
                                ],
                                [
                                    0.3833,
                                    0
                                ],
                                [
                                    0.5,
                                    0.4706
                                ],
                                [
                                    0.1667,
                                    0.5
                                ],
                                [
                                    -0.1667,
                                    0.1176
                                ],
                                [
                                    -0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5413,
                            'y' => 0.4505,
                            'width' => 0.067,
                            'height' => 0.0957,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    0.0833,
                                    -0.5
                                ],
                                [
                                    -0.3833,
                                    0
                                ],
                                [
                                    -0.5,
                                    0.4706
                                ],
                                [
                                    -0.1667,
                                    0.5
                                ],
                                [
                                    0.1667,
                                    0.1176
                                ],
                                [
                                    0.5,
                                    -0.2353
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'weak',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.5792,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4524,
                                    -0.4659
                                ],
                                [
                                    0.2619,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    0.0455
                                ],
                                [
                                    0.2619,
                                    0.5
                                ],
                                [
                                    -0.3333,
                                    0.3864
                                ],
                                [
                                    -0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'weak',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4074,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.2857,
                                    -0.4714
                                ],
                                [
                                    0.2143,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2714
                                ],
                                [
                                    0.5,
                                    0.1857
                                ],
                                [
                                    0.2857,
                                    0.4714
                                ],
                                [
                                    -0.1429,
                                    0.5
                                ],
                                [
                                    -0.4286,
                                    0.3
                                ],
                                [
                                    -0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Iliopsoas',
                            'status' => 'tight',
                            'x' => 0.4208,
                            'y' => 0.4257,
                            'width' => 0.0469,
                            'height' => 0.0495,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4524,
                                    -0.4659
                                ],
                                [
                                    -0.2619,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    0.0455
                                ],
                                [
                                    -0.2619,
                                    0.5
                                ],
                                [
                                    0.3333,
                                    0.3864
                                ],
                                [
                                    0.5,
                                    -0.0682
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'tight',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'weak',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5926,
                            'y' => 0.4434,
                            'width' => 0.0781,
                            'height' => 0.0985,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.2857,
                                    -0.4714
                                ],
                                [
                                    -0.2143,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2714
                                ],
                                [
                                    -0.5,
                                    0.1857
                                ],
                                [
                                    -0.2857,
                                    0.4714
                                ],
                                [
                                    0.1429,
                                    0.5
                                ],
                                [
                                    0.4286,
                                    0.3
                                ],
                                [
                                    0.5,
                                    -0.1
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ]
                ]
            ]
        ],
        'genu_recurvatum' => [
            'name' => 'Genu Recurvatum (Back Knee)',
            'description' => 'The knees push backwards past straight when standing, putting extra load on the back of the knee.',
            'effects' => 'Knees lock backwards when standing
Ache behind the knee after standing
Pelvis may tip forward to balance
Reduced control of the knee when landing',
            'detection' => [
                'sections' => [],
                'classifications' => [
                    'GENU_RECURVATUM'
                ]
            ],
            'order_index' => 12,
            'is_active' => true,
            'highlights' => [
                'male' => [
                    'front' => [
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'tight',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'tight',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'tight',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'tight',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ]
                ],
                'female' => [
                    'front' => [
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'tight',
                            'x' => 0.4074,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.5,
                                    -0.5
                                ],
                                [
                                    0.4286,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.2297
                                ],
                                [
                                    0.3857,
                                    0.2027
                                ],
                                [
                                    0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    -0.2857,
                                    0.2027
                                ],
                                [
                                    -0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'tight',
                            'x' => 0.5971,
                            'y' => 0.587,
                            'width' => 0.0781,
                            'height' => 0.1042,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.5,
                                    -0.5
                                ],
                                [
                                    -0.4286,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.2297
                                ],
                                [
                                    -0.3857,
                                    0.2027
                                ],
                                [
                                    -0.2143,
                                    0.473
                                ],
                                [
                                    0,
                                    0.5
                                ],
                                [
                                    0.2857,
                                    0.2027
                                ],
                                [
                                    0.4714,
                                    -0.2297
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ],
                    'back' => [
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.399,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4259,
                                    -0.5
                                ],
                                [
                                    0.1667,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.4787
                                ],
                                [
                                    0.463,
                                    -0.0957
                                ],
                                [
                                    0.3519,
                                    0.2872
                                ],
                                [
                                    0.2037,
                                    0.4787
                                ],
                                [
                                    -0.0556,
                                    0.5
                                ],
                                [
                                    -0.2778,
                                    0.3723
                                ],
                                [
                                    -0.4259,
                                    0.0319
                                ],
                                [
                                    -0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.6055,
                            'y' => 0.6039,
                            'width' => 0.1507,
                            'height' => 0.1323,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4259,
                                    -0.5
                                ],
                                [
                                    -0.1667,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.4787
                                ],
                                [
                                    -0.463,
                                    -0.0957
                                ],
                                [
                                    -0.3519,
                                    0.2872
                                ],
                                [
                                    -0.2037,
                                    0.4787
                                ],
                                [
                                    0.0556,
                                    0.5
                                ],
                                [
                                    0.2778,
                                    0.3723
                                ],
                                [
                                    0.4259,
                                    0.0319
                                ],
                                [
                                    0.5,
                                    -0.3085
                                ]
                            ],
                            'order_index' => 1
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.4196,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.3944,
                                    -0.3929
                                ],
                                [
                                    -0.0423,
                                    -0.5
                                ],
                                [
                                    0.3803,
                                    -0.4286
                                ],
                                [
                                    0.5,
                                    -0.1071
                                ],
                                [
                                    0.4859,
                                    0.3214
                                ],
                                [
                                    0.3099,
                                    0.4643
                                ],
                                [
                                    -0.0423,
                                    0.5
                                ],
                                [
                                    -0.3592,
                                    0.3571
                                ],
                                [
                                    -0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 2
                        ],
                        [
                            'muscle' => 'Gluteus Maximus',
                            'status' => 'weak',
                            'x' => 0.5848,
                            'y' => 0.4702,
                            'width' => 0.1585,
                            'height' => 0.0788,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.3944,
                                    -0.3929
                                ],
                                [
                                    0.0423,
                                    -0.5
                                ],
                                [
                                    -0.3803,
                                    -0.4286
                                ],
                                [
                                    -0.5,
                                    -0.1071
                                ],
                                [
                                    -0.4859,
                                    0.3214
                                ],
                                [
                                    -0.3099,
                                    0.4643
                                ],
                                [
                                    0.0423,
                                    0.5
                                ],
                                [
                                    0.3592,
                                    0.3571
                                ],
                                [
                                    0.5,
                                    0.0357
                                ]
                            ],
                            'order_index' => 3
                        ]
                    ],
                    'right_side' => [
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'tight',
                            'x' => 0.5273,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1667
                                ],
                                [
                                    0.4091,
                                    0.2436
                                ],
                                [
                                    0.0455,
                                    0.5
                                ],
                                [
                                    -0.3182,
                                    0.3462
                                ],
                                [
                                    -0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.4297,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    0.5,
                                    -0.1047
                                ],
                                [
                                    0.4,
                                    0.2674
                                ],
                                [
                                    0.2,
                                    0.5
                                ],
                                [
                                    -0.2,
                                    0.4535
                                ],
                                [
                                    -0.46,
                                    0.0814
                                ],
                                [
                                    -0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ],
                    'left_side' => [
                        [
                            'muscle' => 'Quadriceps (Rectus Femoris)',
                            'status' => 'tight',
                            'x' => 0.4727,
                            'y' => 0.5926,
                            'width' => 0.0614,
                            'height' => 0.1098,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4091,
                                    -0.5
                                ],
                                [
                                    -0.4091,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1667
                                ],
                                [
                                    -0.4091,
                                    0.2436
                                ],
                                [
                                    -0.0455,
                                    0.5
                                ],
                                [
                                    0.3182,
                                    0.3462
                                ],
                                [
                                    0.5,
                                    -0.0641
                                ]
                            ],
                            'order_index' => 0
                        ],
                        [
                            'muscle' => 'Hamstrings',
                            'status' => 'weak',
                            'x' => 0.5703,
                            'y' => 0.5983,
                            'width' => 0.0558,
                            'height' => 0.1211,
                            'rotation' => 0,
                            'points' => [
                                [
                                    0.4,
                                    -0.5
                                ],
                                [
                                    -0.4,
                                    -0.5
                                ],
                                [
                                    -0.5,
                                    -0.1047
                                ],
                                [
                                    -0.4,
                                    0.2674
                                ],
                                [
                                    -0.2,
                                    0.5
                                ],
                                [
                                    0.2,
                                    0.4535
                                ],
                                [
                                    0.46,
                                    0.0814
                                ],
                                [
                                    0.5,
                                    -0.2907
                                ]
                            ],
                            'order_index' => 1
                        ]
                    ]
                ]
            ]
        ]
    ];

    public function run(): void
    {
        foreach (self::CONDITIONS as $slug => $condition) {
            if (MuscleCondition::where('slug', $slug)->exists()) {
                continue;
            }

            $highlights = $condition['highlights'] ?? [];
            unset($condition['highlights']);

            $muscleCondition = MuscleCondition::create(array_merge(
                ['slug' => $slug],
                $condition
            ));

            foreach ($highlights as $gender => $views) {
                foreach ($views as $view => $items) {
                    foreach ($items as $item) {
                        MuscleHighlight::create(array_merge(
                            [
                                'muscle_condition_id' => $muscleCondition->id,
                                'gender' => $gender,
                                'view' => $view,
                            ],
                            $item
                        ));
                    }
                }
            }
        }
    }
}