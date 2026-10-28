<?php

namespace App\Services\Sparisk;

use App\Models\AssessmentImage;
use App\Models\PostureAssessment;
use App\Models\PostureMeasurement;
use Barryvdh\DomPDF\Facade\Pdf as PdfFacade;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Renders the SPARISK posture assessment PDF.
 *
 * The PDF is produced by the backend so the admin panel and the mobile app
 * download exactly the same document, and the app no longer has to build (or
 * ship the libraries for) the report itself.
 */
class SpariskReportPdfService
{
    /** The four posture views, in the order they appear in the report. */
    private const VIEWS = [
        'front' => ['letter' => 'A', 'name' => 'FRONT'],
        'back' => ['letter' => 'B', 'name' => 'BACK'],
        'right_side' => ['letter' => 'C', 'name' => 'RIGHT SIDE'],
        'left_side' => ['letter' => 'D', 'name' => 'LEFT SIDE'],
    ];

    /** SPARISK alignment colour names mapped to brand hex values. */
    private const STATUS_HEX = [
        'green' => '#16A34A',
        'yellow' => '#CA8A04',
        'orange' => '#EA580C',
        'red' => '#DC2626',
        'blue' => '#2563EB',
        'grey' => '#64748B',
    ];

    /**
     * Anatomical name for each body zone, per view.
     *
     * The app captures a fixed set of zones per view and stores them as bare
     * rectangles, so the body part a zone covers is recovered from its position
     * in that list. Zones placed by hand in the admin carry their own label.
     */
    private const HIGHLIGHT_LABELS = [
        'front' => ['Head', 'Shoulders', 'Shoulders', 'Arms', 'Arms', 'Core', 'Knees', 'Knees', 'Feet', 'Feet'],
        'back' => ['Neck', 'Shoulders', 'Lats', 'Lats', 'Lower Back', 'Knees', 'Knees', 'Feet', 'Feet'],
        'right_side' => ['Head', 'Chest', 'Upper Back', 'Abdomen', 'Hip', 'Thigh', 'Calf'],
        'left_side' => ['Head', 'Chest', 'Upper Back', 'Abdomen', 'Hip', 'Thigh', 'Calf'],
    ];

    /** Display height of a posture photo and the label gutter beside it (pt). */
    private const PHOTO_HEIGHT = 360.0;

    /**
     * The photo plate now sits beside its measurement table, so the photo box
     * gets the left column of the content width; the table takes the right.
     */
    private const PLATE_PHOTO_WIDTH = 300.0;

    /**
     * The two gutters beside a photo hold the level names (left) and their
     * deviations (right). They are sized for the longest label the plates
     * print ("Top of the curve"), so a name never spills onto the body.
     */
    private const PHOTO_GUTTER = 58.0;

    /** Usable width inside the A4 page margins (595.28 - 38 - 38 pt). */
    private const CONTENT_WIDTH = 519.0;

    /**
     * Square crops printed beside each Primary Finding.
     *
     * A finding that only names a body region ("Upper Back Curve") still leaves
     * the reader guessing where on the photo it was measured. These crops are
     * cut from the same capture the measurement used, around the landmark chain
     * that defines the angle, so the row points at the body part it describes.
     *
     * `points` are MediaPipe landmark indices; `pad` widens the box so the joint
     * is not cropped at its edge.
     */
    private const FINDING_CROPS = [
        // Side profiles — sagittal plumb / curve measurements.
        'C1' => ['right_side', [8, 24, 28], 0.18],
        'C2' => ['right_side', [8, 12], 0.30],
        'C3' => ['right_side', [12, 24], 0.20],
        'C4' => ['right_side', [24, 28], 0.18],
        'C5' => ['right_side', [24, 26, 28], 0.14],
        'C6' => ['right_side', [26, 28], 0.22],
        'C7' => ['right_side', [28, 32], 0.22],
        'D1' => ['left_side', [7, 23, 27], 0.18],
        'D2' => ['left_side', [7, 11], 0.30],
        'D3' => ['left_side', [11, 23], 0.20],
        'D4' => ['left_side', [23, 27], 0.18],
        'D5' => ['left_side', [23, 25, 27], 0.14],
        'D6' => ['left_side', [25, 27], 0.22],
        'D7' => ['left_side', [27, 31], 0.22],
        'side_cva' => ['right_side', [8, 12], 0.30],
        'side_kyphosis' => ['right_side', [8, 12, 24], 0.16],
        'side_lordosis' => ['right_side', [12, 24, 26], 0.16],
        // Front view.
        'A1' => ['front', [0, 27, 28], 0.12],
        'A2' => ['front', [2, 5], 0.55],
        'A3' => ['front', [11, 12], 0.28],
        'A4' => ['front', [11, 12, 23, 24], 0.14],
        'A5' => ['front', [11, 12, 23, 24], 0.14],
        'A6' => ['front', [11, 12, 27, 28], 0.12],
        'A7' => ['front', [23, 24], 0.30],
        'A8' => ['front', [24, 26, 28], 0.16],
        'A9' => ['front', [23, 25, 27], 0.16],
        'A10' => ['front', [28, 32], 0.22],
        'A11' => ['front', [27, 31], 0.22],
        // Back view.
        'B1' => ['back', [0, 27, 28], 0.12],
        'B2' => ['back', [7, 8], 0.55],
        'B3' => ['back', [11, 12], 0.28],
        'B4' => ['back', [11, 12, 23, 24], 0.14],
        'B5' => ['back', [11, 12, 27, 28], 0.12],
        'B6' => ['back', [23, 24], 0.30],
        'B7' => ['back', [25, 26, 27, 28], 0.14],
        'B8' => ['back', [27, 28, 31, 32], 0.20],
    ];

    /** Rendered size of a finding crop (px); printed at 84px, so 260 is ample. */
    private const FINDING_CROP_PX = 260;

    /** Label width, the gap before a leader line, and the dot that ends it (pt). */
    private const LABEL_WIDTH = 52.0;

    private const LEADER_GAP = 1.0;

    private const DOT_SIZE = 5.0;

    /**
     * Views whose stored photo is mirrored against the app's zone grid.
     *
     * The phone previews the front camera mirrored, so the file that reaches
     * the backend runs opposite to the coordinates the app recorded. The two
     * side profiles are the only views where that difference is visible.
     */
    private const MIRRORED_VIEWS = ['right_side', 'left_side'];

    /**
     * Where a label points when its zone does not cover the part it names.
     *
     * The stored zones are broad muscle groups, so the shoulder label receives
     * the pec/rhomboid box and its centre lands on the chest or mid-back. These
     * anchors are normalized (x, y) positions on the shoulder itself.
     */
    private const HIGHLIGHT_ANCHORS = [
        'front' => ['Shoulders' => [0.27, 0.22]],
        'back' => ['Shoulders' => [0.26, 0.22]],
    ];

    // ------------------------------------------------------------------
    // APECS posture overlay
    // ------------------------------------------------------------------

    /**
     * MediaPipe pose landmark indices the overlay draws with.
     *
     * The app normalises its 33 detected landmarks to the photo (0..1) and
     * uploads them in index order, so a lookup by index is stable.
     */
    private const LM = [
        'nose' => 0,
        'l_ear' => 7,
        'r_ear' => 8,
        'l_shoulder' => 11,
        'r_shoulder' => 12,
        'l_elbow' => 13,
        'r_elbow' => 14,
        'l_wrist' => 15,
        'r_wrist' => 16,
        'l_hip' => 23,
        'r_hip' => 24,
        'l_knee' => 25,
        'r_knee' => 26,
        'l_ankle' => 27,
        'r_ankle' => 28,
        'l_foot' => 31,
        'r_foot' => 32,
    ];

    /** Overlay palette, matching the reference plates. */
    private const OV_GRID = 'rgba(100,116,139,0.5)';

    private const OV_GREEN = '#2ECC40';

    private const OV_RED = '#E74C3C';

    private const OV_ORANGE = '#F59E0B';

    private const OV_BLUE = '#2563EB';

    /** Grid lines drawn per photo height, so every view shares one cell size. */
    private const OV_GRID_ROWS = 26;

    /**
     * Levels drawn across the body for the upright views, top to bottom.
     *
     * Each entry pairs a name with the two landmarks its horizontal line joins.
     * Derived entries interpolate between the shoulder and the hip, so a level
     * without its own landmark (the ribcage, the axillae) still lands on the
     * right part of the trunk.
     */
    private const OV_LEVELS_UPRIGHT = [
        'front' => [
            ['Head', 'l_ear', 'r_ear'],
            ['Acrom.', 'l_shoulder', 'r_shoulder'],
            ['Axill.', '@0.10', '@0.10'],
            ['Ribc.', '@0.30', '@0.30'],
            ['Torso', '@0.50', '@0.50'],
            ['ASIS', 'l_hip', 'r_hip'],
            ['Knees', 'l_knee', 'r_knee'],
            ['Feet', 'l_ankle', 'r_ankle'],
        ],
        'back' => [
            ['Ears', 'l_ear', 'r_ear'],
            ['Shoulders', 'l_shoulder', 'r_shoulder'],
            ['Axillae', '@0.20', '@0.20'],
            ['Top of the curve', '@0.42', '@0.42'],
            ['PSIS', 'l_hip', 'r_hip'],
            ['Knees', 'l_knee', 'r_knee'],
            ['Feet', 'l_ankle', 'r_ankle'],
        ],
    ];

