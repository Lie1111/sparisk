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
    private const PHOTO_HEIGHT = 430.0;

    private const PHOTO_GUTTER = 62.0;

    /** Label width, the gap before a leader line, and the dot that ends it (pt). */
    private const LABEL_WIDTH = 58.0;

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
        'front' => ['Head' => 'A2', 'Shoulders' => 'A3', 'Core' => 'A6', 'Knees' => 'A8/A9', 'Feet' => 'A10/A11'],
        'back' => ['Neck' => 'B2', 'Shoulders' => 'B3', 'Lower Back' => 'B6', 'Knees' => 'B7', 'Feet' => 'B8'],
        'right_side' => ['Head' => 'C2', 'Chest' => 'C3', 'Hip' => 'C4', 'Thigh' => 'C5', 'Calf' => 'C6'],
        'left_side' => ['Head' => 'D2', 'Chest' => 'D3', 'Hip' => 'D4', 'Thigh' => 'D5', 'Calf' => 'D6'],
    ];

    public function __construct(
        private SpariskInterpretationEngine $interpreter,
        private SpariskBmiEngine $bmiEngine,
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
     */
    public function download(PostureAssessment $assessment): Response
    {
        $data = $this->buildData($assessment);
        $pdf = $this->make($data);
        $this->stampChrome($pdf, $data);

        return $pdf->download($this->filename($assessment));
    }

    /**
     * Serve the report inline, so a browser can preview it.
     */
    public function stream(PostureAssessment $assessment): Response
    {
        $data = $this->buildData($assessment);
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
            $grey = [0.392, 0.455, 0.545];
            $left = 28.5;
            $pageWidth = 595.28;

            $title = 'POSTURE REPORT — Full Posture';
            $titleSize = 12.5;
            $dateLine = 'Date: '.$data['assessment']['date'];
            $byLine = 'Generated by: SPARISK · '.$data['generated_at'];

            // Canvas::text() anchors the *top* of the line box at $y and the box
            // is ~1.16× the font size tall, so the y values below leave
            // deliberate gaps rather than colliding.
            $titleX = max($left, ($pageWidth - $metrics->getTextWidth($title, $bold, $titleSize)) / 2);

            $canvas->page_script(function ($pageNumber, $pageCount, $canvas) use (
                $regular, $bold, $dark, $grey, $left,
                $title, $titleX, $titleSize, $dateLine, $byLine
            ) {
                // Page head (in the top page margin): centred title + date/byline.
                $canvas->text($titleX, 10, $title, $bold, $titleSize, $dark, 0, 0);
                $canvas->text($left, 28, $dateLine, $bold, 8.5, $dark, 0, 0);
                $canvas->text(150, 28, $byLine, $regular, 8.5, $grey, 0, 0);

                // Footer (in the bottom page margin).
                $canvas->text($left, 804, 'SPARISK · SATA NeuroPosture', $regular, 7, $grey, 0, 0);
                $canvas->text(508, 804, "Page {$pageNumber} of {$pageCount}", $regular, 7, $grey, 0, 0);
            });
        } catch (\Throwable) {
            // The chrome is cosmetic; it must never break the download.
        }
    }

    /**
     * Assemble everything the Blade template needs.
     */
    private function buildData(PostureAssessment $assessment): array
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

        return [
            'generated_at' => now()->format('d/m/Y H:i'),
            'patient' => $this->patientData($assessment),
            'bmi' => $this->bmiData($assessment),
            'assessment' => [
                'date' => $assessment->assessment_date?->format('d/m/Y') ?? '-',
                'time_mark' => $assessment->time_mark,
                'score' => (int) $assessment->overall_score,
                'status' => $assessment->overall_status,
                'clinical_summary' => $assessment->clinical_summary,
            ],
            'overview' => $this->overviewSentences($assessment),
            'views' => $this->viewData($assessment, $statuses),
            'images' => $this->imageData($assessment),
            'classification' => $this->interpreter->classificationSummary($assessment),
            'symmetry' => $this->interpreter->generateSymmetry($assessment),
            'findings' => $this->primaryFindings($assessment, $statuses),
            'exercises' => $this->exerciseData($assessment->exerciseRecommendations),
            'massages' => $assessment->massageRecommendations,
            'weekly' => $assessment->weeklyPrograms,
        ];
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
            $classification['suspected_pattern_label'] ? 'Main finding: '.$classification['suspected_pattern_label'].'.' : null,
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
                ->map(function (PostureMeasurement $m) use ($statuses, $lines) {
                    $status = $m->review_required ? 'review' : ($m->alignment_status ?? $m->severity ?? 'normal');
                    $statusMeta = $statuses[$status] ?? $statuses['normal'];
                    $label = $m->label ?: (config("sparisk.measurements.{$m->section}.label") ?? $m->section);
                    $line = $lines[(string) $m->section] ?? '';

                    // Naming the row the way the photo labels it lets a reader
                    // find "Hip ( Estimated Pelvic Alignment )" from the line
                    // that says "C4 Hip". When the line already carries the
                    // row's own name the two would only repeat, so it prints
                    // once ("Knees", not "Knees ( Knees )").
                    $name = $line !== '' && strcasecmp($line, $label) !== 0
                        ? $line.' ( '.$label.' )'
                        : $label;

                    return [
                        'code' => preg_match('/^[A-Z]\d+$/', (string) $m->section) ? $m->section : '',
                        'name' => $name,
                        'value' => $this->formatDegree($m->value),
                        'status' => $statusMeta['label'],
                        'color' => self::STATUS_HEX[$statusMeta['color']] ?? self::STATUS_HEX['grey'],
                        'note' => $m->position_note ?: $m->status_text,
                    ];
                })
                ->all();

            $views[$key] = [
                'letter' => $meta['letter'],
                'name' => $meta['name'],
                'rows' => $rows,
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

            $images[$key] = [
                'label' => $names[$key],
                'data' => 'data:'.$mime.';base64,'.base64_encode(Storage::disk('public')->get($image->image_path)),
            ] + $this->photoOverlay($image, $key);
        }

        return $images;
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
    private function photoOverlay(AssessmentImage $image, string $view): array
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

        $rows = [];
        $hasRight = false;

        foreach ($zones as $label => $zone) {
            // A region sitting a little past the midline of the photo belongs
            // to the right gutter; anything on or before it reads from the
            // left, so the paired front/back zones stay together.
            $right = ($zone['left'] + $zone['right']) / 2 - 0.5 > 0.03;
            $hasRight = $hasRight || $right;

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

        $boxW = round($gutter + $width + ($hasRight ? $gutter : 0), 1);
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
            'levels' => $levels,
        ];
    }

    private function primaryFindings(PostureAssessment $assessment, array $statuses): array
    {
        $findings = $this->interpreter->generatePrimaryFindings($assessment);

        return array_map(function (array $finding) use ($statuses) {
            $statusMeta = $statuses[$finding['alignment_status']] ?? $statuses['normal'];

            return [
                'name' => $finding['label'] ?: $finding['section'],
                'value' => $this->formatDegree($finding['value']),
                'status' => $statusMeta['label'],
                'color' => self::STATUS_HEX[$statusMeta['color']] ?? self::STATUS_HEX['grey'],
                'note' => $finding['interpretation'] ?: $finding['position_note'],
            ];
        }, $findings);
    }

    /**
     * Degree values are printed the way the reference report does it — a
     * trailing `.0` is dropped, so `1.0` becomes `1`.
     */
    private function formatDegree(mixed $value): string
    {
        return rtrim(rtrim(number_format((float) $value, 1, '.', ''), '0'), '.');
    }
}
