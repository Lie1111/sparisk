<?php

namespace Database\Seeders;

use App\Models\MuscleCondition;
use Illuminate\Database\Seeder;

/**
 * Seeds the muscle map: the posture conditions the app can detect, the
 * plain-English wording shown to the patient, and the weak / tight muscle
 * outlines for each one (taken from the client's reference charts).
 *
 * Outlines were traced in pixels on the 896 x 1776 boy images in
 * storage/app/public/muscle-body; admins fine-tune them on the Muscle Map
 * page. Existing conditions are left untouched so admin edits are never
 * overwritten.
 */
class MuscleMapSeeder extends Seeder
{
    private const IMAGE_WIDTH = 896;
    private const IMAGE_HEIGHT = 1776;

    /** The front and back images are symmetric about this x. */
    private const MIDLINE = 450;

    /**
     * The girl's torso is narrower than the boy's: above this y the outline
     * is pulled towards the midline by GIRL_TORSO_SCALE.
     */
    private const GIRL_TORSO_BOTTOM = 700;
    private const GIRL_TORSO_SCALE = 0.92;

    /**
     * view => zone => outline in image pixels. Front and back zones are drawn
     * on the image-left side (`_l`); the image-right twin (`_r`) is mirrored
     * about MIDLINE. Side zones are drawn on the right side view; the left
     * side view uses the same outline mirrored.
     */
    private const SHAPES = [
        'front' => [
            'scm_l' => [[398, 222], [412, 226], [440, 285], [447, 302], [430, 305], [415, 280], [402, 250]],
            'scalene_l' => [[390, 238], [402, 240], [415, 275], [405, 300], [385, 296], [380, 270]],
            'pec_major_l' => [[445, 300], [400, 298], [345, 302], [318, 322], [305, 355], [300, 397], [318, 432], [350, 455], [395, 468], [440, 465], [447, 420], [447, 350]],
            'pec_minor_l' => [[325, 322], [345, 318], [405, 375], [410, 420], [390, 425], [345, 365], [322, 340]],
            'serratus_l' => [[305, 420], [330, 430], [345, 455], [345, 485], [332, 505], [312, 500], [303, 470]],
            'rectus_abdominis' => [[405, 468], [450, 470], [495, 468], [512, 520], [518, 600], [510, 680], [490, 720], [450, 730], [410, 720], [390, 680], [382, 600], [388, 520]],
            'rectus_abdominis_upper' => [[405, 468], [450, 470], [495, 468], [512, 520], [515, 560], [385, 560], [388, 520]],
            'ext_oblique_l' => [[325, 440], [350, 470], [385, 520], [385, 640], [378, 690], [345, 700], [322, 660], [312, 580], [310, 500]],
            'int_oblique_l' => [[335, 600], [380, 610], [390, 680], [370, 715], [340, 700], [325, 650]],
            'iliopsoas_l' => [[385, 715], [410, 715], [438, 800], [445, 880], [425, 885], [405, 820], [385, 760]],
            'tfl_l' => [[296, 745], [318, 750], [326, 800], [320, 870], [310, 940], [297, 940], [290, 860], [289, 790]],
            'rectus_femoris_l' => [[330, 950], [395, 950], [400, 1000], [392, 1080], [380, 1130], [365, 1135], [345, 1080], [332, 1000]],
            'adductors_l' => [[360, 955], [435, 958], [432, 1000], [420, 1060], [408, 1100], [390, 1095], [372, 1040], [362, 990]],
            'vmo_l' => [[405, 1085], [425, 1100], [428, 1140], [415, 1172], [395, 1170], [385, 1140], [392, 1105]],
        ],
        'back' => [
            'upper_trap_l' => [[425, 200], [447, 205], [447, 300], [420, 312], [380, 305], [330, 300], [305, 298], [330, 282], [380, 258], [400, 235]],
            'levator_l' => [[403, 210], [418, 212], [410, 260], [385, 300], [370, 296], [392, 250]],
            'mid_trap_l' => [[445, 298], [445, 355], [400, 345], [355, 330], [312, 315], [310, 300], [360, 300]],
            'lower_trap_l' => [[385, 325], [445, 335], [447, 420], [445, 520], [425, 470], [400, 400]],
            'rhomboid_l' => [[380, 305], [440, 300], [442, 345], [435, 395], [395, 425], [378, 420], [374, 360]],
            'lat_l' => [[300, 400], [330, 420], [375, 440], [415, 480], [435, 560], [430, 620], [400, 640], [360, 620], [330, 560], [315, 500], [305, 450]],
            'erector_l' => [[428, 330], [445, 330], [446, 690], [420, 690], [412, 620], [418, 450]],
            'erector_lumbar_l' => [[420, 600], [446, 600], [446, 690], [418, 690], [412, 640]],
            'multifidus_l' => [[436, 620], [447, 620], [447, 690], [434, 690]],
            'ql_l' => [[395, 620], [420, 615], [422, 690], [395, 690], [385, 660]],
            'glute_max_l' => [[320, 780], [370, 765], [430, 775], [447, 820], [445, 880], [420, 900], [370, 905], [325, 885], [305, 840]],
            'glute_med_l' => [[300, 740], [340, 722], [395, 728], [400, 760], [350, 775], [310, 790], [296, 770]],
            'glute_min_l' => [[305, 750], [345, 738], [375, 745], [350, 770], [312, 780]],
            'hip_rotators_l' => [[340, 840], [400, 835], [440, 845], [438, 865], [395, 862], [345, 860]],
            'hamstring_l' => [[300, 955], [380, 955], [425, 960], [420, 1050], [405, 1140], [385, 1185], [350, 1190], [320, 1160], [300, 1080], [290, 1000]],
            'biceps_femoris_l' => [[290, 960], [395, 958], [400, 1050], [390, 1140], [370, 1185], [330, 1185], [305, 1150], [292, 1080], [286, 1000]],
        ],
        'right_side' => [
            'upper_trap' => [[395, 225], [420, 230], [440, 280], [400, 285], [360, 285], [340, 290], [365, 262]],
            'levator' => [[420, 215], [440, 215], [445, 255], [430, 280], [415, 270], [415, 240]],
            'pec' => [[470, 300], [500, 315], [530, 345], [555, 385], [560, 420], [545, 445], [510, 450], [475, 430], [470, 390], [468, 340]],
            'mid_trap' => [[335, 290], [370, 290], [360, 330], [330, 330], [328, 310]],
            'rhomboid' => [[328, 320], [355, 320], [352, 380], [330, 380], [324, 350]],
            'lower_trap' => [[326, 370], [350, 370], [350, 440], [335, 450], [326, 410]],
            'lat' => [[330, 430], [355, 420], [360, 500], [355, 560], [340, 560], [332, 500]],
            'erector' => [[326, 330], [345, 330], [352, 450], [365, 560], [378, 650], [362, 660], [345, 560], [330, 450]],
            'erector_lumbar' => [[350, 540], [368, 540], [382, 640], [366, 660], [352, 600]],
            'rectus_abdominis' => [[510, 450], [545, 450], [558, 500], [552, 600], [548, 690], [515, 690], [505, 600], [505, 500]],
            'rectus_abdominis_upper' => [[510, 450], [545, 450], [558, 500], [556, 560], [505, 560], [505, 500]],
            'oblique' => [[470, 460], [505, 465], [510, 560], [510, 680], [480, 690], [462, 620], [465, 540]],
            'iliopsoas' => [[500, 715], [530, 712], [540, 760], [530, 800], [505, 790], [498, 750]],
            'tfl' => [[430, 720], [460, 725], [470, 790], [460, 860], [440, 860], [432, 790]],
            'glute' => [[345, 705], [380, 700], [400, 740], [400, 820], [385, 870], [355, 875], [335, 840], [330, 770]],
            'glute_med' => [[360, 695], [400, 690], [420, 720], [405, 745], [370, 740]],
            'rectus_femoris' => [[450, 955], [495, 955], [500, 1020], [495, 1100], [475, 1150], [455, 1120], [445, 1040]],
            'hamstring' => [[365, 955], [405, 955], [410, 1040], [405, 1120], [395, 1170], [375, 1160], [362, 1080], [360, 1000]],
            'adductor_magnus' => [[408, 960], [440, 960], [442, 1040], [430, 1100], [412, 1090], [405, 1020]],
            'vmo' => [[465, 1105], [490, 1105], [495, 1150], [480, 1175], [465, 1160]],
        ],
    ];

