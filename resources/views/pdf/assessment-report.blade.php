<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>SPARISK Posture Report</title>
<style>
    /* The top and bottom margins hold the running page head (centred title +
       date/byline) and the footer, both of which are painted onto the canvas by
       SpariskReportPdfService::stampChrome(). */
    @page { margin: 60px 38px 58px 38px; }

    * { box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 9.5px;
        color: #0A3C50;
        margin: 0;
    }

    /* ---------- Section band ---------- */
    /* Full-width filled bar, the way the reference report heads each section. */
    .band {
        background: #DCE9EC;
        color: #0A3C50;
        font-size: 9px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 4px 8px;
        margin: 13px 0 8px 0;
    }
    .band.first { margin-top: 0; }

    /* ---------- View heading (A. FRONT, B. BACK, …) ---------- */
    .view-head {
        font-size: 11px;
        font-weight: bold;
        color: #0A3C50;
        margin: 13px 0 8px 0;
    }
    .view-head .ltr { color: #0E7C86; }
    .view-head.first { margin-top: 0; }

    .muted { color: #64748B; }
    .pb { page-break-after: always; }

    /* ---------- Patient / detail fields ---------- */
    table.fields { width: 100%; border-collapse: collapse; }
    table.fields td {
        padding: 5px 10px 5px 0;
        border-bottom: 1px solid #E4EBEE;
        vertical-align: top;
    }
    .pl {
        font-size: 8px;
        font-weight: bold;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .pv { font-size: 9.5px; color: #0A3C50; }

    /* ---------- Overview lines ---------- */
    .overview p { margin: 0 0 3px 0; font-size: 9.5px; }

    /* ---------- Data tables (Label / Section / Value) ---------- */
    table.data { width: 100%; border-collapse: collapse; }
    table.data th {
        font-size: 7.5px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #0A3C50;
        background: #E7EEF1;
        text-align: left;
        padding: 5px 8px;
        border: 1px solid #D7E0E5;
    }
    table.data th.c-sec { background: #D6E7EA; }
    table.data td {
        font-size: 9.5px;
        padding: 4px 8px;
        border: 1px solid #D7E0E5;
        vertical-align: top;
    }
    table.data tbody tr:nth-child(even) td { background: #F6F9FA; }
    .c-code { width: 12%; }
    .c-val { width: 14%; text-align: right; }
    .val { font-weight: bold; }

    /* ---------- Photo + table split (A./B. pages) ---------- */
    table.split { width: 100%; border-collapse: collapse; page-break-inside: avoid; }
    table.split > tbody > tr > td { vertical-align: top; }
    td.photo { width: 42%; padding-right: 10px; text-align: center; }
    /* The posture photos are tall, narrow crops, so they are sized by height
       (dompdf scales the width proportionally) to fill the page. */
    td.photo img { height: 470pt; border: 1px solid #E4EBEE; }

    /* ---------- Two views side by side (C./D. page) ---------- */
    table.two-col { width: 100%; border-collapse: collapse; }
    table.two-col > tbody > tr > td { width: 50%; vertical-align: top; padding-right: 9px; }
    table.two-col > tbody > tr > td + td { padding-right: 0; padding-left: 9px; }
    .side-photo { text-align: center; margin-bottom: 7px; }
    .side-photo img { height: 470pt; border: 1px solid #E4EBEE; }

    /* ---------- Anatomical labels on a posture photo ----------
       A label gutter sits to the left of the photo; the photo, the labels and
       the dashed leader lines pointing at each body region are all absolutely
       positioned inside one box whose size the service computes in points. */
    .cm-box { position: relative; display: inline-block; text-align: left; }
    .cm-img { position: absolute; top: 0; left: 0; display: block; border: 1px solid #E4EBEE; }
    .cm-label {
        position: absolute;
        left: 0;
        width: 58pt;
        text-align: right;
        font-size: 11px;
        font-weight: bold;
        line-height: 1;
        white-space: nowrap;
        color: #0A3C50;
        letter-spacing: 0.2px;
    }
    /* The measurement row code (A3, C4…) that the line ranks with, printed
       small and in the accent colour on the line above the body part, so a
       long name never wraps into the leader line. */
    .cm-code { display: block; font-size: 8px; color: #0E7C86; letter-spacing: 0.3px; }
    .cm-leader { position: absolute; height: 0; border-top: 2px dashed #0E7C86; }
    .cm-dot { position: absolute; width: 6px; height: 6px; border-radius: 3px; background: #0E7C86; }

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
    table.ex-grid { width: 100%; border-collapse: separate; border-spacing: 7px; }
    table.ex-grid td.ex-card {
        width: 50%;
        vertical-align: top;
        border: 1px solid #D7E0E5;
        padding: 0;
    }
    .ex-head { background: #E7F4F5; padding: 5px 8px; border-bottom: 1px solid #D7E0E5; }
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
    .ex-photo { text-align: center; padding: 7px 8px 0 8px; }
    .ex-photo img { width: 100%; border: 1px solid #E4EBEE; }
    .ex-body { padding: 6px 8px; }
    .ex-meta { font-size: 8px; color: #0E7C86; margin-bottom: 3px; }
    .ex-note { font-size: 8.5px; color: #475569; }

    .comment { font-size: 9.5px; font-weight: bold; color: #0A3C50; margin: 8px 0 4px 0; }

    .star { color: #CA8A04; }

    .footnote {
        margin-top: 14px;
        padding-top: 6px;
        border-top: 1px solid #CBD5E1;
        font-size: 8px;
        color: #64748B;
    }
</style>
</head>
<body>

{{-- ============================ PAGE 1 ============================ --}}
<div class="band first">Patient Details</div>
<table class="fields">
    <tr>
        <td><span class="pl">Sex:</span> <span class="pv">{{ $patient['gender'] }}</span></td>
        <td><span class="pl">Age:</span> <span class="pv">{{ $patient['age'] }}</span></td>
        <td><span class="pl">Name:</span> <span class="pv">{{ $patient['name'] }}</span></td>
    </tr>
    <tr>
        <td><span class="pl">Height:</span> <span class="pv">{{ $patient['height'] }}</span></td>
        <td><span class="pl">Weight:</span> <span class="pv">{{ $patient['weight'] }}</span></td>
        <td><span class="pl">BMI:</span> <span class="pv">{{ $bmi ? $bmi['value'].' · '.$bmi['category'] : '-' }}</span></td>
    </tr>
    <tr>
        <td colspan="2"><span class="pl">Diagnosis:</span> <span class="pv">{{ $patient['diagnosis'] }}</span></td>
        <td><span class="pl">Neuro Profile:</span> <span class="pv">{{ $patient['neuro_profile'] }}</span></td>
    </tr>
    <tr>
        <td colspan="2"><span class="pl">Overall Score:</span> <span class="pv">{{ $assessment['score'] }} / 100 · {{ $assessment['status'] ?: 'Assessed' }}</span></td>
        <td><span class="pl">Classification:</span> <span class="pv">{{ $classification['display'] }}</span></td>
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

{{-- ==================== A. FRONT / B. BACK ==================== --}}
@foreach (['front', 'back'] as $key)
    <div class="view-head first"><span class="ltr">{{ $views[$key]['letter'] }}.</span> {{ $views[$key]['name'] }}</div>
    <table class="split">
        <tr>
            @isset ($images[$key])
                <td class="photo">
                    @if (empty($images[$key]['levels']))
                        <img src="{{ $images[$key]['data'] }}" alt="{{ $images[$key]['label'] }}">
                    @else
                        <div class="cm-box" style="width: {{ $images[$key]['box_w'] }}pt; height: {{ $images[$key]['box_h'] }}pt">
                            <img class="cm-img" style="left: {{ $images[$key]['img_left'] }}pt; width: {{ $images[$key]['img_w'] }}pt; height: {{ $images[$key]['box_h'] }}pt" src="{{ $images[$key]['data'] }}" alt="{{ $images[$key]['label'] }}">
                            @foreach ($images[$key]['levels'] as $level)
                                <div class="cm-label" style="left: {{ $level['label_left'] }}pt; top: {{ round($level['y'] - ($level['code'] !== '' ? 7 : 4), 1) }}pt; text-align: {{ $level['label_align'] }}">@if ($level['code'] !== '')<span class="cm-code">{{ $level['code'] }}</span>@endif{{ $level['label'] }}</div>
                                <div class="cm-leader" style="left: {{ $level['x1'] }}pt; top: {{ $level['y'] }}pt; width: {{ round($level['x2'] - $level['x1'], 1) }}pt"></div>
                                <div class="cm-dot" style="left: {{ $level['dot_left'] }}pt; top: {{ $level['dot_top'] }}pt"></div>
                            @endforeach
                        </div>
                    @endif
                </td>
            @endisset
            <td>
                <table class="data">
                    <thead>
                        <tr>
                            <th class="c-code">Label</th>
                            <th class="c-sec">Section</th>
                            <th class="c-val">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($views[$key]['rows'] as $row)
                            <tr>
                                <td>{{ $row['code'] }}</td>
                                <td>{{ $row['name'] }}</td>
                                <td class="val" style="color: {{ $row['color'] }}">{{ $row['value'] }}°</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="muted">No measurements captured for this view.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <div class="pb"></div>
@endforeach

{{-- ==================== C. RIGHT SIDE / D. LEFT SIDE ==================== --}}
<table class="two-col">
    <tr>
        @foreach (['right_side', 'left_side'] as $key)
            <td>
                <div class="view-head first"><span class="ltr">{{ $views[$key]['letter'] }}.</span> {{ $views[$key]['name'] }}</div>
                @isset ($images[$key])
                    <div class="side-photo">
                        @if (empty($images[$key]['levels']))
                            <img src="{{ $images[$key]['data'] }}" alt="{{ $images[$key]['label'] }}">
                        @else
                            <div class="cm-box" style="width: {{ $images[$key]['box_w'] }}pt; height: {{ $images[$key]['box_h'] }}pt">
                                <img class="cm-img" style="left: {{ $images[$key]['img_left'] }}pt; width: {{ $images[$key]['img_w'] }}pt; height: {{ $images[$key]['box_h'] }}pt" src="{{ $images[$key]['data'] }}" alt="{{ $images[$key]['label'] }}">
                                @foreach ($images[$key]['levels'] as $level)
                                    <div class="cm-label" style="left: {{ $level['label_left'] }}pt; top: {{ round($level['y'] - ($level['code'] !== '' ? 7 : 4), 1) }}pt; text-align: {{ $level['label_align'] }}">@if ($level['code'] !== '')<span class="cm-code">{{ $level['code'] }}</span>@endif{{ $level['label'] }}</div>
                                    <div class="cm-leader" style="left: {{ $level['x1'] }}pt; top: {{ $level['y'] }}pt; width: {{ round($level['x2'] - $level['x1'], 1) }}pt"></div>
                                    <div class="cm-dot" style="left: {{ $level['dot_left'] }}pt; top: {{ $level['dot_top'] }}pt"></div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endisset
                <table class="data">
                    <thead>
                        <tr>
                            <th class="c-code">Label</th>
                            <th class="c-sec">Section</th>
                            <th class="c-val">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($views[$key]['rows'] as $row)
                            <tr>
                                <td>{{ $row['code'] }}</td>
                                <td>{{ $row['name'] }}</td>
                                <td class="val" style="color: {{ $row['color'] }}">{{ $row['value'] }}°</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="muted">No measurements.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        @endforeach
    </tr>
</table>

<div class="pb"></div>

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
<div class="band first">Posture Classification</div>
@if ($classification['review_required'])
    <p class="muted" style="margin: 0 0 6px 0">
        The measurements currently available are not sufficient to confirm a posture type.
        No posture diagnosis has been made.
    </p>
@endif
<table class="fields">
    <tr>
        <td><span class="pl">Result:</span> <span class="pv">{{ $classification['display'] }}</span></td>
    </tr>
    <tr>
        <td><span class="pl">Main Finding:</span> <span class="pv">{{ $classification['suspected_pattern_label'] ?: '-' }}</span></td>
    </tr>
    <tr>
        <td><span class="pl">Secondary:</span> <span class="pv">{{ $classification['secondary_pattern_label'] ?: '-' }}</span></td>
    </tr>
    <tr>
        <td><span class="pl">Asymmetry:</span> <span class="pv">{{ $classification['asymmetry_flag'] ? 'Detected' : 'Not detected' }}</span></td>
    </tr>
    <tr>
        <td><span class="pl">Confidence:</span> <span class="pv">{{ $classification['confidence_label'] ?: '-' }}</span></td>
    </tr>
</table>

@if (count($classification['classifications']))
    <div class="band">Classification Detail</div>
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

@if (count($findings))
    <div class="band">Primary Findings</div>
    <table class="data">
        <thead>
            <tr><th>Finding</th><th class="c-sec">Note</th><th class="c-val">Value</th><th class="c-sec">Alignment</th></tr>
        </thead>
        <tbody>
            @foreach ($findings as $finding)
                <tr>
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
    <div class="band">Left / Right Symmetry</div>
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

{{-- ==================== SELECTED EXERCISES (last page) ==================== --}}
<div class="pb"></div>

<div class="band first">Selected Exercises</div>
<p class="muted" style="margin: 0 0 4px 0">
    Exercises selected for this assessment. Use the SPARISK app to track sets, reps and progress.
</p>
<div class="comment">Comment:</div>

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
