<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>SPARISK Posture Report</title>
<style>
    /* The top and bottom margins hold the running page head (SATA badge +
       report title + date/byline) and the footer, both of which are painted
       onto the canvas by SpariskReportPdfService::stampChrome(). */
    @page { margin: 64pt 38pt 56pt 38pt; }

    * { box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 9.5px;
        line-height: 1.45;
        color: #0A3C50;
        margin: 0;
    }

    /* ---------- Section band ---------- */
    /* Full-width filled bar with a teal accent edge, the way the reference
       report heads each section. */
    .band {
        background: #EAF2F4;
        color: #0A3C50;
        font-size: 9px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 5px 9px;
        border-left: 3px solid #0E7C86;
        margin: 16px 0 9px 0;
    }
    .band.first { margin-top: 0; }

    /* ---------- View heading (A. FRONT, B. BACK, …) ---------- */
    .view-head {
        font-size: 12px;
        font-weight: bold;
        color: #0A3C50;
        letter-spacing: 0.3px;
        padding-bottom: 4px;
        border-bottom: 2px solid #0E7C86;
        margin: 16px 0 6px 0;
    }
    .view-head .ltr { color: #0E7C86; }
    .view-head.first { margin-top: 0; }

    .muted { color: #64748B; }
    .pb { page-break-after: always; }

    /* ---------- Report header card ---------- */
    table.profile-card {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #D7E0E5;
        background: #FFFFFF;
    }
    table.profile-card td { vertical-align: middle; padding: 12px 14px; }
    table.profile-card td.profile-main { border-right: 1px solid #E4EBEE; }
    .profile-name { font-size: 18px; font-weight: bold; line-height: 1.2; color: #0A3C50; }
    .profile-meta { font-size: 9px; color: #475569; margin-top: 4px; }
    .profile-note { font-size: 8.5px; color: #64748B; margin-top: 3px; }
    table.profile-card td.profile-score {
        width: 150pt;
        text-align: center;
        background: #F6F9FA;
    }
    .score-label {
        font-size: 7px;
        font-weight: bold;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    .score-value { font-size: 30px; font-weight: bold; line-height: 32px; color: #0A3C50; }
    .score-value .score-max { font-size: 11px; font-weight: normal; color: #64748B; }
    .score-status {
        display: inline-block;
        margin-top: 4px;
        padding: 3px 8px;
        font-size: 7px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #FFFFFF;
        border-radius: 8px;
    }

    /* ---------- Summary cards ---------- */
    table.summary-cards { width: 100%; border-collapse: separate; border-spacing: 6px; }
    table.summary-cards td {
        width: 20%;
        vertical-align: top;
        border: 1px solid #D7E0E5;
        border-top: 2px solid #0E7C86;
        background: #FFFFFF;
        padding: 7px 8px;
    }
    .summary-label {
        display: block;
        font-size: 7px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748B;
        margin-bottom: 2px;
    }
    .summary-value {
        display: block;
        font-size: 9px;
        font-weight: bold;
        line-height: 1.3;
        color: #0A3C50;
    }

    /* ---------- Reading guide ---------- */
    table.legend { width: 100%; border-collapse: collapse; border: 1px solid #E4EBEE; background: #FFFFFF; }
    table.legend td { width: 50%; vertical-align: top; padding: 9px 11px; }
    table.legend td + td { border-left: 1px solid #E4EBEE; }
    .legend-title {
        font-size: 8px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #0A3C50;
        margin-bottom: 5px;
    }
    .legend-item { font-size: 8.5px; line-height: 1.45; color: #475569; margin: 0 0 3px 0; }
    .legend-swatch {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 2px;
        margin-right: 5px;
        vertical-align: -1px;
    }

    /* ---------- Patient / detail fields ----------
       Each cell stacks a small uppercase label over its value, so the page
       reads as a clean data grid instead of a run of inline text. */
    table.fields { width: 100%; border-collapse: collapse; }
    table.fields td {
        padding: 7px 12px 7px 0;
        border-bottom: 1px solid #E4EBEE;
        vertical-align: top;
    }
    .pl {
        display: block;
        font-size: 7.5px;
        font-weight: bold;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .pv { display: block; font-size: 10px; color: #0A3C50; }

    /* ---------- Overview panel ---------- */
    .overview {
        background: #F6F9FA;
        border: 1px solid #E4EBEE;
        border-left: 3px solid #0E7C86;
        padding: 8px 11px;
    }
    .overview p { margin: 0 0 4px 0; font-size: 9.5px; }
    .overview p:last-child { margin-bottom: 0; }

    /* ---------- Data tables ---------- */
    table.data { width: 100%; border-collapse: collapse; }
    table.data th {
        font-size: 7.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0A3C50;
        background: #E7EEF1;
        text-align: left;
        padding: 6px 8px;
        border: 1px solid #D7E0E5;
    }
    table.data th.c-sec { background: #DCE9EC; }
    table.data thead { display: table-header-group; }
    table.data tr { page-break-inside: avoid; }
    table.data td {
        font-size: 9.5px;
        padding: 5px 8px;
        border: 1px solid #D7E0E5;
        vertical-align: top;
    }
    table.data tbody tr:nth-child(even) td { background: #F6F9FA; }
    .c-code { width: 12%; }
    .c-val { width: 14%; text-align: right; }
    .c-corr { width: 22%; }
    .c-meas { width: 11%; text-align: right; }
    .c-ref  { width: 13%; text-align: right; }
    .c-dev  { width: 13%; text-align: right; }
    .val { font-weight: bold; }

    /* ---------- Primary Findings photo column ----------
       A square crop of the body area the finding describes, so the reader can
       see where on the photo the measurement was taken. */
    .c-photo { width: 16%; text-align: center; }
    .finding-crop {
        display: block;
        width: 84px;
        height: 84px;
        border: 1px solid #D7E0E5;
        border-radius: 3px;
    }

    /* ---------- Posture plate (one view per page) ----------
       The photo sits full width above its measurement table, so the table
       always has the whole content width and neither element can run past the
       right margin. The photo is sized by height (dompdf scales the width
       proportionally) and the overlay box the service computes is capped to
       the content width, so a plate always fits inside one A4 content box. */
    .photo-block {
        text-align: center;
        margin-bottom: 12px;
        padding: 8px 0 4px 0;
        background: #F8FAFB;
        border: 1px solid #E4EBEE;
    }
    .plain-photo { height: 360pt; max-width: 100%; border: 1px solid #D7E0E5; }
    .plate-meta {
        margin: 0 0 8px 0;
        font-size: 8px;
        color: #64748B;
        letter-spacing: 0.2px;
    }

    /* ---------- Photo and measurement table side by side ----------
       Mirrors the reference plate: the annotated photo fills the left column
       and the measurement table the right. Two plates share a page, so the
       report keeps the reference's compact, paired reading order. */
    .plate-section { margin-bottom: 18pt; page-break-inside: avoid; }
    table.plate { width: 100%; border-collapse: collapse; }
    table.plate td.plate-photo {
        width: 60%;
        vertical-align: top;
        padding-right: 10pt;
    }
    table.plate td.plate-table { width: 40%; vertical-align: top; }
    table.plate .photo-block {
        margin-bottom: 0;
        padding: 4px 0;
    }
    table.plate .plain-photo { height: auto; max-height: 300pt; max-width: 100%; }
    .plate-note {
        margin: 6px 0 0 0;
        font-size: 7px;
        line-height: 1.4;
        color: #64748B;
    }
    table.data.compact th { font-size: 6.5px; padding: 4px 4px; }
    table.data.compact td { font-size: 8px; padding: 3px 4px; line-height: 1.3; }
    table.data.compact .c-code { width: 12%; }
    table.data.compact .c-sec { width: 30%; }
    table.data.compact .c-dev { width: 15%; }
    table.data.compact .c-corr { width: 18%; }

    /* ---------- Labels beside a posture photo ----------
       A photo is framed by two gutters of equal width: level names and their
       table codes read from the left, the stored deviations from the right.
       Every element is absolutely positioned inside one box whose size the
       service computes in points, so the labels can never drift onto the body
       or past the page margin. */
    .cm-box { position: relative; display: inline-block; text-align: left; }
    .cm-img { position: absolute; top: 0; left: 0; display: block; border: 1px solid #D7E0E5; }
    .cm-label {
        position: absolute;
        width: 52pt;
        font-size: 9px;
        font-weight: bold;
        line-height: 1.15;
        white-space: normal;
        color: #0A3C50;
        letter-spacing: 0.1px;
    }
    .cm-label-left { text-align: right; }
    .cm-label-right { text-align: left; }
    .cm-name { display: inline; }
    .cm-deg { display: block; margin-top: 1pt; font-size: 8px; font-weight: bold; color: #1D4ED8; }
    /* The measurement row code (A3, C4…) that the line ranks with, printed
       small and in the accent colour above the body part. */
    .cm-code {
        display: block;
        margin-bottom: 1pt;
        font-size: 7px;
        font-weight: bold;
        color: #0E7C86;
        letter-spacing: 0.3px;
    }
    .cm-leader { position: absolute; height: 0; border-top: 1px dashed #0E7C86; transform-origin: 0 0; }
    .cm-dot { position: absolute; width: 5px; height: 5px; border-radius: 2.5px; background: #0E7C86; }

    /* ---------- APECS pose overlay ----------
       Drawn on top of the photo as plain CSS borders so dompdf emits real
       vector operators (line, dash, fill) instead of a flattened bitmap.
       Layer order, bottom to top: photo, grid, green plumb, red alignment,
       blue segments, orange levels, markers, angle labels. */
    .ov-grid-v { position: absolute; top: 0; width: 0; border-left: 0.5px solid rgba(100,116,139,0.5); }
    .ov-grid-h { position: absolute; height: 0; border-top: 0.5px solid rgba(100,116,139,0.5); }
    .ov-plumb { position: absolute; top: 0; width: 0; border-left: 2px solid #2ECC40; }
    .ov-align { position: absolute; height: 0; border-top: 2px dashed #E74C3C; transform-origin: 0 0; }
    .ov-seg { position: absolute; height: 0; border-top: 2px solid #2563EB; transform-origin: 0 0; }
    .ov-level { position: absolute; height: 0; border-top: 2px solid #F59E0B; transform-origin: 0 0; }
    .ov-mk { position: absolute; box-sizing: border-box; width: 6pt; height: 6pt; border-radius: 3pt; background: #FFFFFF; border: 1px solid #2563EB; }
    .ov-align-tag {
        position: absolute;
        font-size: 7.5px;
        font-weight: bold;
        line-height: 10px;
        white-space: nowrap;
        color: #E74C3C;
        background: #FFFFFF;
        border: 1px solid #E74C3C;
        padding: 0 3px;
    }
    .ov-tag { position: absolute; width: 22pt; text-align: center; font-size: 7.5px; font-weight: bold; line-height: 10px; background: #FFFFFF; }
    .ov-tag-green { color: #2ECC40; border: 1px solid #2ECC40; }
    .ov-tag-red { color: #E74C3C; border: 1px solid #E74C3C; }

    /* ---------- Score-card grid ---------- */
    table.cards { width: 100%; border-collapse: separate; border-spacing: 7px; }
    table.cards td.card {
        width: 25%;
        vertical-align: top;
        border: 1px solid #D7E0E5;
        border-left: 4px solid #0E7C86;
        background: #FFFFFF;
        padding: 8px 9px;
    }
    table.cards td.card.empty { border: none; background: transparent; }
    .card-val { font-size: 14px; font-weight: bold; line-height: 15px; }
    .card-name { font-size: 9px; color: #0A3C50; margin-top: 3px; }
    .card-code { font-size: 7.5px; color: #64748B; margin-top: 5px; }

    /* ---------- Exercise cards ---------- */
    table.ex-grid { width: 100%; border-collapse: separate; border-spacing: 8px; }
    table.ex-grid tr { page-break-inside: avoid; }
    table.ex-grid td.ex-card {
        width: 50%;
        vertical-align: top;
        border: 1px solid #D7E0E5;
        border-top: 3px solid #0E7C86;
        padding: 0;
    }
    .ex-head { background: #E7F4F5; padding: 6px 9px; border-bottom: 1px solid #D7E0E5; }
    .ex-no {
        display: inline-block;
        width: 15px;
        height: 15px;
        line-height: 15px;
        text-align: center;
        border-radius: 8px;
        background: #0E7C86;
        color: #FFFFFF;
        font-size: 8px;
        font-weight: bold;
        margin-right: 6px;
    }
    .ex-name { font-size: 9.5px; font-weight: bold; color: #0A3C50; }
    .ex-photo { text-align: center; padding: 8px 9px 0 9px; }
    .ex-photo img { width: 100%; border: 1px solid #E4EBEE; }
    .ex-body { padding: 7px 9px; }
    .ex-meta { font-size: 8px; font-weight: bold; color: #0E7C86; margin-bottom: 3px; }
    .ex-note { font-size: 8.5px; color: #475569; }

    .comment { font-size: 9.5px; font-weight: bold; color: #0A3C50; margin: 10px 0 5px 0; }
    .comment-body { font-size: 9px; color: #475569; margin: 0 0 10px 0; }

    /* ---------- Student progress dashboard ----------
       Dompdf has no canvas, flexbox or transforms, so every chart below is a
       stack of fixed-height block elements. The line chart is an SVG document
       embedded through <img>; an inline <svg> loses its shapes in dompdf. */
    .skill-chart { margin-bottom: 15px; }
    .skill-chart-title { font-size: 9px; font-weight: bold; color: #0A3C50; margin: 0 0 3px 0; }
    .skill-chart-note { font-size: 8px; color: #64748B; margin: 0 0 7px 0; }
    .skill-line { width: 519pt; height: 190pt; display: block; }

    table.skill-bars { width: 100%; border-collapse: collapse; }
    table.skill-bars td { width: 25%; vertical-align: bottom; text-align: center; padding: 0 7px; }
    .skill-bar-track { height: 100pt; background: #F1F5F9; }
    .skill-bar-fill { background: #0E7C86; }
    .skill-bar-pct { font-size: 9px; font-weight: bold; color: #0E7C86; margin-top: 5px; }
    .skill-bar-label { font-size: 7.5px; font-weight: bold; color: #0A3C50; margin-top: 2px; line-height: 1.25; }
    .skill-bar-count { font-size: 7.5px; color: #64748B; margin-top: 1px; }
    .skill-bars-rule { border-top: 1px solid #CBD5E1; height: 0; }

    table.skill-score { width: 100%; border-collapse: collapse; background: #F6F9FA; border: 1px solid #E4EBEE; border-left: 3px solid #0E7C86; }
    table.skill-score td { vertical-align: top; padding: 9px 11px; }
    .skill-score-value { font-size: 26px; font-weight: bold; line-height: 28px; color: #0A3C50; }
    .skill-score-meta { font-size: 8px; color: #64748B; margin-top: 2px; }
    .skill-score-count { font-size: 8.5px; color: #0A3C50; margin-bottom: 6px; }
    .skill-score-track { height: 9pt; background: #E4EBEE; }
    .skill-score-fill { height: 9pt; background: #0E7C86; }
    .skill-score-note { font-size: 8px; color: #64748B; margin-top: 6px; }

    .star { color: #CA8A04; }

    .footnote {
        margin-top: 16px;
        padding-top: 8px;
        border-top: 1px solid #CBD5E1;
        font-size: 8px;
        line-height: 1.5;
        color: #64748B;
    }
</style>
</head>
<body>

{{-- ============================ PAGE 1 ============================ --}}
<div class="band first">Patient Details</div>
<table class="profile-card">
    <tr>
        <td class="profile-main">
            <div class="profile-name">{{ $patient['name'] }}</div>
            <div class="profile-meta">
                {{ $patient['gender'] }} · {{ $patient['age'] }} · {{ $patient['height'] }} · {{ $patient['weight'] }}
                @if ($bmi)<span class="muted">· BMI {{ $bmi['value'] }} ({{ $bmi['category'] }})</span>@endif
            </div>
            <div class="profile-note">
                Diagnosis: {{ $patient['diagnosis'] }} &nbsp;·&nbsp; Neuro profile: {{ $patient['neuro_profile'] }}
            </div>
        </td>
        <td class="profile-score">
            <div class="score-label">Overall score</div>
            <div class="score-value">{{ $assessment['score'] }}<span class="score-max"> / 100</span></div>
            <div class="score-status" style="background: {{ $assessment['status_color'] }}">{{ $assessment['status'] ?: 'Assessed' }}</div>
        </td>
    </tr>
</table>

<table class="fields" style="margin-top: 12px">
    <tr>
        <td>
            <span class="pl">Assessment Date:</span>
            <span class="pv">{{ $assessment['date'] }}{{ $assessment['time_mark'] ? ' · '.$assessment['time_mark'] : '' }}</span>
        </td>
        <td>
            <span class="pl">Captured Views:</span>
            <span class="pv">{{ $assessment['captured_views'] }} of 4</span>
        </td>
        <td>
            <span class="pl">Measured Markers:</span>
            <span class="pv">{{ $assessment['marker_count'] }}</span>
        </td>
    </tr>
</table>

<div class="band">Complete Posture Overview</div>
<div class="overview">
    @forelse ($overview as $line)
        <p>{{ $line }}</p>
    @empty
        <p class="muted">No overview available for this assessment.</p>
    @endforelse
</div>

<div class="band">Classification Summary</div>
@if ($classification['review_required'] && $classification['explanation'])
    <p class="muted" style="margin: 0 0 6px 0">{{ $classification['explanation'] }}</p>
@endif
<table class="summary-cards">
    <tr>
        <td>
            <span class="summary-label">Result</span>
            <span class="summary-value">{{ $classification['display'] }}</span>
        </td>
        <td>
            <span class="summary-label">Main Finding</span>
            <span class="summary-value">{{ $classification['main_finding'] ?: '-' }}</span>
        </td>
        <td>
            <span class="summary-label">Suspected Pattern</span>
            <span class="summary-value">{{ $classification['suspected_pattern_label'] ?: '-' }}</span>
        </td>
        <td>
            <span class="summary-label">Secondary</span>
            <span class="summary-value">{{ $classification['secondary_pattern_label'] ?: '-' }}</span>
        </td>
        <td>
            <span class="summary-label">Asymmetry</span>
            <span class="summary-value">{{ $classification['asymmetry_flag'] ? 'Detected' : 'Not detected' }}</span>
        </td>
    </tr>
</table>

<div class="band">Reading This Report</div>
<table class="legend">
    <tr>
        <td>
            <div class="legend-title">Photo overlay</div>
            <p class="legend-item"><span class="legend-swatch" style="background: #2ECC40"></span><strong>Green</strong> — vertical plumb line used as the reference.</p>
            <p class="legend-item"><span class="legend-swatch" style="background: #E74C3C"></span><strong>Red</strong> — body alignment line; its stored deviation is quoted in the tag.</p>
            <p class="legend-item"><span class="legend-swatch" style="background: #F59E0B"></span><strong>Orange</strong> — one measured level per row.</p>
            <p class="legend-item"><span class="legend-swatch" style="background: #2563EB"></span><strong>Blue</strong> — bone segments and landmark markers.</p>
            <p class="legend-item">Each label shows its row code and level name in the left gutter, with the stored deviation in the right gutter; a side profile keeps both in one gutter.</p>
        </td>
        <td>
            <div class="legend-title">Status bands</div>
            <p class="legend-item"><strong style="color: #16A34A">On Point</strong> — within the approved SATA reference band.</p>
            <p class="legend-item"><strong style="color: #CA8A04">Near Point</strong> — mild deviation, still close to the reference.</p>
            <p class="legend-item"><strong style="color: #EA580C">Off Point</strong> — moderate or severe deviation from the reference.</p>
            <p class="legend-item"><strong style="color: #2563EB">Baseline / Change Tracking</strong> — no approved population reference; the row is tracked against the patient's own first assessment.</p>
            <p class="legend-item">Measurements are screening indicators and do not replace a medical diagnosis.</p>
        </td>
    </tr>
</table>

{{-- ==================== STUDENT PROGRESS DASHBOARD ==================== --}}
{{-- One dashboard per patient, not per assessment: the report shows every
     sitting on record, taken from AquaticSkillsProgressService. --}}
@if (count($skills['sessions']))
    <div class="pb"></div>

    <div class="band first">Student Progress Dashboard</div>
    <p class="muted" style="margin: 0 0 11px 0">
        Aquatic skills checklist across {{ count($skills['sessions']) }} sitting{{ count($skills['sessions']) === 1 ? '' : 's' }}
        ({{ $skills['catalogue']['version'] }}, {{ $skills['catalogue']['item_count'] }} items).
    </p>

    <div class="skill-chart">
        <div class="skill-chart-title">1. Overall progress</div>
        <p class="skill-chart-note">Score at each sitting, oldest first.</p>
        <img class="skill-line" src="{{ $skills['chart']['uri'] }}" alt="Overall skills progress across sittings">
    </div>

    <div class="skill-chart">
        <div class="skill-chart-title">2. Progress by Assessment Category</div>
        <p class="skill-chart-note">Each category as a percentage of its own items: the four categories hold 9, 6, 9 and 11 items, so raw counts would favour the longest.</p>
        <table class="skill-bars">
            <tr>
                @foreach ($skills['categories'] as $category)
                    @php
                        $fill = max(1.5, min(100.0, round((float) $category['percentage'], 1)));
                    @endphp
                    <td>
                        <div class="skill-bar-track">
                            <div style="height: {{ 100 - $fill }}pt"></div>
                            <div class="skill-bar-fill" style="height: {{ $fill }}pt"></div>
                        </div>
                    </td>
                @endforeach
            </tr>
            <tr>
                @foreach ($skills['categories'] as $category)
                    <td>
                        <div class="skill-bar-pct">{{ $category['percentage'] }}%</div>
                        <div class="skill-bar-label">{{ $category['label'] }}</div>
                        <div class="skill-bar-count">{{ $category['achieved'] }} / {{ $category['total'] }} items</div>
                    </td>
                @endforeach
            </tr>
        </table>
        <div class="skill-bars-rule"></div>
    </div>

    <div class="skill-chart">
        <div class="skill-chart-title">3. Overall score as a percentage</div>
        <table class="skill-score">
            <tr>
                <td style="width: 32%">
                    <div class="skill-score-value">{{ $skills['current']['percentage'] }}%</div>
                    <div class="skill-score-meta">{{ $skills['current']['label'] }} · {{ $skills['current']['date'] }}</div>
                </td>
                <td>
                    <div class="skill-score-count">
                        <strong>{{ $skills['current']['achieved'] }}</strong> of {{ $skills['current']['max'] }} items achieved
                        · {{ $skills['current']['missed'] }} not achieved
                    </div>
                    <div class="skill-score-track">
                        <div class="skill-score-fill" style="width: {{ $skills['current']['percentage'] }}%"></div>
                    </div>
                    <div class="skill-score-note">
                        @if ($skills['improvement']['has_comparison'])
                            @php
                                $itemDelta = (int) $skills['improvement']['items'];
                                $pointDelta = (float) $skills['improvement']['percentage_points'];
                                $baselineLabel = $skills['improvement']['baseline']['label'] ?? 'the baseline';
                            @endphp
                            @if ($itemDelta === 0 && $pointDelta === 0.0)
                                No change since {{ $baselineLabel }}.
                            @else
                                {{ $itemDelta > 0 ? '+' : '' }}{{ $itemDelta }} items ·
                                {{ $pointDelta > 0 ? '+' : '' }}{{ $pointDelta }} percentage points since {{ $baselineLabel }}.
                            @endif
                        @else
                            First sitting recorded — a comparison appears after the next sitting.
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endif

{{-- ==================== VIEW PLATES ==================== --}}
{{-- Two views per page, following the reference report: the annotated photo
     with its gutter labels on the left, the measurement table beside it on the
     right. The photo geometry is computed by the service, so every plate is
     the same width and no label can run onto the body. --}}
<div class="pb"></div>
@php
    $plateGroups = [['front', 'back'], ['right_side', 'left_side']];
@endphp
@foreach ($plateGroups as $group)
    @foreach ($group as $key)
        @php
            $view = $views[$key];
            $image = $images[$key] ?? null;
            $upright = in_array($key, ['front', 'back'], true);
            $summary = $view['summary'];
            $statusParts = [];
            if ($summary['on_point']) {
                $statusParts[] = $summary['on_point'].' on point';
            }
            if ($summary['near_point']) {
                $statusParts[] = $summary['near_point'].' near point';
            }
            if ($summary['off_point']) {
                $statusParts[] = $summary['off_point'].' off point';
            }
            if ($summary['baseline']) {
                $statusParts[] = $summary['baseline'].' baseline';
            }
            if ($summary['review']) {
                $statusParts[] = $summary['review'].' to review';
            }
        @endphp
        <div class="plate-section">
            <div class="view-head {{ $loop->first ? 'first' : '' }}"><span class="ltr">{{ $view['letter'] }}.</span> {{ $view['name'] }}</div>
            <p class="plate-meta">
                {{ $summary['total'] }} marker{{ $summary['total'] === 1 ? '' : 's' }} measured
                @if (count($statusParts))
                    · {{ implode(' · ', $statusParts) }}
                @endif
            </p>
            <table class="plate">
                <tr>
                    <td class="plate-photo">
                        @include('pdf.partials.posture-plate', ['image' => $image, 'upright' => $upright])
                    </td>
                    <td class="plate-table">
                        @include('pdf.partials.posture-table', ['view' => $view])
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
    @if (! $loop->last)
        <div class="pb"></div>
    @endif
@endforeach
<div class="pb"></div>

{{-- ============ DETAILED ANGLE BREAKDOWN (admin panel only) ============ --}}
{{-- HIDDEN at the client's request (2026-10-26). The per-view photo plates
     with the compact table beside each image stay in the report; only this
     standalone breakdown table is hidden. To show it again, remove the
     @if(0) wrapper (replace it with @if ($extended)) and the matching @endif.
--}}
@if (0)
    <div class="band first">Detailed Angle Breakdown</div>
    <p class="muted" style="margin: 0 0 6px 0">
        Full per-view measurement detail. Every marker lists the measured anatomical
        angle, the SATA reference applied (with its reference level), the resulting
        deviation angle and the clinical note.
    </p>

    @foreach ($views as $key => $view)
        @if (count($view['rows']))
            <div class="view-head {{ $loop->first ? 'first' : '' }}"><span class="ltr">{{ $view['letter'] }}.</span> {{ $view['name'] }} — Angle Breakdown</div>
            <table class="data">
                <thead>
                    <tr>
                        <th class="c-code">Marker</th>
                        <th class="c-sec">Parameter</th>
                        <th class="c-meas">Measured</th>
                        <th class="c-ref">Reference</th>
                        <th class="c-dev">Deviation</th>
                        <th>Ref. Level</th>
                        <th class="c-sec">Status</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($view['rows'] as $row)
                        <tr>
                            <td>{{ $row['code'] !== '' ? $row['code'] : '—' }}</td>
                            <td>
                                {{ $row['name'] }}
                            </td>
                            <td class="c-meas">{{ $row['measured'] }}°</td>
                            <td class="c-ref">{{ $row['reference'] !== null ? $row['reference'].'°' : 'Baseline' }}</td>
                            <td class="c-dev val" style="color: {{ $row['color'] }}">{{ $row['deviation'] !== null ? $row['deviation'].'°' : '—' }}</td>
                            <td>{{ $row['reference_level'] ?: ($row['is_baseline'] ? 'Individual Baseline' : '-') }}</td>
                            <td style="color: {{ $row['color'] }}">{{ $row['status'] }}</td>
                            <td class="muted">{{ $row['note'] ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (! $loop->last)
                <div class="pb"></div>
            @endif
        @endif
    @endforeach

    <div class="pb"></div>
@endif

{{-- ==================== SCORE-CARD GRID PAGES ==================== --}}
{{-- Hidden for now — kept for later. Remove this comment pair to show the
     4-up score-card grid page for each view again.
@foreach ($views as $key => $view)
    <div class="band first">{{ $view['letter'] }}. {{ $view['name'] }}</div>
    <table class="cards">
        @foreach (array_chunk($view['rows'], 4) as $chunk)
            <tr>
                @foreach ($chunk as $row)
                    <td class="card">
                        <div class="card-val" style="color: {{ $row['color'] }}">{{ $row['value'] }}°</div>
                        <div class="card-name">{{ $row['name'] }}</div>
                        <div class="card-code">{{ $row['code'] }}</div>
                    </td>
                @endforeach
                @for ($i = count($chunk); $i < 4; $i++)
                    <td class="card empty"></td>
                @endfor
            </tr>
        @endforeach
    </table>
    @if (! $loop->last)
        <div class="pb"></div>
    @endif
@endforeach

<div class="pb"></div>
--}}

{{-- ==================== CLASSIFICATION & FINDINGS ==================== --}}
{{-- The result and its pattern are summarised on page one; this page carries
     the supporting detail, the primary findings and the symmetry check. --}}
@if (count($classification['classifications']))
    <div class="band first">Classification Detail</div>
    <table class="data">
        <thead>
            <tr><th>Classification</th><th class="c-sec">Type</th><th class="c-sec">Alignment</th></tr>
        </thead>
        <tbody>
            @foreach ($classification['classifications'] as $item)
                <tr>
                    <td>
                        {{ $item['classification_label'] ?? str_replace('_', ' ', $item['classification_name']) }}
                        @if (! empty($item['description']))
                            <div class="muted">{{ $item['description'] }}</div>
                        @endif
                    </td>
                    <td>{{ ucfirst($item['classification_type'] ?? '-') }}</td>
                    <td style="color: {{ \App\Services\Sparisk\SpariskReportPdfService::hex($item['alignment_color'] ?? null) }}">
                        {{ $item['alignment_label'] ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@php
    $detailFirst = count($classification['classifications']) === 0;
@endphp

@if (count($findings))
    <div class="band {{ $detailFirst ? 'first' : '' }}">Primary Findings</div>
    <table class="data findings">
        <thead>
            <tr><th class="c-photo">Photo</th><th>Finding</th><th class="c-sec">Note</th><th class="c-val">Value</th><th class="c-sec">Alignment</th></tr>
        </thead>
        <tbody>
            @foreach ($findings as $finding)
                <tr>
                    <td class="c-photo">
                        @if (! empty($finding['crop']))
                            <img class="finding-crop" src="{{ $finding['crop'] }}" alt="{{ $finding['name'] }}">
                        @else
                            <span class="muted">—</span>
                        @endif
                    </td>
                    <td>{{ $finding['name'] }}</td>
                    <td>{{ $finding['note'] ?: '-' }}</td>
                    <td class="val" style="color: {{ $finding['color'] }}">{{ $finding['value'] }}°</td>
                    <td style="color: {{ $finding['color'] }}">{{ $finding['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if (count($symmetry))
    <div class="band {{ $detailFirst && count($findings) === 0 ? 'first' : '' }}">Left / Right Symmetry</div>
    <table class="data">
        <thead>
            <tr><th>Pair</th><th class="c-val">Left</th><th class="c-val">Right</th><th class="c-sec">Status</th></tr>
        </thead>
        <tbody>
            @foreach ($symmetry as $pair)
                @php $hex = \App\Services\Sparisk\SpariskReportPdfService::hex($pair['status_color']); @endphp
                <tr>
                    <td>{{ $pair['label'] }}</td>
                    <td class="c-val">{{ rtrim(rtrim(number_format($pair['left_value'], 1), '0'), '.') }}°</td>
                    <td class="c-val">{{ rtrim(rtrim(number_format($pair['right_value'], 1), '0'), '.') }}°</td>
                    <td style="color: {{ $hex }}">{{ $pair['status_label'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<div class="pb"></div>

@if (count($massages))
    <div class="band first">Massage Therapy Plan</div>
    <table class="data">
        <thead>
            <tr><th>Body Area</th><th class="c-val">Priority</th><th class="c-val">Duration</th><th class="c-sec">Frequency</th><th>Instructions</th></tr>
        </thead>
        <tbody>
            @foreach ($massages as $massage)
                <tr>
                    <td>{{ $massage->body_area }}</td>
                    <td class="c-val star">{{ str_repeat('★', (int) $massage->priority_stars) }}</td>
                    <td class="c-val">{{ $massage->duration_minutes }} min</td>
                    <td>{{ $massage->frequency ?: '-' }}</td>
                    <td>{{ $massage->instructions ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if (count($weekly))
    <div class="band">Weekly Program</div>
    <table class="data">
        <thead>
            <tr><th style="width: 16%">Day</th><th style="width: 30%">Activity</th><th>Details</th><th class="c-val">Duration</th></tr>
        </thead>
        <tbody>
            @foreach ($weekly as $program)
                <tr>
                    <td>{{ ucfirst($program->day_of_week) }}</td>
                    <td>{{ $program->activity_title ?: ucfirst(str_replace('_', ' ', (string) $program->activity_type)) }}</td>
                    <td>{{ $program->activity_details ?: '-' }}</td>
                    <td class="c-val">{{ $program->duration_minutes ? $program->duration_minutes.' min' : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

{{-- ==================== SELECTED EXERCISES ==================== --}}
{{-- Follows the therapy plan on the same page when there is room, so the
     report does not end with a mostly empty sheet. --}}
@php
    $hasPlans = count($massages) > 0 || count($weekly) > 0;
@endphp

<div class="band {{ $hasPlans ? '' : 'first' }}">Selected Exercises</div>
<p class="muted" style="margin: 0 0 4px 0">
    Exercises selected for this assessment. Use the SPARISK app to track sets, reps and progress.
</p>

@if (! empty($assessment['clinical_summary']))
    <div class="comment">Clinical Comment</div>
    <p class="comment-body">{{ $assessment['clinical_summary'] }}</p>
@endif

@if (count($exercises))
    <table class="ex-grid">
        @foreach (array_chunk($exercises, 2) as $chunk)
            <tr>
                @foreach ($chunk as $exercise)
                    <td class="ex-card">
                        <div class="ex-head">
                            <span class="ex-no">{{ $exercise['no'] }}</span>
                            <span class="ex-name">{{ $exercise['name'] }}</span>
                        </div>
                        @if ($exercise['image'])
                            <div class="ex-photo">
                                <img src="{{ $exercise['image'] }}" alt="{{ $exercise['name'] }}">
                            </div>
                        @endif
                        <div class="ex-body">
                            <div class="ex-meta">{{ $exercise['meta'] }}</div>
                            <div class="ex-note">{{ $exercise['note'] }}</div>
                        </div>
                    </td>
                @endforeach
                @if (count($chunk) < 2)
                    <td class="ex-card" style="border: none"></td>
                @endif
            </tr>
        @endforeach
    </table>
@else
    <p class="muted">No exercise recommendations for this assessment.</p>
@endif

<div class="footnote">
    This report was generated by SPARISK — the SATA posture screening &amp; aquatic therapy platform.
    The measurements are screening indicators and are not a medical diagnosis.
    Report generated on {{ $generated_at }}.
</div>

</body>
</html>