    /**
     * slug => condition wording, detection and [zone, muscle, status] per view.
     */
    private const CONDITIONS = [
        'kyphosis' => [
            'name' => 'Kyphosis',
            'description' => 'An increased forward rounding of the upper back. The shoulders and head drift forward and the chest closes in.',
            'effects' => "Rounded upper back and shoulders\nNeck and upper-back stiffness or pain\nReduced chest expansion when breathing\nHead carried forward of the body",
            'detection' => ['classifications' => ['KYPHOSIS', 'KYPHOSIS_LORDOSIS'], 'sections' => []],
            'muscles' => [
                'front' => [
                    ['pec_major_l', 'Pectoralis Major', 'tight'], ['pec_major_r', 'Pectoralis Major', 'tight'],
                    ['pec_minor_l', 'Pectoralis Minor', 'tight'], ['pec_minor_r', 'Pectoralis Minor', 'tight'],
                ],
                'back' => [
                    ['upper_trap_l', 'Upper Trapezius', 'tight'], ['upper_trap_r', 'Upper Trapezius', 'tight'],
                    ['erector_l', 'Erector Spinae', 'tight'], ['erector_r', 'Erector Spinae', 'tight'],
                    ['rhomboid_l', 'Rhomboid', 'weak'], ['rhomboid_r', 'Rhomboid', 'weak'],
                    ['lat_l', 'Latissimus Dorsi', 'weak'], ['lat_r', 'Latissimus Dorsi', 'weak'],
                ],
                'side' => [
                    ['upper_trap', 'Upper Trapezius', 'tight'], ['pec', 'Pectoralis Major', 'tight'],
                    ['erector', 'Erector Spinae', 'tight'], ['rhomboid', 'Rhomboid', 'weak'], ['lat', 'Latissimus Dorsi', 'weak'],
                ],
            ],
        ],
        'lordosis' => [
            'name' => 'Lordosis',
            'description' => 'An increased inward curve of the lower back. The pelvis tips forward and the stomach and buttocks push out.',
            'effects' => "Lower-back aches after standing or walking\nStomach pushes forward\nTight hip flexors and lower back\nWeak core and buttock muscles",
            'detection' => ['classifications' => ['LORDOSIS', 'KYPHOSIS_LORDOSIS'], 'sections' => []],
            'muscles' => [
                'front' => [
                    ['rectus_abdominis', 'Rectus Abdominis', 'weak'],
                    ['iliopsoas_l', 'Iliopsoas', 'tight'], ['iliopsoas_r', 'Iliopsoas', 'tight'],
                ],
                'back' => [
                    ['erector_l', 'Erector Spinae', 'tight'], ['erector_r', 'Erector Spinae', 'tight'],
                    ['glute_max_l', 'Gluteus Maximus', 'weak'], ['glute_max_r', 'Gluteus Maximus', 'weak'],
                    ['hamstring_l', 'Hamstrings', 'tight'], ['hamstring_r', 'Hamstrings', 'tight'],
                ],
                'side' => [
                    ['erector', 'Erector Spinae', 'tight'], ['rectus_abdominis', 'Rectus Abdominis', 'weak'],
                    ['iliopsoas', 'Iliopsoas', 'tight'], ['glute', 'Gluteus Maximus', 'weak'], ['hamstring', 'Hamstrings', 'tight'],
                ],
            ],
        ],
        'flat_back' => [
            'name' => 'Flat Back',
            'description' => 'The natural inward curve of the lower back is reduced, so the spine looks straight and the pelvis tucks under.',
            'effects' => "Difficulty standing upright for long periods\nLower-back and thigh fatigue\nBody leans slightly forward\nReduced shock absorption of the spine",
            'detection' => ['classifications' => ['FLAT_BACK', 'FLATBACK'], 'sections' => []],
            'muscles' => [
                'front' => [
                    ['rectus_abdominis', 'Rectus Abdominis', 'tight'],
                    ['ext_oblique_l', 'External Oblique', 'tight'], ['ext_oblique_r', 'External Oblique', 'tight'],
                    ['rectus_femoris_l', 'Rectus Femoris', 'weak'], ['rectus_femoris_r', 'Rectus Femoris', 'weak'],
                    ['iliopsoas_l', 'Iliopsoas', 'weak'], ['iliopsoas_r', 'Iliopsoas', 'weak'],
                ],
                'back' => [
                    ['erector_l', 'Erector Spinae', 'weak'], ['erector_r', 'Erector Spinae', 'weak'],
                    ['multifidus_l', 'Multifidus', 'weak'], ['multifidus_r', 'Multifidus', 'weak'],
                    ['glute_max_l', 'Gluteus Maximus', 'weak'], ['glute_max_r', 'Gluteus Maximus', 'weak'],
                    ['hamstring_l', 'Hamstrings', 'tight'], ['hamstring_r', 'Hamstrings', 'tight'],
                ],
                'side' => [
                    ['erector', 'Erector Spinae', 'weak'], ['rectus_abdominis', 'Rectus Abdominis', 'tight'],
                    ['iliopsoas', 'Iliopsoas', 'weak'], ['rectus_femoris', 'Rectus Femoris', 'weak'],
                    ['glute', 'Gluteus Maximus', 'weak'], ['hamstring', 'Hamstrings', 'tight'],
                ],
            ],
        ],
        'sway_back' => [
            'name' => 'Sway Back',
            'description' => 'The pelvis shifts forward and the upper body leans back behind it, giving a swayed standing posture.',
            'effects' => "Upper body leans backwards\nHips pushed forward of the feet\nLower-back and hip discomfort\nWeak buttock and hip flexor muscles",
            'detection' => ['classifications' => ['SWAYBACK', 'SWAY_BACK'], 'sections' => []],
            'muscles' => [
                'front' => [
                    ['rectus_abdominis_upper', 'Rectus Abdominis (Upper)', 'tight'],
                    ['ext_oblique_l', 'Obliques (Upper)', 'tight'], ['ext_oblique_r', 'Obliques (Upper)', 'tight'],
                    ['rectus_femoris_l', 'Rectus Femoris', 'weak'], ['rectus_femoris_r', 'Rectus Femoris', 'weak'],
                    ['iliopsoas_l', 'Iliopsoas', 'weak'], ['iliopsoas_r', 'Iliopsoas', 'weak'],
                ],
                'back' => [
                    ['erector_lumbar_l', 'Erector Spinae (Lumbar)', 'weak'], ['erector_lumbar_r', 'Erector Spinae (Lumbar)', 'weak'],
                    ['glute_max_l', 'Gluteus Maximus', 'weak'], ['glute_max_r', 'Gluteus Maximus', 'weak'],
                    ['hamstring_l', 'Hamstrings', 'tight'], ['hamstring_r', 'Hamstrings', 'tight'],
                ],
                'side' => [
                    ['rectus_abdominis_upper', 'Rectus Abdominis (Upper)', 'tight'], ['erector_lumbar', 'Erector Spinae (Lumbar)', 'weak'],
                    ['iliopsoas', 'Iliopsoas', 'weak'], ['rectus_femoris', 'Rectus Femoris', 'weak'],
                    ['glute', 'Gluteus Maximus', 'weak'], ['hamstring', 'Hamstrings', 'tight'],
                ],
            ],
        ],
        'postural_scoliosis' => [
            'name' => 'Postural Scoliosis',
            'description' => 'A sideways shift of the spine caused by muscle imbalance. The muscles on the inner (concave) side shorten and the outer (convex) side lengthens.',
            'effects' => "Shoulders sit at different heights\nUneven waist line\nOne-sided back tightness\nBody shifts to one side when standing",
            'detection' => [
                'classifications' => ['PELVIC_OBLIQUITY'],
                'sections' => [
                    ['section' => 'A5', 'level' => ['moderate', 'severe']],
                    ['section' => 'A4', 'level' => ['moderate', 'severe']],
                    ['section' => 'B4', 'level' => ['moderate', 'severe']],
                ],
            ],
            'muscles' => [
                'front' => [
                    ['ext_oblique_l', 'External Oblique (Concave Side)', 'tight'], ['int_oblique_l', 'Internal Oblique (Concave Side)', 'tight'],
                    ['ext_oblique_r', 'External Oblique (Convex Side)', 'weak'], ['int_oblique_r', 'Internal Oblique (Convex Side)', 'weak'],
                ],
                'back' => [
                    ['erector_l', 'Erector Spinae (Concave Side)', 'tight'], ['erector_r', 'Erector Spinae (Convex Side)', 'weak'],
                    ['multifidus_l', 'Multifidus (Concave Side)', 'tight'], ['multifidus_r', 'Multifidus (Convex Side)', 'weak'],
                    ['ql_l', 'Quadratus Lumborum (Concave Side)', 'tight'], ['ql_r', 'Quadratus Lumborum (Convex Side)', 'weak'],
                ],
                'side' => [
                    ['oblique', 'Obliques (Concave Side)', 'tight'], ['erector', 'Erector Spinae', 'weak'],
                ],
            ],
        ],
        'anterior_pelvic_tilt' => [
            'name' => 'Anterior Pelvic Tilt',
            'description' => 'The front of the pelvis drops and the back of the pelvis rises, increasing the lower-back curve.',
            'effects' => "Lower-back tightness or pain\nStomach and buttocks stick out\nTight front of the hips\nWeak core, buttock and hamstring muscles",
            'detection' => [
                'classifications' => [],
                'sections' => [['section' => 'side_lordosis', 'direction' => 'above', 'level' => ['moderate', 'severe']]],
            ],
            'muscles' => [
                'front' => [
                    ['rectus_abdominis', 'Rectus Abdominis', 'weak'],
                    ['ext_oblique_l', 'External Oblique', 'weak'], ['ext_oblique_r', 'External Oblique', 'weak'],
                    ['int_oblique_l', 'Internal Oblique', 'weak'], ['int_oblique_r', 'Internal Oblique', 'weak'],
                    ['tfl_l', 'Tensor Fasciae Latae (TFL)', 'tight'], ['tfl_r', 'Tensor Fasciae Latae (TFL)', 'tight'],
                    ['rectus_femoris_l', 'Rectus Femoris', 'tight'], ['rectus_femoris_r', 'Rectus Femoris', 'tight'],
                ],
                'back' => [
                    ['erector_lumbar_l', 'Erector Spinae (Lumbar)', 'tight'], ['erector_lumbar_r', 'Erector Spinae (Lumbar)', 'tight'],
                    ['ql_l', 'Quadratus Lumborum (QL)', 'tight'], ['ql_r', 'Quadratus Lumborum (QL)', 'tight'],
                    ['glute_max_l', 'Gluteus Maximus', 'weak'], ['glute_max_r', 'Gluteus Maximus', 'weak'],
                    ['hamstring_l', 'Hamstrings', 'weak'], ['hamstring_r', 'Hamstrings', 'weak'],
                ],
                'side' => [
                    ['erector_lumbar', 'Erector Spinae (Lumbar)', 'tight'], ['iliopsoas', 'Iliopsoas', 'tight'],
                    ['tfl', 'Tensor Fasciae Latae (TFL)', 'tight'], ['rectus_femoris', 'Rectus Femoris', 'tight'],
                    ['rectus_abdominis', 'Rectus Abdominis', 'weak'], ['glute', 'Gluteus Maximus', 'weak'], ['hamstring', 'Hamstrings', 'weak'],
                ],
            ],
        ],
        'posterior_pelvic_tilt' => [
            'name' => 'Posterior Pelvic Tilt',
            'description' => 'The front of the pelvis rises and the back of the pelvis drops, flattening the lower back.',
            'effects' => "Flattened lower back\nTucked-under buttocks\nTight hamstrings and abdominals\nLower-back fatigue when sitting or standing",
            'detection' => [
                'classifications' => [],
                'sections' => [['section' => 'side_lordosis', 'direction' => 'below', 'level' => ['moderate', 'severe']]],
            ],
            'muscles' => [
                'front' => [
                    ['rectus_abdominis', 'Rectus Abdominis', 'tight'],
                    ['ext_oblique_l', 'External Oblique', 'tight'], ['ext_oblique_r', 'External Oblique', 'tight'],
                    ['int_oblique_l', 'Internal Oblique', 'tight'], ['int_oblique_r', 'Internal Oblique', 'tight'],
                    ['iliopsoas_l', 'Iliopsoas', 'weak'], ['iliopsoas_r', 'Iliopsoas', 'weak'],
                    ['rectus_femoris_l', 'Rectus Femoris', 'weak'], ['rectus_femoris_r', 'Rectus Femoris', 'weak'],
                ],
                'back' => [
                    ['erector_lumbar_l', 'Erector Spinae (Lumbar)', 'weak'], ['erector_lumbar_r', 'Erector Spinae (Lumbar)', 'weak'],
                    ['multifidus_l', 'Multifidus (Lumbar)', 'weak'], ['multifidus_r', 'Multifidus (Lumbar)', 'weak'],
                    ['glute_max_l', 'Gluteus Maximus', 'tight'], ['glute_max_r', 'Gluteus Maximus', 'tight'],
                    ['hamstring_l', 'Hamstrings', 'tight'], ['hamstring_r', 'Hamstrings', 'tight'],
                ],
                'side' => [
                    ['rectus_abdominis', 'Rectus Abdominis', 'tight'], ['oblique', 'External Oblique', 'tight'],
                    ['iliopsoas', 'Iliopsoas', 'weak'], ['rectus_femoris', 'Rectus Femoris', 'weak'],
                    ['glute', 'Gluteus Maximus', 'tight'], ['hamstring', 'Hamstrings', 'tight'],
                ],
            ],
        ],
        'uneven_shoulder' => [
            'name' => 'Uneven Shoulders',
            'description' => 'One shoulder sits higher than the other because the neck and upper-shoulder muscles pull unevenly.',
            'effects' => "One shoulder visibly higher\nNeck and shoulder tension on one side\nHeadaches or neck stiffness\nUneven arm swing and carrying posture",
            'detection' => [
                'classifications' => [],
                'sections' => [
                    ['section' => 'A3', 'level' => ['moderate', 'severe']],
                    ['section' => 'B3', 'level' => ['moderate', 'severe']],
                ],
            ],
            'muscles' => [
                'front' => [
                    ['scm_l', 'Sternocleidomastoid (SCM)', 'tight'], ['scalene_l', 'Scalenes', 'tight'],
                    ['pec_minor_l', 'Pectoralis Minor (if involved)', 'tight'], ['serratus_r', 'Serratus Anterior', 'weak'],
                ],
                'back' => [
                    ['upper_trap_l', 'Upper Trapezius', 'tight'], ['levator_r', 'Levator Scapulae', 'tight'],
                    ['rhomboid_l', 'Rhomboid Minor (if involved)', 'tight'],
                    ['mid_trap_r', 'Middle Trapezius', 'weak'], ['lower_trap_r', 'Lower Trapezius', 'weak'], ['rhomboid_r', 'Rhomboid Major', 'weak'],
                ],
                'side' => [
                    ['upper_trap', 'Upper Trapezius', 'tight'], ['levator', 'Levator Scapulae', 'tight'],
                    ['mid_trap', 'Middle Trapezius', 'weak'], ['lower_trap', 'Lower Trapezius', 'weak'],
                ],
            ],
        ],
        'genu_valgum' => [
            'name' => 'Knock Knees (Genu Valgum)',
            'description' => 'The knees angle inwards and touch or nearly touch while the ankles stay apart.',
            'effects' => "Knees collapse inwards when standing or squatting\nKnee or hip discomfort with activity\nFeet may roll inwards\nWeak hip muscles that control the knee",
            // The 2D pose landmarks do not measure frontal knee alignment yet,
            // so this condition is not detected automatically.
            'detection' => ['classifications' => [], 'sections' => []],
            'muscles' => [
                'front' => [
                    ['adductors_l', 'Adductors (Longus / Brevis / Magnus)', 'tight'], ['adductors_r', 'Adductors (Longus / Brevis / Magnus)', 'tight'],
                    ['tfl_l', 'Tensor Fasciae Latae (TFL)', 'tight'], ['tfl_r', 'Tensor Fasciae Latae (TFL)', 'tight'],
                    ['vmo_l', 'Vastus Medialis Oblique (VMO)', 'weak'], ['vmo_r', 'Vastus Medialis Oblique (VMO)', 'weak'],
                ],
                'back' => [
                    ['glute_med_l', 'Gluteus Medius', 'weak'], ['glute_med_r', 'Gluteus Medius', 'weak'],
                    ['glute_max_l', 'Gluteus Maximus', 'weak'], ['glute_max_r', 'Gluteus Maximus', 'weak'],
                    ['glute_min_l', 'Gluteus Minimus', 'weak'], ['glute_min_r', 'Gluteus Minimus', 'weak'],
                    ['hip_rotators_l', 'Deep Hip External Rotators', 'weak'], ['hip_rotators_r', 'Deep Hip External Rotators', 'weak'],
                    ['biceps_femoris_l', 'Biceps Femoris (may be involved)', 'tight'], ['biceps_femoris_r', 'Biceps Femoris (may be involved)', 'tight'],
                ],
                'side' => [
                    ['glute_med', 'Gluteus Medius', 'weak'], ['tfl', 'Tensor Fasciae Latae (TFL)', 'tight'],
                    ['adductor_magnus', 'Adductor Magnus', 'tight'], ['vmo', 'Vastus Medialis Oblique (VMO)', 'weak'],
                ],
            ],
        ],
        'forward_head' => [
            'name' => 'Forward Head Posture',
            'description' => 'The head sits in front of the shoulders instead of above them, so the neck and upper back work harder to hold it up.',
            'effects' => "Neck and upper-shoulder tension\nHeadaches after long screen or study time\nChin pokes forward when sitting\nRounded upper back over time",
            'detection' => ['classifications' => ['FORWARD_HEAD'], 'sections' => []],
            'muscles' => [
                'front' => [
                    ['scm_l', 'Sternocleidomastoid (SCM)', 'tight'], ['scm_r', 'Sternocleidomastoid (SCM)', 'tight'],
                    ['pec_minor_l', 'Pectoralis Minor', 'tight'], ['pec_minor_r', 'Pectoralis Minor', 'tight'],
                ],
                'back' => [
                    ['upper_trap_l', 'Upper Trapezius', 'tight'], ['upper_trap_r', 'Upper Trapezius', 'tight'],
                    ['levator_l', 'Levator Scapulae', 'tight'], ['levator_r', 'Levator Scapulae', 'tight'],
                    ['mid_trap_l', 'Middle Trapezius', 'weak'], ['mid_trap_r', 'Middle Trapezius', 'weak'],
                    ['lower_trap_l', 'Lower Trapezius', 'weak'], ['lower_trap_r', 'Lower Trapezius', 'weak'],
                ],
                'side' => [
                    ['upper_trap', 'Upper Trapezius', 'tight'], ['levator', 'Levator Scapulae', 'tight'],
                    ['pec', 'Pectoralis Minor', 'tight'], ['lower_trap', 'Lower Trapezius', 'weak'],
                ],
            ],
        ],
        'flexed_knee' => [
            'name' => 'Flexed Knee Posture',
            'description' => 'The knees stay slightly bent when standing, so the thigh and hip muscles never fully relax.',
            'effects' => "Knees do not straighten fully when standing\nTired thighs after standing or walking\nHips and trunk lean slightly forward\nKnee discomfort with stairs or squats",
            'detection' => ['classifications' => ['FLEXED_KNEE'], 'sections' => []],
            'muscles' => [
                'front' => [
                    ['iliopsoas_l', 'Iliopsoas', 'tight'], ['iliopsoas_r', 'Iliopsoas', 'tight'],
                    ['rectus_femoris_l', 'Quadriceps (Rectus Femoris)', 'weak'], ['rectus_femoris_r', 'Quadriceps (Rectus Femoris)', 'weak'],
                ],
                'back' => [
                    ['hamstring_l', 'Hamstrings', 'tight'], ['hamstring_r', 'Hamstrings', 'tight'],
                    ['glute_max_l', 'Gluteus Maximus', 'weak'], ['glute_max_r', 'Gluteus Maximus', 'weak'],
                ],
                'side' => [
                    ['iliopsoas', 'Iliopsoas', 'tight'], ['hamstring', 'Hamstrings', 'tight'],
                    ['rectus_femoris', 'Quadriceps (Rectus Femoris)', 'weak'], ['glute', 'Gluteus Maximus', 'weak'],
                ],
            ],
        ],
        'genu_recurvatum' => [
            'name' => 'Genu Recurvatum (Back Knee)',
            'description' => 'The knees push backwards past straight when standing, putting extra load on the back of the knee.',
            'effects' => "Knees lock backwards when standing\nAche behind the knee after standing\nPelvis may tip forward to balance\nReduced control of the knee when landing",
            'detection' => ['classifications' => ['GENU_RECURVATUM'], 'sections' => []],
            'muscles' => [
                'front' => [
                    ['rectus_femoris_l', 'Quadriceps (Rectus Femoris)', 'tight'], ['rectus_femoris_r', 'Quadriceps (Rectus Femoris)', 'tight'],
                ],
                'back' => [
                    ['hamstring_l', 'Hamstrings', 'weak'], ['hamstring_r', 'Hamstrings', 'weak'],
                    ['glute_max_l', 'Gluteus Maximus', 'weak'], ['glute_max_r', 'Gluteus Maximus', 'weak'],
                ],
                'side' => [
                    ['rectus_femoris', 'Quadriceps (Rectus Femoris)', 'tight'], ['hamstring', 'Hamstrings', 'weak'],
                ],
            ],
        ],
    ];