    /**
     * Levels for the side profiles: a single landmark per level, joined to the
     * plumb line. The first three are read off the horizontal, the leg and foot
     * entries off the segment itself.
     */
    private const OV_LEVELS_SIDE = [
        ['Head', 'ear', 'horiz'],
        ['Shoulders', 'shoulder', 'horiz'],
        ['Pelvis', 'hip', 'horiz'],
        ['Femur', 'knee', 'vert'],
        ['Tibia', 'ankle', 'vert'],
        ['Foot', 'foot', 'horiz'],
    ];

    /**
     * The blue bone segments each view draws, as landmark pairs.
     *
     * Upright views read both legs; the profiles read the single side facing
     * the camera, chained from the ear down to the foot so the whole posture
     * line is traced.
     */
    private const OV_SEGMENTS = [
        'front' => [
            ['l_hip', 'l_knee'], ['l_knee', 'l_ankle'],
            ['r_hip', 'r_knee'], ['r_knee', 'r_ankle'],
        ],
        'back' => [
            ['l_hip', 'l_knee'], ['l_knee', 'l_ankle'],
            ['r_hip', 'r_knee'], ['r_knee', 'r_ankle'],
        ],
        'right_side' => [
            ['r_ear', 'r_shoulder'], ['r_shoulder', 'r_hip'],
            ['r_hip', 'r_knee'], ['r_knee', 'r_ankle'], ['r_ankle', 'r_foot'],
        ],
        'left_side' => [
            ['l_ear', 'l_shoulder'], ['l_shoulder', 'l_hip'],
            ['l_hip', 'l_knee'], ['l_knee', 'l_ankle'], ['l_ankle', 'l_foot'],
        ],
    ];

    /** Marker diameter (px) drawn on each landmark. */
    private const OV_MARKER = 6.0;

    /**
     * Measurement row codes drawn beside each anatomical label.
     *
     * The view tables name every coded row (A1…A11, B1…B8, C1…C7, D1…D7) in
     * their Section column, so prefixing a line with that row's code is what
     * ties the label on the photo to the row it reports — the reader can match
     * "A3 Shoulders" on the body to "A3 Shoulder Alignment" in the table. A
     * region the tables do not measure on its own (the arms, the lats) keeps
     * its plain name. Several codes print as "A8/A9" because one line serves
     * both sides of a paired row.
     */
    private const HIGHLIGHT_CODES = [
        'front' => [
            'Alignment' => 'A1',
            'Head' => 'A2',
            'Shoulders' => 'A3',
            'Acrom.' => 'A3',
            'Axill.' => 'A4',
            'Ribc.' => 'A5',
            'Core' => 'A6',
            'Torso' => 'A6',
            'ASIS' => 'A7',
            'Knees' => 'A8/A9',
            'Feet' => 'A10/A11',
        ],
        'back' => [
            'Alignment' => 'B1',
            'Neck' => 'B2',
            'Ears' => 'B2',
            'Shoulders' => 'B3',
            'Axillae' => 'B4',
            'Top of the curve' => 'B5',
            'Lower Back' => 'B6',
            'PSIS' => 'B6',
            'Knees' => 'B7',
            'Feet' => 'B8',
        ],
        'right_side' => ['Alignment' => 'C1', 'Head' => 'C2', 'Chest' => 'C3', 'Shoulder' => 'C3', 'Hip' => 'C4', 'Pelvis' => 'C4', 'Thigh' => 'C5', 'Femur' => 'C5', 'Calf' => 'C6', 'Tibia' => 'C6', 'Foot' => 'C7'],
        'left_side' => ['Alignment' => 'D1', 'Head' => 'D2', 'Chest' => 'D3', 'Shoulder' => 'D3', 'Hip' => 'D4', 'Pelvis' => 'D4', 'Thigh' => 'D5', 'Femur' => 'D5', 'Calf' => 'D6', 'Tibia' => 'D6', 'Foot' => 'D7'],
    ];

    /**
     * The measurement row behind each printed overlay angle.
     *
     * The overlay draws geometry (lines, markers, plumb) from the landmarks,
     * but every number it prints is the stored deviation angle of the row this
     * map points at — the same value the report table shows for that row. The
     * photo and the table therefore cannot quote two different readings.
     */
    private const OV_ALIGNMENT_SECTION = [
        'front' => 'A1',
        'back' => 'B1',
        'right_side' => 'C1',
        'left_side' => 'D1',
    ];

    /**
     * Overlay level label → the section codes that level line reports. Paired
     * entries (the two front knees, the two feet) print both sides at once.
     */
    private const OV_LEVEL_SECTIONS = [
        'front' => [
            'Head' => ['A2'],
            'Acrom.' => ['A3'],
            'Axill.' => ['A4'],
            'Ribc.' => ['A5'],
            'Torso' => ['A6'],
            'ASIS' => ['A7'],
            'Knees' => ['A8', 'A9'],
            'Feet' => ['A10', 'A11'],
        ],
        'back' => [
            'Ears' => ['B2'],
            'Shoulders' => ['B3'],
            'Axillae' => ['B4'],
            'Top of the curve' => ['B5'],
            'PSIS' => ['B6'],
            'Knees' => ['B7'],
            'Feet' => ['B8'],
        ],
        'right_side' => [
            'Head' => ['C2'],
            'Shoulders' => ['C3'],
            'Pelvis' => ['C4'],
            'Femur' => ['C5'],
            'Tibia' => ['C6'],
            'Foot' => ['C7'],
        ],
        'left_side' => [
            'Head' => ['D2'],
            'Shoulders' => ['D3'],
            'Pelvis' => ['D4'],
            'Femur' => ['D5'],
            'Tibia' => ['D6'],
            'Foot' => ['D7'],
        ],
    ];

    public function __construct(
        private SpariskInterpretationEngine $interpreter,
        private SpariskBmiEngine $bmiEngine,
        private SpariskMeasurementEngine $measurementEngine,
        private AquaticSkillsProgressService $skillsProgress,
    ) {}

    public static function hex(?string $color): string
    {
        return self::STATUS_HEX[$color] ?? self::STATUS_HEX['grey'];
    }

    public function filename(PostureAssessment $assessment): string
    {
        return 'SPARISK_Report_'.$assessment->id.'.pdf';
    }

    /**
     * Serve the report as a download (attachment).
     *
     * The admin panel asks for the extended report, which appends the full
     * per-view angle breakdown pages. The mobile app keeps the standard report
     * (the default), so its document is unchanged.
     */
    public function download(PostureAssessment $assessment, bool $extended = false): Response
    {
        $data = $this->buildData($assessment, $extended);
        $pdf = $this->make($data);
        $this->stampChrome($pdf, $data);

        return $pdf->download($this->filename($assessment));
    }

    /**
     * Serve the report inline, so a browser can preview it.
     */
    public function stream(PostureAssessment $assessment, bool $extended = false): Response
    {
        $data = $this->buildData($assessment, $extended);
        $pdf = $this->make($data);
        $this->stampChrome($pdf, $data);

        return $pdf->stream($this->filename($assessment));
    }

    private function make(array $data): PDF
    {
        return PdfFacade::loadView('pdf.assessment-report', $data)
            ->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 110,
            ])
            ->setPaper('a4', 'portrait');
    }

    /**
     * Draw the running page head and footer on every page. Dompdf cannot repeat
     * a `position: fixed` element reliably (it anchors to the content box and
     * overlaps the body), so the chrome is painted straight onto the canvas
     * after the render — into the page margins, where no content is laid out.
     */
    private function stampChrome(PDF $pdf, array $data): void
    {
        try {
            $pdf->render();

            $canvas = $pdf->getCanvas();
            $metrics = $pdf->getFontMetrics();

            $regular = $metrics->getFont('DejaVu Sans', 'normal');

            try {
                $bold = $metrics->getFont('DejaVu Sans', 'bold');
            } catch (\Throwable) {
                $bold = $regular;
            }

            $dark = [0.039, 0.235, 0.314];
            $navy = [0.039, 0.180, 0.322];
            $hair = [0.843, 0.898, 0.918];
            $white = [1, 1, 1];
            $grey = [0.392, 0.455, 0.545];
            $left = 28.5;
            $right = 595.28 - 28.5;

            // SATA badge: "SATA" over "POSTURE ANALYSIS" in a navy block at the
            // left of the running head, with the report title beside it.
            $badgeX = $left;
            $badgeY = 6.0;
            $badgeH = 30.0;
            $badgePadX = 10.0;
            $sataText = 'SATA';
            $sataSize = 13.5;
            $sataY = $badgeY + 4.0;
            $subText = 'POSTURE ANALYSIS';
            $subSize = 6.0;
            $subY = $badgeY + 19.5;

            $badgeW = max(
                $metrics->getTextWidth($sataText, $bold, $sataSize),
                $metrics->getTextWidth($subText, $bold, $subSize),
            ) + ($badgePadX * 2);

            $title = 'POSTURE REPORT — Full Posture';
            $titleSize = 13.0;
            $titleX = $badgeX + $badgeW + 14.0;
            // Centre the title on the badge's vertical midpoint.
            $titleY = ($badgeY + ($badgeH / 2)) - ($titleSize * 0.6);

            $dateLine = 'Date: '.$data['assessment']['date'];
            $byLine = 'Generated by: SPARISK · '.$data['generated_at'];
            $metaY = $badgeY + $badgeH + 7.0;
            $byX = $right - $metrics->getTextWidth($byLine, $regular, 8.0);
            $headRuleY = 54.0;

            $footRuleY = 800.0;
            $footY = 806.0;
            // Right-align the counter against the widest it can get, so the
            // numbers line up down the page instead of drifting.
            $pageNumX = $right - $metrics->getTextWidth('Page 88 of 88', $regular, 7);

            $canvas->page_script(function ($pageNumber, $pageCount, $canvas) use (
                $regular, $bold, $dark, $navy, $hair, $white, $grey, $left, $right,
                $badgeX, $badgeY, $badgeW, $badgeH, $sataText, $sataSize, $sataY,
                $subText, $subSize, $subY, $badgePadX,
                $title, $titleX, $titleY, $titleSize, $dateLine, $byLine, $metaY, $byX,
                $headRuleY, $footRuleY, $footY, $pageNumX
            ) {
                // Page head: SATA badge, report title, then the date / byline.
                $canvas->filled_rectangle($badgeX, $badgeY, $badgeW, $badgeH, $navy);
                $canvas->text($badgeX + $badgePadX, $sataY, $sataText, $bold, $sataSize, $white, 0, 0);
                $canvas->text($badgeX + $badgePadX, $subY, $subText, $bold, $subSize, $white, 0, 0);
                $canvas->text($titleX, $titleY, $title, $bold, $titleSize, $dark, 0, 0);

                $canvas->text($left, $metaY, $dateLine, $bold, 8.0, $dark, 0, 0);
                $canvas->text($byX, $metaY, $byLine, $regular, 8.0, $grey, 0, 0);

                $canvas->line($left, $headRuleY, $right, $headRuleY, $hair, 0.8);

                // Page foot: brand line, then the page counter.
                $canvas->line($left, $footRuleY, $right, $footRuleY, $hair, 0.8);
                $canvas->text($left, $footY, 'SPARISK · SATA NeuroPosture', $regular, 7, $grey, 0, 0);
                $canvas->text($pageNumX, $footY, "Page {$pageNumber} of {$pageCount}", $regular, 7, $grey, 0, 0);
            });
        } catch (\Throwable) {
            // The chrome is cosmetic; it must never break the download.
        }
    }

    /**
     * Assemble everything the Blade template needs.
     *
     * `$extended` turns on the extra per-view angle breakdown pages. Only the
     * admin panel requests them; the app's report stays exactly as it was.
     */
    private function buildData(PostureAssessment $assessment, bool $extended = false): array
    {
        $assessment->load([
            'patient',
            'measurements',
            'images',
            'exerciseRecommendations',
            'massageRecommendations',
            'weeklyPrograms',
        ]);

        $statuses = config('sparisk.alignment_statuses');
        $views = $this->viewData($assessment, $statuses);
        $images = $this->imageData($assessment);

        return [
            'extended' => $extended,
            'generated_at' => now()->format('d/m/Y H:i'),
            'patient' => $this->patientData($assessment),
            'bmi' => $this->bmiData($assessment),
            'assessment' => [
                'date' => $assessment->assessment_date?->format('d/m/Y') ?? '-',
                'time_mark' => $assessment->time_mark,
                'score' => (int) $assessment->overall_score,
                'status' => $assessment->overall_status,
                'status_color' => $this->overallStatusColor((string) $assessment->overall_status),
                'clinical_summary' => $assessment->clinical_summary,
                'captured_views' => count($images),
                'marker_count' => array_sum(array_map(
                    fn (array $view) => count($view['rows']),
                    $views,
                )),
            ],
            'overview' => $this->overviewSentences($assessment),
            'views' => $views,
            'images' => $images,
            'classification' => $this->interpreter->classificationSummary($assessment),
            'symmetry' => $this->interpreter->generateSymmetry($assessment),
            'findings' => $this->primaryFindings($assessment, $statuses),
            'exercises' => $this->exerciseData($assessment->exerciseRecommendations),
            'massages' => $assessment->massageRecommendations,
            'weekly' => $assessment->weeklyPrograms,
            'skills' => $this->skillsData($assessment),
        ];
    }

    /**
     * The colour the overall score is printed in, so the summary card on page
     * one reads at a glance instead of asking the reader to interpret a label.
     */
    private function overallStatusColor(string $status): string
    {
        $status = strtoupper($status);

        if (str_contains($status, 'GOOD') || str_contains($status, 'EXCELLENT') || str_contains($status, 'NORMAL')) {
            return self::STATUS_HEX['green'];
        }

        if (str_contains($status, 'FAIR') || str_contains($status, 'MODERATE')) {
            return self::STATUS_HEX['orange'];
        }

        if (str_contains($status, 'MILD')) {
            return self::STATUS_HEX['yellow'];
        }

        return self::STATUS_HEX['red'];
    }

    /**
     * The patient's aquatic skills progress plus the line chart itself.
     *
     * The three charts are per patient, not per posture assessment, so the
     * report shows every sitting the patient has on record.
     *
     * Dompdf drops the shapes of an inline <svg> element (only the inner
     * <text> leaks into the flow), but it renders a standalone SVG document
     * embedded through <img> as real vector operators, so the line chart is
     * built here and handed to the template as a base64 data URI.
     *
     * @return array<string, mixed>
     */
    private function skillsData(PostureAssessment $assessment): array
    {
        $dashboard = $this->skillsProgress->forPatient($assessment->patient);
        $sessions = $dashboard['sessions'];

        $dashboard['chart'] = $sessions === [] ? null : [
            'uri' => 'data:image/svg+xml;base64,'.base64_encode($this->lineChartSvg($sessions)),
            'height' => 190.0,
        ];

        return $dashboard;
    }

    /**
     * The overall-progress line chart as a standalone SVG document, sized to
     * the report's content width in points.
     *
     * @param  array<int, array<string, mixed>>  $sessions
     */
    private function lineChartSvg(array $sessions): string
    {
        $width = self::CONTENT_WIDTH;
        $height = 190.0;
        $left = 36.0;
        $right = 22.0;
        $top = 18.0;
        $bottom = 40.0;
        $plotWidth = $width - $left - $right;
        $plotHeight = $height - $top - $bottom;

        $ceiling = max(1, ...array_map(fn (array $session) => (int) $session['max'], $sessions));
        $count = count($sessions);

        $x = function (int $index) use ($count, $left, $plotWidth): float {
            return $count === 1
                ? $left + ($plotWidth / 2)
                : $left + (($index / ($count - 1)) * $plotWidth);
        };
        $y = fn (float $score): float => $top + (1 - ($score / $ceiling)) * $plotHeight;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'"'
            .' viewBox="0 0 '.$width.' '.$height.'" font-family="DejaVu Sans">';
        $svg .= '<rect x="0" y="0" width="'.$width.'" height="'.$height.'" fill="#FFFFFF"/>';

        foreach ([0.0, 0.5, 1.0] as $fraction) {
            $lineY = round($top + (1 - $fraction) * $plotHeight, 2);
            $dash = $fraction === 0.0 ? '' : ' stroke-dasharray="3 3"';

            $svg .= '<line x1="'.$left.'" y1="'.$lineY.'" x2="'.($width - $right).'" y2="'.$lineY.'"'
                .' stroke="#E4EBEE" stroke-width="1"'.$dash.'/>';
            $svg .= '<text x="'.($left - 6).'" y="'.($lineY + 3).'" font-size="8" text-anchor="end" fill="#64748B">'
                .round($ceiling * $fraction).'</text>';
        }

        if ($count > 1) {
            $points = [];
            foreach ($sessions as $index => $session) {
                $points[] = round($x($index), 2).','.round($y((float) $session['score']), 2);
            }

            $svg .= '<polyline points="'.implode(' ', $points).'" fill="none" stroke="#0E7C86"'
                .' stroke-width="2.4" stroke-linejoin="round" stroke-linecap="round"/>';
        }

        // Thin the labels out so a long history never collides.
        $labelEvery = max(1, (int) ceil($count / 6));

        foreach ($sessions as $index => $session) {
            $pointX = round($x($index), 2);
            $pointY = round($y((float) $session['score']), 2);

            $svg .= '<circle cx="'.$pointX.'" cy="'.$pointY.'" r="3.4" fill="#FFFFFF"'
                .' stroke="#0E7C86" stroke-width="2"/>';

            if ($count <= 8) {
                $svg .= '<text x="'.$pointX.'" y="'.($pointY - 7).'" font-size="7.5" text-anchor="middle"'
                    .' fill="#0A3C50">'.$session['score'].'/'.$session['max'].'</text>';
            }

            if ($index % $labelEvery === 0 || $index === $count - 1) {
                $label = htmlspecialchars((string) $session['label'], ENT_QUOTES, 'UTF-8');
                $date = htmlspecialchars((string) ($session['date'] ?? ''), ENT_QUOTES, 'UTF-8');

                $svg .= '<text x="'.$pointX.'" y="'.($height - 22).'" font-size="7.5" text-anchor="middle"'
                    .' fill="#64748B">'.$label.'</text>';
                $svg .= '<text x="'.$pointX.'" y="'.($height - 12).'" font-size="6.5" text-anchor="middle"'
                    .' fill="#94A3B8">'.$date.'</text>';
            }
        }

        return $svg.'</svg>';
    }

    /**
     * The exercise cards, each with the image the admin attached to the source
     * intervention already inlined.
     */
    private function exerciseData($exercises): array
    {
        return $exercises->values()->map(fn ($exercise, $index) => [
            'no' => $index + 1,
            'name' => $exercise->exercise_name,
            'meta' => trim(
                ($exercise->program_level ?: '-')
                .' · '.($exercise->estimated_duration_minutes ?? '-').' min'
            ),
            'note' => $exercise->instructions ?: '',
            'image' => $this->inlineImage($exercise->image_url),
        ])->all();
    }

    /**
     * Turn an intervention's stored image into a data URI.
     *
     * Recommendations carry the URL the app loads (`/storage/...` for an
     * uploaded file, or the external link the admin pasted). Dompdf renders
     * with remote assets disabled, so the bytes are resolved here — read back
     * from the public disk, or fetched once for link-only entries — and
     * embedded in the document. Null when there is nothing to show, so the
     * card simply renders without a photo.
     */
    private function inlineImage(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '';

        if (str_starts_with($path, '/storage/')) {
            $relative = ltrim(substr($path, strlen('/storage/')), '/');

            if (! Storage::disk('public')->exists($relative)) {
                return null;
            }

            $mime = str_ends_with(strtolower($relative), '.png') ? 'image/png' : 'image/jpeg';

            return 'data:'.$mime.';base64,'.base64_encode(Storage::disk('public')->get($relative));
        }

        if (! preg_match('#^https?://#i', $url)) {
            return null;
        }

        try {
            $response = Http::timeout(8)->get($url);
        } catch (\Throwable) {
            return null;
        }

        $mime = (string) $response->header('Content-Type');

        if (! $response->successful() || ! str_starts_with($mime, 'image/')) {
            return null;
        }

        return 'data:'.$mime.';base64,'.base64_encode($response->body());
    }

    private function patientData(PostureAssessment $assessment): array
    {
        $patient = $assessment->patient;
        $gender = $patient->gender;

        return [
            'name' => $patient->name,
            'gender' => $gender ? ucfirst($gender) : '-',
            'age' => $patient->age ? $patient->age.' years' : '-',
            'height' => $patient->height ? rtrim(rtrim(number_format((float) $patient->height, 1), '0'), '.').' cm' : '-',
            'weight' => $patient->weight ? rtrim(rtrim(number_format((float) $patient->weight, 1), '0'), '.').' kg' : '-',
            'diagnosis' => $patient->diagnosis ?: '-',
            'neuro_profile' => $patient->neuro_profile_label ?: '-',
        ];
    }

    private function bmiData(PostureAssessment $assessment): ?array
    {
        $patient = $assessment->patient;
        $result = $this->bmiEngine->calculate(
            $patient->age,
            $patient->gender,
            $patient->height,
            $patient->weight,
        );

        if (! ($result['valid'] ?? false)) {
            return null;
        }

        return [
            'value' => $result['bmi_display'],
            'category' => $result['category'] ?? '-',
        ];
    }

    /**
     * The "Complete posture overview" narrative: one short sentence per point,
     * built from the interpretation engine so the app and the PDF agree.
     */
    private function overviewSentences(PostureAssessment $assessment): array
    {
        $classification = $this->interpreter->classificationSummary($assessment);
        $overall = $this->interpreter->generateOverallInterpretation($assessment);

        $lines = array_filter([
            $overall['summary'] ?? null,
            $assessment->clinical_summary,
            $classification['main_finding'] ? 'Main finding: '.$classification['main_finding'] : null,
            $classification['suspected_pattern_label'] ? 'Suspected pattern: '.$classification['suspected_pattern_label'].'.' : null,
            $classification['secondary_pattern_label'] ? 'Secondary finding: '.$classification['secondary_pattern_label'].'.' : null,
            $classification['asymmetry_flag'] ? 'Left/right asymmetry detected.' : 'No marked left/right asymmetry detected.',
        ]);

        return array_values($lines);
    }

    /**
     * The photo line drawn beside each measurement row, keyed by row code.
     *
     * A line can serve more than one row — the two front-view knees share the
     * single "Knees" line — so the map is built by expanding the codes the
     * photo labels already list. Both the photo and the tables read from
     * HIGHLIGHT_CODES, so the two can never disagree.
     */
    private function measurementLines(string $view): array
    {
        $lines = [];

        foreach (self::HIGHLIGHT_CODES[$view] ?? [] as $label => $codes) {
            foreach (explode('/', $codes) as $code) {
                $lines[$code] = $label;
            }
        }

        return $lines;
    }

    /**
     * The measurement tables, grouped by view with the SPARISK alignment
     * colour and the plain-language note for each row.
     */
    private function viewData(PostureAssessment $assessment, array $statuses): array
    {
        $views = [];
        $outputStatuses = config('sparisk.posture_output_statuses');
        $levelToStatus = config('sparisk.level_to_status');
        $referenceLevels = config('sparisk.reference_levels');

        foreach (self::VIEWS as $key => $meta) {
            $lines = $this->measurementLines($key);

            // Only the coded measurements (A1…A11, B1…B8, …) get a row in the
            // view tables and score cards; the uncoded extras (CVA, kyphosis
            // and lordosis indicators) are reported in the findings section.
            $rows = $assessment->measurements
                ->where('view', $key)
                ->filter(fn (PostureMeasurement $m) => preg_match('/^[A-Z]\d+$/', (string) $m->section))
                ->sort(fn (PostureMeasurement $a, PostureMeasurement $b) => strnatcasecmp((string) $a->section, (string) $b->section))
                ->values()
                ->map(function (PostureMeasurement $m) use ($lines, $levelToStatus, $outputStatuses, $referenceLevels) {
                    $status = $m->review_required ? 'review' : ($m->alignment_status ?? $m->severity ?? 'normal');
                    $statusCode = $m->review_required
                        ? 'review'
                        : ($m->status_code ?? ($levelToStatus[$status] ?? 'review'));
                    $outputMeta = $outputStatuses[$statusCode] ?? $outputStatuses['review'];
                    $label = $m->label ?: (config("sparisk.measurements.{$m->section}.label") ?? $m->section);
                    $line = $lines[(string) $m->section] ?? '';

                    // SATA output is DEVIATION ANGLE ONLY: the row reports the
                    // deviation from the applicable reference, never the raw
                    // anatomical angle. The marker code is printed only when the
                    // same code is drawn on the photo, so the numbering on the
                    // body and in the table can never disagree.
                    $code = isset($lines[(string) $m->section]) ? (string) $m->section : '';

                    // Naming the row the way the photo labels it lets a reader
                    // find "Hip ( Estimated Pelvic Alignment )" from the line
                    // that says "C4 Hip". When the line already carries the
                    // row's own name the two would only repeat, so it prints
                    // once ("Knees", not "Knees ( Knees )").
                    $name = $line !== '' && strcasecmp($line, $label) !== 0
                        ? $line.' ( '.$label.' )'
                        : $label;

                    $reference = $m->reference_value !== null ? (float) $m->reference_value : null;
                    $deviation = $m->deviation_angle !== null ? (float) $m->deviation_angle : null;
                    $levelMeta = $referenceLevels[$m->reference_level] ?? null;
                    $tolerance = $this->measurementEngine->toleranceFor($m);

                    return [
                        'code' => $code,
                        'section' => (string) $m->section,
                        'name' => $name,
                        // The compact table beside the photo has no room for
                        // the "Photo label ( measurement )" wording; the short
                        // measurement label reads cleanly in one column.
                        'short_name' => $label,
                        'measured' => $this->formatDecimal($m->measured_angle ?? $m->value),
                        'reference' => $reference !== null ? $this->formatDecimal($reference) : null,
                        // The published half-width the On Point / Near Point /
                        // Off Point bands are built from, so a reader can check
                        // the status against the cut-off it was judged on.
                        'tolerance' => $tolerance !== null ? $this->formatDecimal($tolerance) : null,
                        // The shared axis/landmarks/sign convention so a reader
                        // can confirm the measured angle and the reference use
                        // the same definition.
                        'definition' => $this->measurementEngine->definitionFor((string) $m->section),
                        'deviation' => $deviation !== null ? $this->formatSignedDecimal($deviation) : null,
                        'reference_level' => $levelMeta['label'] ?? null,
                        'is_baseline' => $m->reference_level === 'individual_baseline',
                        'status' => $outputMeta['label'],
                        'status_code' => $statusCode,
                        'correction' => $outputMeta['correction'] ?? $outputMeta['label'],
                        'color' => self::STATUS_HEX[$outputMeta['color']] ?? self::STATUS_HEX['grey'],
                        'note' => $m->position_note ?: $m->status_text,
                    ];
                })
                ->all();

            $summary = [
                'total' => count($rows),
                'on_point' => 0,
                'near_point' => 0,
                'off_point' => 0,
                'baseline' => 0,
                'review' => 0,
            ];

            foreach ($rows as $row) {
                $bucket = match ($row['status_code']) {
                    'on_point' => 'on_point',
                    'near_point' => 'near_point',
                    'off_point' => 'off_point',
                    'baseline_tracking' => 'baseline',
                    default => 'review',
                };

                $summary[$bucket]++;
            }

            $views[$key] = [
                'letter' => $meta['letter'],
                'name' => $meta['name'],
                'rows' => $rows,
                'summary' => $summary,
            ];
        }

        return $views;
    }

    /**
     * Posture photos embedded as data URIs (so dompdf never has to fetch them
     * over HTTP), keyed by view so each one can sit beside its measurement
     * table.
     */
    private function imageData(PostureAssessment $assessment): array
    {
        $names = ['front' => 'Front', 'back' => 'Back', 'right_side' => 'Right Side', 'left_side' => 'Left Side'];
        $images = [];

        foreach (self::VIEWS as $key => $meta) {
            $image = $assessment->images->where('view', $key)->sortBy('order_index')->last();

            if (! $image || ! $image->image_path || ! Storage::disk('public')->exists($image->image_path)) {
                continue;
            }

            $mime = str_ends_with(strtolower($image->image_path), '.png') ? 'image/png' : 'image/jpeg';
            $photo = $this->photoOverlay($image, $key, self::PLATE_PHOTO_WIDTH);

            $images[$key] = [
                'label' => $names[$key],
                'data' => 'data:'.$mime.';base64,'.base64_encode(Storage::disk('public')->get($image->image_path)),
                'overlay' => $this->poseOverlay($image, $key, $photo, $this->deviationLabels($assessment, $key)),
            ] + $photo;
        }

        return $images;
    }

    /**
     * The printed deviation of every stored row in a view, keyed by section —
     * the exact text the report table prints, so the photo overlay can quote
     * the same numbers instead of measuring the landmarks a second time.
     */
    private function deviationLabels(PostureAssessment $assessment, string $view): array
    {
        return $assessment->measurements
            ->where('view', $view)
            ->mapWithKeys(fn (PostureMeasurement $m) => [
                (string) $m->section => $m->deviation_angle !== null
                    ? $this->formatSignedDecimal($m->deviation_angle).'°'
                    : null,
            ])
            ->filter(fn (?string $label) => $label !== null)
            ->all();
    }

    /**
     * The badge printed beside one overlay level: the deviation of the row(s)
     * that level reports. Paired rows — the two front knees, the two feet —
     * print both sides, in the order the table lists them.
     */
    private function levelDeviation(string $view, string $label, array $deviations): string
    {
        $codes = self::OV_LEVEL_SECTIONS[$view][$label] ?? [];
        $values = array_values(array_filter(
            array_map(fn (string $code) => $deviations[$code] ?? null, $codes),
            fn (?string $value) => $value !== null,
        ));

        if ($values === []) {
            return '';
        }

        if (count($values) === 1) {
            return $values[0];
        }

        $sides = ['R', 'L'];

        return collect($values)
            ->map(fn (string $value, int $index) => trim(($sides[$index] ?? '').' '.$value))
            ->implode(' · ');
    }

    /**
     * Geometry for a posture photo and the anatomical labels pointing at it.
     *
     * The body zones are stored as normalized (0..1) rectangles, so they are
     * scaled to the size the photo is drawn at and the labels are emitted as
     * ready-to-use point values. Everything is measured inside one positioned
     * box — a photo with a label gutter on either side — the only arrangement
     * dompdf keeps aligned with the image.
     *
     * Each label sits in the gutter nearest the region it names and its leader
     * line runs from that label to the near edge of the zone, where it ends in
     * a dot. The line therefore points at the body part instead of crossing
     * the body, exactly like the reference plates.
     */
    private function photoOverlay(AssessmentImage $image, string $view, float $maxWidth = self::CONTENT_WIDTH): array
    {
        $size = @getimagesize(Storage::disk('public')->path($image->image_path));

        // Without the intrinsic size the overlay cannot be aligned to the
        // photo, so the caller falls back to a plain image.
        if (! $size || ! $size[0] || ! $size[1]) {
            return [];
        }

        $height = self::PHOTO_HEIGHT;
        $width = round($height * $size[0] / $size[1], 1);
        $gutter = self::PHOTO_GUTTER;
        $defaults = self::HIGHLIGHT_LABELS[$view] ?? [];
        $codes = self::HIGHLIGHT_CODES[$view] ?? [];
        $mirror = in_array($view, self::MIRRORED_VIEWS, true);

        // Zones naming the same body part (the two arms, the two knees…) are
        // merged, so one label serves a level instead of one per side.
        $zones = [];

        foreach (array_values((array) $image->highlights) as $index => $zone) {
            if (! is_array($zone) || ! isset($zone['left'], $zone['top'], $zone['width'], $zone['height'])) {
                continue;
            }

            $label = trim((string) ($zone['label'] ?? $zone['muscle'] ?? '')) ?: ($defaults[$index] ?? '');

            if ($label === '') {
                continue;
            }

            // A label with a fixed anchor points straight at the body part and
            // ignores the rectangle, so it never merges with a sibling zone.
            $anchor = self::HIGHLIGHT_ANCHORS[$view][$label] ?? null;

            if ($anchor) {
                $zones[$label] = [
                    'left' => $anchor[0],
                    'right' => $anchor[0],
                    'top' => $anchor[1],
                    'bottom' => $anchor[1],
                ];

                continue;
            }

            $left = (float) $zone['left'];

            if ($mirror) {
                $left = 1 - ($left + (float) $zone['width']);
            }

            $right = $left + (float) $zone['width'];
            $top = (float) $zone['top'];
            $bottom = $top + (float) $zone['height'];

            $zones[$label] = isset($zones[$label])
                ? [
                    'left' => min($zones[$label]['left'], $left),
                    'right' => max($zones[$label]['right'], $right),
                    'top' => min($zones[$label]['top'], $top),
                    'bottom' => max($zones[$label]['bottom'], $bottom),
                ]
                : compact('left', 'right', 'top', 'bottom');
        }

        // Both gutters are always reserved: names read from the left, values
        // from the right, so every plate on the report is centred and the
        // reader learns one reading order. A one-sided box would also shift the
        // photo off the page centre by half a gutter on every other page.
        $sides = [];

        foreach ($zones as $label => $zone) {
            // A region sitting a little past the midline of the photo belongs
            // to the right gutter; anything on or before it reads from the
            // left, so the paired front/back zones stay together.
            $sides[$label] = ($zone['left'] + $zone['right']) / 2 - 0.5 > 0.03;
        }

        // The box — the photo plus its two label gutters — has to fit the
        // column the caller allows, so the photo is scaled down from its
        // display height until the whole overlay fits that width. Scaling both
        // sides together keeps the labels aligned with the body.
        $maxImageWidth = round($maxWidth - ($gutter * 2), 1);

        if ($maxImageWidth > 0 && $width > $maxImageWidth) {
            $width = $maxImageWidth;
            $height = round($width * $size[1] / $size[0], 1);
        }

        $rows = [];

        foreach ($zones as $label => $zone) {
            $right = $sides[$label];

            $rows[] = [
                'label' => $label,
                'y' => round(($zone['top'] + $zone['bottom']) / 2 * $height, 1),
                'right' => $right,
                'x2' => round(($right ? $zone['right'] : $zone['left']) * $width, 1),
            ];
        }

        // The labels are placed top to bottom so the lines never cross and the
        // reading order follows the body.
        usort($rows, fn ($a, $b) => $a['y'] <=> $b['y']);

        $boxW = round(($gutter * 2) + $width, 1);
        $dot = self::DOT_SIZE / 2;

        $levels = [];

        foreach ($rows as $row) {
            $dotX = round($gutter + $row['x2'], 1);

            $levels[] = [
                'label' => $row['label'],
                'code' => $codes[$row['label']] ?? '',
                'y' => $row['y'],
                'dot_left' => round($dotX - $dot, 1),
                'dot_top' => round($row['y'] - $dot, 1),
                'label_left' => $row['right'] ? round($boxW - self::LABEL_WIDTH, 1) : 0,
                'label_align' => $row['right'] ? 'left' : 'right',
                'x1' => $row['right'] ? $dotX : round($gutter - self::LEADER_GAP, 1),
                'x2' => $row['right'] ? round($gutter + $width + self::LEADER_GAP, 1) : $dotX,
            ];
        }

        return [
            'box_w' => $boxW,
            'box_h' => $height,
            'img_left' => $gutter,
            'img_w' => $width,
            'label_width' => self::LABEL_WIDTH,
            'levels' => $levels,
        ];
    }

    /**
     * APECS pose overlay geometry for one posture photo.
     *
     * The app uploads the MediaPipe landmarks normalised to the photo, so the
     * grid, the plumb line, the alignment line, the segment lines and the
     * markers can be placed exactly where the body was measured. Every value
     * comes back as a point (or a rotated strip) inside the same positioned box
     * the anatomical labels are laid out in, which lets the Blade template draw
     * each element with a plain CSS border — dompdf then emits real vector
     * operators, so the overlay stays sharp at any zoom instead of being
     * flattened into the photo.
     *
     * An empty array means "no usable landmarks", and the caller falls back to
     * the photo-only layout.
     */
    private function poseOverlay(AssessmentImage $image, string $view, array $photo, array $deviations = []): array
    {
        $landmarks = (array) ($image->landmarks ?? []);

        if (count($landmarks) < 29 || ! isset($photo['img_left'], $photo['img_w'], $photo['box_h'])) {
            return [];
        }

        $imgLeft = (float) $photo['img_left'];
        $imgW = (float) $photo['img_w'];
        $imgH = (float) $photo['box_h'];

        // Normalized (0..1) photo coordinates to points inside the box.
        $px = fn (float $nx): float => round($imgLeft + ($nx * $imgW), 1);
        $py = fn (float $ny): float => round($ny * $imgH, 1);

        $at = function (string $name) use ($landmarks): ?array {
            $index = self::LM[$name] ?? null;
            $point = $index === null ? null : ($landmarks[$index] ?? null);

            if (! is_array($point) || ! isset($point['x'], $point['y'])) {
                return null;
            }

            return [(float) $point['x'], (float) $point['y']];
        };

        $mid = function (?array $a, ?array $b): ?array {
            if ($a === null) {
                return $b;
            }
            if ($b === null) {
                return $a;
            }

            return [($a[0] + $b[0]) / 2, ($a[1] + $b[1]) / 2];
        };

        $side = in_array($view, self::MIRRORED_VIEWS, true);
        $pre = $view === 'left_side' ? 'l_' : 'r_';

        // The plumb line hangs from the point under the body — the midpoint
        // between the ankles upright, the visible ankle in profile.
        $feet = $side ? $at($pre.'ankle') : $mid($at('l_ankle'), $at('r_ankle'));

        if ($feet === null) {
            return [];
        }

        $plumbX = $px($feet[0]);

        $overlay = [
            'grid_v' => [],
            'grid_h' => [],
            'plumb' => ['left' => $plumbX],
            'align' => null,
            'levels' => [],
            'labels' => [],
            'segments' => [],
            'markers' => [],
            'angle' => '',
        ];

        // ---- Grid: one cell size in every view, so the scales compare ----
        $cell = $imgH / self::OV_GRID_ROWS;

        for ($x = $cell; $x < $imgW - ($cell / 2); $x += $cell) {
            $overlay['grid_v'][] = round($imgLeft + $x, 1);
        }

        for ($y = $cell; $y < $imgH - ($cell / 2); $y += $cell) {
            $overlay['grid_h'][] = round($y, 1);
        }

        // ---- Red alignment line: head centre down to the feet ----
        $head = $side ? $at($pre.'ear') : $mid($at('l_ear'), $at('r_ear'));

        if ($head !== null) {
            $overlay['align'] = $this->overlayLine($px($head[0]), $py($head[1]), $plumbX, $py($feet[1]));
        }

        // The printed alignment is the stored Body Alignment deviation of this
        // view, i.e. the row the reader sees in the table below the photo. The
        // red line stays geometric: it shows where the body actually sits.
        $overlay['angle'] = $deviations[self::OV_ALIGNMENT_SECTION[$view] ?? ''] ?? '';

        // ---- Orange levels, one line per measured row ----
        if ($side) {
            foreach (self::OV_LEVELS_SIDE as [$label, $part]) {
                $point = $at($pre.$part);

                if ($point === null) {
                    continue;
                }

                $x = $px($point[0]);
                $y = $py($point[1]);

                $overlay['levels'][] = [
                    'label' => $label,
                    'left' => round(min($x, $plumbX), 1),
                    'top' => $y,
                    'width' => round(abs($x - $plumbX), 1),
                    'angle' => 0.0,
                    'deg' => $this->levelDeviation($view, $label, $deviations),
                    // The landmark is the point the label points at; the plumb
                    // line is the other end of the level.
                    'point' => [round($x, 1), $y],
                    // The ankle is the plumb itself, so its own level has no
                    // left/right direction; it reads from the right with the
                    // rest of the profile labels.
                    'side' => $x < $plumbX ? 'left' : 'right',
                ];

                $overlay['markers'][$label] = ['left' => round($x - (self::OV_MARKER / 2), 1), 'top' => round($y - (self::OV_MARKER / 2), 1)];
            }
        } else {
            foreach (self::OV_LEVELS_UPRIGHT[$view] ?? [] as [$label, $leftRef, $rightRef]) {
                $pl = $this->overlayLevelPoint($leftRef, 'l_', $at ?: null, $landmarks);
                $pr = $this->overlayLevelPoint($rightRef, 'r_', $at ?: null, $landmarks);

                if ($pl === null || $pr === null) {
                    continue;
                }

                $line = $this->overlayLine($px($pl[0]), $py($pl[1]), $px($pr[0]), $py($pr[1]));

                $overlay['levels'][] = [
                    'label' => $label,
                    'left' => $line['left'],
                    'top' => $line['top'],
                    'width' => $line['width'],
                    'angle' => $line['angle'],
                    'deg' => $this->levelDeviation($view, $label, $deviations),
                    'p1' => [round($px($pl[0]), 1), round($py($pl[1]), 1)],
                    'p2' => [round($px($pr[0]), 1), round($py($pr[1]), 1)],
                ];

                $overlay['markers'][$label.'_l'] = ['left' => round($px($pl[0]) - (self::OV_MARKER / 2), 1), 'top' => round($py($pl[1]) - (self::OV_MARKER / 2), 1)];
                $overlay['markers'][$label.'_r'] = ['left' => round($px($pr[0]) - (self::OV_MARKER / 2), 1), 'top' => round($py($pr[1]) - (self::OV_MARKER / 2), 1)];
            }
        }

        // ---- Blue bone segments ----
        foreach (self::OV_SEGMENTS[$view] ?? [] as [$from, $to]) {
            $a = $at($from);
            $b = $at($to);

            if ($a === null || $b === null) {
                continue;
            }

            $overlay['segments'][] = $this->overlayLine($px($a[0]), $py($a[1]), $px($b[0]), $py($b[1]));
            $overlay['markers'][$from] = ['left' => round($px($a[0]) - (self::OV_MARKER / 2), 1), 'top' => round($py($a[1]) - (self::OV_MARKER / 2), 1)];
            $overlay['markers'][$to] = ['left' => round($px($b[0]) - (self::OV_MARKER / 2), 1), 'top' => round($py($b[1]) - (self::OV_MARKER / 2), 1)];
        }

        // A profile reads as one column: the landmarks sit on both sides of
        // the plumb, so they are all printed from the gutter the majority of
        // them face instead of alternating left/right down the body.
        if ($side && $overlay['levels'] !== []) {
            $leftFacing = count(array_filter(
                $overlay['levels'],
                fn (array $level) => ($level['side'] ?? 'right') === 'left',
            ));
            $sharedSide = ($leftFacing * 2) >= count($overlay['levels']) ? 'left' : 'right';

            foreach ($overlay['levels'] as $index => $level) {
                $overlay['levels'][$index]['side'] = $sharedSide;
            }
        }

        $overlay['markers'] = array_values($overlay['markers']);
        $overlay['labels'] = $this->overlayGutterLabels(
            $view,
            $overlay['levels'],
            $photo,
            $overlay['angle'] !== '' && ! $side,
        );
        $overlay['label_scale'] = $this->overlayLabelScale($photo);

        // Grid goes first and markers last, so the template can emit the
        // layers in the order the reference plates stack them.
        return $overlay;
    }

    /**
     * The labels printed in the gutters beside a posture photo.
     *
     * The level lines already carry the reading for every measured row, so the
     * gutters only repeat that reading outside the body: the level name (with
     * its table code) on the left, the stored deviation on the right. The side
     * profiles are the exception — one landmark per level, so the name and its
     * value travel together in the gutter nearest that landmark.
     *
     * Label centres start at the level's own height and are then pushed apart
     * just enough that two boxes never overlap. The leader lines are emitted as
     * rotated strips, so a label that moved a little still points exactly at
     * its level endpoint instead of at an empty strip of grid.
     *
     * @param  array<int, array<string, mixed>>  $levels
     * @return array<int, array<string, mixed>>
     */
    /**
     * How much the gutter labels are scaled down for a short photo.
     *
     * A portrait plate is 232pt tall at the report's photo width; a square or
     * landscape upload is shorter. Shrinking the label type (and the reserved
     * box heights) keeps the whole column inside the plate instead of letting
     * the last row run past the photo.
     */
    private function overlayLabelScale(array $photo): float
    {
        $boxH = (float) ($photo['box_h'] ?? 232.0);

        return max(0.7, min(1.0, $boxH / 232.0));
    }

    private function overlayGutterLabels(string $view, array $levels, array $photo, bool $hasAlignmentTag): array
    {
        if ($levels === []) {
            return [];
        }

        $boxH = (float) $photo['box_h'];
        $imgLeft = (float) $photo['img_left'];
        $imgW = (float) $photo['img_w'];
        $sideView = in_array($view, self::MIRRORED_VIEWS, true);
        $scale = $this->overlayLabelScale($photo);
        $gap = max(1.5, 4.0 * $scale);

        $rows = [];

        foreach ($levels as $level) {
            $label = (string) ($level['label'] ?? '');
            if ($label === '') {
                continue;
            }

            $codes = self::OV_LEVEL_SECTIONS[$view][$label] ?? [];
            $hint = implode('/', $codes);

            if ($sideView) {
                $point = $level['point'] ?? null;
                if ($point === null) {
                    continue;
                }

                $rows[] = [
                    'label' => $label,
                    'code' => $hint,
                    'deg' => (string) ($level['deg'] ?? ''),
                    'y' => (float) $point[1],
                    'anchor' => [(float) $point[0], (float) $point[1]],
                    'side' => (string) ($level['side'] ?? 'left'),
                    'height' => (($level['deg'] ?? '') !== '' ? 26.0 : ($hint !== '' ? 17.0 : 11.0)) * $scale,
                ];

                continue;
            }

            $p1 = $level['p1'] ?? null;
            $p2 = $level['p2'] ?? null;

            if ($p1 === null || $p2 === null) {
                continue;
            }

            $rows[] = [
                'label' => $label,
                'code' => $hint,
                'deg' => (string) ($level['deg'] ?? ''),
                'y' => round((((float) $p1[1]) + ((float) $p2[1])) / 2, 1),
                'name_anchor' => (float) $p1[0] <= (float) $p2[0] ? $p1 : $p2,
                'value_anchor' => (float) $p1[0] <= (float) $p2[0] ? $p2 : $p1,
                // A long name wraps onto a second line inside the narrow
                // gutter, so it needs more room than a one-word level.
                'height' => ($hint !== ''
                    ? (strlen($label) > 12 ? 24.0 : 17.0)
                    : (strlen($label) > 12 ? 18.0 : 11.0)) * $scale,
            ];
        }

        if ($rows === []) {
            return [];
        }

        usort($rows, fn (array $a, array $b) => $a['y'] <=> $b['y']);

        // Reserve the strip holding the 0°/alignment tags at the top and the
        // Alignment tag at the bottom, so no gutter label can run under them.
        // Both tags have a fixed type size, so their bands do not scale with
        // the labels.
        $top = $hasAlignmentTag ? 16.0 : 4.0;
        $bottom = $boxH - 24.0;

        $centres = [];
        $cursor = $top;

        foreach ($rows as $index => $row) {
            $centre = max((float) $row['y'], $cursor + ($row['height'] / 2));
            $centres[$index] = $centre;
            $cursor = $centre + ($row['height'] / 2) + $gap;
        }

        $last = count($rows) - 1;
        $excess = ($centres[$last] + ($rows[$last]['height'] / 2)) - $bottom;

        if ($excess > 0) {
            // The labels follow the body top to bottom, so a low foot label can
            // push the last box past the plate. Compress the spacing between
            // the boxes instead of shifting the whole column, which would push
            // the head label above the photo.
            $spaces = [];

            for ($index = 0; $index < $last; $index++) {
                $spaces[$index] = max(0.0, $centres[$index + 1] - $centres[$index]
                    - (($rows[$index]['height'] + $rows[$index + 1]['height']) / 2));
            }

            $total = array_sum($spaces);
            $minimum = 1.0 * $last;
            $available = max($minimum, $total - $excess);
            $factor = $total > 0 ? $available / $total : 0.0;

            $centres[0] = max($centres[0], $top + ($rows[0]['height'] / 2));

            for ($index = 0; $index < $last; $index++) {
                $centres[$index + 1] = $centres[$index]
                    + (($rows[$index]['height'] + $rows[$index + 1]['height']) / 2)
                    + ($spaces[$index] * $factor);
            }

            // If the anchors were clustered low on the body, the compressed
            // column can still hang past the plate. Slide the whole column up
            // as far as the top reserve allows.
            $overflow = ($centres[$last] + ($rows[$last]['height'] / 2)) - $bottom;
            $headroom = $centres[0] - ($top + ($rows[0]['height'] / 2));

            if ($overflow > 0 && $headroom > 0) {
                $shift = min($overflow, $headroom);

                foreach ($centres as $index => $centre) {
                    $centres[$index] = $centre - $shift;
                }
            }
        }

        $labels = [];
        $leaderInset = 3.0;
        $leftGutterEdge = $imgLeft - $leaderInset;
        $rightGutterEdge = $imgLeft + $imgW + $leaderInset;

        foreach ($rows as $index => $row) {
            $centre = round($centres[$index], 1);
            $height = (float) $row['height'];

            if ($sideView) {
                $right = ($row['side'] ?? 'left') === 'right';
                $anchor = $row['anchor'];

                $labels[] = [
                    'side' => $right ? 'right' : 'left',
                    'code' => $row['code'],
                    'label' => $row['label'],
                    'deg' => $row['deg'],
                    'label_top' => round($centre - ($height / 2), 1),
                    'label_height' => $height,
                    'leader' => $right
                        ? $this->overlayLine($anchor[0], $anchor[1], $rightGutterEdge, $centre)
                        : $this->overlayLine($leftGutterEdge, $centre, $anchor[0], $anchor[1]),
                ];

                continue;
            }

            $nameAnchor = $row['name_anchor'];
            $valueAnchor = $row['value_anchor'];

            $labels[] = [
                'side' => 'left',
                'code' => $row['code'],
                'label' => $row['label'],
                'deg' => '',
                'label_top' => round($centre - ($height / 2), 1),
                'label_height' => $height,
                'leader' => $this->overlayLine($leftGutterEdge, $centre, $nameAnchor[0], $nameAnchor[1]),
            ];

            if ($row['deg'] !== '') {
                $labels[] = [
                    'side' => 'right',
                    'code' => '',
                    'label' => '',
                    'deg' => $row['deg'],
                    'label_top' => round($centre - (5.0 * $scale), 1),
                    'label_height' => 10.0 * $scale,
                    'leader' => $this->overlayLine($valueAnchor[0], $valueAnchor[1], $rightGutterEdge, $centre),
                ];
            }
        }

        return $labels;
    }

    /**
     * A level's two endpoints for the upright views.
     *
     * A plain landmark name resolves directly. An `@0.30` entry has no
     * landmark of its own (the ribcage, the axillae), so it is interpolated
     * that fraction of the way from the shoulder down to the hip on that side.
     */
    private function overlayLevelPoint(string $ref, string $prefix, ?callable $at, array $landmarks): ?array
    {
        if (! str_starts_with($ref, '@')) {
            return $at === null ? null : $at($ref);
        }

        $index = fn (string $name) => self::LM[$name] ?? null;

        $shoulder = $landmarks[$index($prefix.'shoulder') ?? -1] ?? null;
        $hip = $landmarks[$index($prefix.'hip') ?? -1] ?? null;

        if (! is_array($shoulder) || ! is_array($hip) || ! isset($shoulder['x'], $shoulder['y'], $hip['x'], $hip['y'])) {
            return null;
        }

        $t = (float) substr($ref, 1);

        return [
            (float) $shoulder['x'] + (((float) $hip['x'] - (float) $shoulder['x']) * $t),
            (float) $shoulder['y'] + (((float) $hip['y'] - (float) $shoulder['y']) * $t),
        ];
    }

    /**
     * A line as a horizontal strip plus the rotation that aims it at its
     * endpoint. Dompdf pivots the strip about its top-left corner, so the
     * rotation is measured from the +x axis to the (dx, dy) rise of the page,
     * whose y axis points down.
     */
    private function overlayLine(float $x1, float $y1, float $x2, float $y2): array
    {
        $dx = $x2 - $x1;
        $dy = $y2 - $y1;

        return [
            'left' => round($x1, 1),
            'top' => round($y1, 1),
            'width' => round(sqrt(($dx * $dx) + ($dy * $dy)), 1),
            'angle' => round(rad2deg(atan2($dy, $dx)), 2),
        ];
    }

    private function primaryFindings(PostureAssessment $assessment, array $statuses): array
    {
        $findings = $this->interpreter->generatePrimaryFindings($assessment);

        return array_map(function (array $finding) use ($assessment, $statuses) {
            $statusMeta = $statuses[$finding['alignment_status']] ?? $statuses['normal'];

            return [
                'name' => $finding['label'] ?: $finding['section'],
                'value' => $this->formatDegree($finding['value']),
                'status' => $statusMeta['label'],
                'color' => self::STATUS_HEX[$statusMeta['color']] ?? self::STATUS_HEX['grey'],
                'note' => $finding['interpretation'] ?: $finding['position_note'],
                // A square crop of the body area the finding points at, so the
                // reader does not have to guess where on the photo it was
                // measured. NULL when the capture or landmarks are missing.
                'crop' => $this->findingCrop($assessment, (string) $finding['section']),
            ];
        }, $findings);
    }

    /**
     * Square crop of one body region, cut from the capture that measurement
     * used, as a JPEG data URI.
     *
     * The box is built from the MediaPipe landmarks of the chain that defines
     * the angle, expanded until it is square (so the thumbnail is not distorted)
     * and padded so the joint is not cut at its edge. Every step is clamped to
     * the photo, and any missing piece (no capture, no landmarks, no GD)
     * returns NULL so the table simply drops the column for that row.
     */
    private function findingCrop(PostureAssessment $assessment, string $section): ?string
    {
        [$view, $indices, $pad] = self::FINDING_CROPS[$section] ?? [null, [], 0.0];

        if ($view === null || $indices === [] || ! function_exists('imagecreatefromstring')) {
            return null;
        }

        $image = $assessment->images->where('view', $view)->sortBy('order_index')->last();

        if (! $image || ! $image->image_path || ! Storage::disk('public')->exists($image->image_path)) {
            return null;
        }

        $landmarks = (array) ($image->landmarks ?? []);
        $points = [];

        foreach ($indices as $index) {
            $point = $landmarks[$index] ?? null;
            if ($point === null || ! isset($point['x'], $point['y'])) {
                continue;
            }
            if (((float) ($point['visibility'] ?? 1.0)) < 0.3) {
                continue;
            }
            $points[] = [(float) $point['x'], (float) $point['y']];
        }

        if (count($points) < 2) {
            return null;
        }

        $source = @imagecreatefromstring(Storage::disk('public')->get($image->image_path));

        if ($source === false) {
            return null;
        }

        $width = imagesx($source);
        $height = imagesy($source);

        $xs = array_column($points, 0);
        $ys = array_column($points, 1);

        $minX = min($xs) * $width;
        $maxX = max($xs) * $width;
        $minY = min($ys) * $height;
        $maxY = max($ys) * $height;

        // Square the box around its centre, then pad, then clamp to the photo.
        $side = max($maxX - $minX, $maxY - $minY) * (1 + ($pad * 2));
        $side = max($side, 24.0);

        $centreX = ($minX + $maxX) / 2;
        $centreY = ($minY + $maxY) / 2;

        $left = (int) round($centreX - ($side / 2));
        $top = (int) round($centreY - ($side / 2));
        $size = (int) round($side);

        if ($left < 0) {
            $size += $left;
            $left = 0;
        }
        if ($top < 0) {
            $size += $top;
            $top = 0;
        }
        $size = min($size, $width - $left, $height - $top);

        if ($size < 8) {
            imagedestroy($source);

            return null;
        }

        $crop = imagecrop($source, [
            'x' => $left,
            'y' => $top,
            'width' => $size,
            'height' => $size,
        ]);

        imagedestroy($source);

        if ($crop === false) {
            return null;
        }

        $canvas = imagecreatetruecolor(self::FINDING_CROP_PX, self::FINDING_CROP_PX);
        imagecopyresampled($canvas, $crop, 0, 0, 0, 0, self::FINDING_CROP_PX, self::FINDING_CROP_PX, $size, $size);
        imagedestroy($crop);

        // Draw the reference plumb and the measured landmark chain on top, so
        // the reader can see which points the angle was built from.
        $this->annotateFindingCrop($canvas, $points, $left, $top, $size, $width, $height);

        ob_start();
        imagejpeg($canvas, null, 82);
        $jpeg = (string) ob_get_clean();
        imagedestroy($canvas);

        return $jpeg === '' ? null : 'data:image/jpeg;base64,'.base64_encode($jpeg);
    }

    /**
     * Draw the measurement geometry on a finding crop.
     *
     * A bare photo crop still leaves the reader guessing which points were
     * used. This paints the same two things the full-plate overlay uses, in the
     * same colours the report legend already explains:
     *
     *   green dashed  — the vertical plumb line through the base landmark, i.e.
     *                   the reference the deviation is measured against
     *   blue line     — the measured landmark chain (the "bone segment")
     *   blue pins     — the landmarks the angle is built from, ringed in white
     *                   so they stay visible against skin and clothing
     *
     * @param  array<int, array{0: float, 1: float}>  $points  normalised (0..1) source coords
     */
    private function annotateFindingCrop(
        \GdImage $canvas,
        array $points,
        int $left,
        int $top,
        int $size,
        int $sourceWidth,
        int $sourceHeight,
    ): void {
        $px = self::FINDING_CROP_PX;

        $toCanvas = function (array $point) use ($left, $top, $size, $sourceWidth, $sourceHeight, $px): array {
            $x = (($point[0] * $sourceWidth) - $left) / $size * $px;
            $y = (($point[1] * $sourceHeight) - $top) / $size * $px;

            return [$x, $y];
        };

        $mapped = array_map($toCanvas, $points);
        $base = end($mapped);

        // Annotation sizes are proportional to the canvas so the pins and lines
        // stay readable whether the crop is rendered at 200px or larger.
        $scale = $px / 200;

        $green = imagecolorallocate($canvas, 46, 204, 64);
        $blue = imagecolorallocate($canvas, 37, 99, 235);
        $white = imagecolorallocate($canvas, 255, 255, 255);

        // Green plumb through the base landmark (dashed, like the plate).
        $dash = (int) round(7 * $scale);
        for ($y = 0; $y < $px; $y += $dash * 2) {
            imageline($canvas, (int) $base[0], $y, (int) $base[0], min($px - 1, $y + $dash), $green);
        }

        // Blue measured chain, drawn as a white-edged strip so it reads on any
        // background.
        imagesetthickness($canvas, (int) round(5 * $scale));
        foreach (array_slice($mapped, 0, -1) as $i => $start) {
            $end = $mapped[$i + 1];
            imageline($canvas, (int) $start[0], (int) $start[1], (int) $end[0], (int) $end[1], $white);
        }
        imagesetthickness($canvas, (int) round(3 * $scale));
        foreach (array_slice($mapped, 0, -1) as $i => $start) {
            $end = $mapped[$i + 1];
            imageline($canvas, (int) $start[0], (int) $start[1], (int) $end[0], (int) $end[1], $blue);
        }
        imagesetthickness($canvas, 1);

        // Pins: white halo, blue core.
        $halo = (int) round(15 * $scale);
        $core = (int) round(9 * $scale);
        foreach ($mapped as $point) {
            $x = (int) round($point[0]);
            $y = (int) round($point[1]);

            imagefilledellipse($canvas, $x, $y, $halo, $halo, $white);
            imagefilledellipse($canvas, $x, $y, $core, $core, $blue);
        }
    }

    /**
     * Degree values are printed the way the reference report does it — a
     * trailing `.0` is dropped, so `1.0` becomes `1`.
     */
    private function formatDegree(mixed $value): string
    {
        return rtrim(rtrim(number_format((float) $value, 1, '.', ''), '0'), '.');
    }

    /** Same as formatDegree; named for the deviation-angle output columns. */
    private function formatDecimal(mixed $value): string
    {
        return $this->formatDegree($value);
    }

    /**
     * A signed deviation angle: `0` at the reference, `+` above it and `-`
     * below it, so the direction and magnitude of the deviation are explicit.
     */
    private function formatSignedDecimal(mixed $value): string
    {
        $value = (float) $value;

        if ($value > 0) {
            return '+'.$this->formatDegree($value);
        }

        if ($value < 0) {
            return '-'.$this->formatDegree(abs($value));
        }

        return '0';
    }
}