    public function run(): void
    {
        $order = 0;

        foreach (self::CONDITIONS as $slug => $definition) {
            $order++;

            if (MuscleCondition::where('slug', $slug)->exists()) {
                continue;
            }

            $condition = MuscleCondition::create([
                'slug' => $slug,
                'name' => $definition['name'],
                'description' => $definition['description'],
                'effects' => $definition['effects'],
                'detection' => $definition['detection'],
                'order_index' => $order,
                'is_active' => true,
            ]);

            foreach (['male', 'female'] as $gender) {
                foreach ($definition['muscles'] as $view => $muscles) {
                    // The side outlines are drawn once and used for both sides.
                    $targets = $view === 'side' ? ['right_side' => false, 'left_side' => true] : [$view => false];

                    foreach ($targets as $target => $mirrored) {
                        foreach ($muscles as $index => [$zone, $muscle, $status]) {
                            $condition->highlights()->create(array_merge(
                                $this->shape($view === 'side' ? 'right_side' : $view, $zone, $gender, $mirrored),
                                [
                                    'gender' => $gender,
                                    'view' => $target,
                                    'muscle' => $muscle,
                                    'status' => $status,
                                    'rotation' => 0,
                                    'order_index' => $index,
                                ],
                            ));
                        }
                    }
                }
            }
        }
    }

    /**
     * A traced outline as the highlight's normalized box plus its points
     * inside that box (-0.5..0.5).
     *
     * @return array{x: float, y: float, width: float, height: float, points: array<int, array{0: float, 1: float}>}
     */
    private function shape(string $view, string $zone, string $gender, bool $mirrored): array
    {
        $pixels = self::SHAPES[$view][$zone] ?? null;

        // `_r` zones are the mirror of their traced `_l` twin.
        if ($pixels === null && str_ends_with($zone, '_r')) {
            $pixels = array_map(
                fn(array $p) => [2 * self::MIDLINE - $p[0], $p[1]],
                self::SHAPES[$view][substr($zone, 0, -2) . '_l'],
            );
        }

        $pixels = array_map(function (array $p) use ($view, $gender, $mirrored) {
            [$x, $y] = $p;

            if ($gender === 'female' && in_array($view, ['front', 'back'], true) && $y < self::GIRL_TORSO_BOTTOM) {
                $x = self::MIDLINE + ($x - self::MIDLINE) * self::GIRL_TORSO_SCALE;
            }

            // The left side image is the right side image flipped.
            if ($mirrored) {
                $x = self::IMAGE_WIDTH - $x;
            }

            return [$x / self::IMAGE_WIDTH, $y / self::IMAGE_HEIGHT];
        }, $pixels);

        $xs = array_column($pixels, 0);
        $ys = array_column($pixels, 1);
        $width = max($xs) - min($xs);
        $height = max($ys) - min($ys);
        $cx = (max($xs) + min($xs)) / 2;
        $cy = (max($ys) + min($ys)) / 2;

        return [
            'x' => round($cx, 4),
            'y' => round($cy, 4),
            'width' => round($width, 4),
            'height' => round($height, 4),
            'points' => array_map(
                fn(array $p) => [round(($p[0] - $cx) / $width, 4), round(($p[1] - $cy) / $height, 4)],
                $pixels,
            ),
        ];
    }
}
