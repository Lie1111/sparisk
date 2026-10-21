{{--
    The gutter labels beside a posture photo.

    When the captured image has pose landmarks, the labels come from the same
    level lines the overlay draws: the level name (with the measurement row
    code) in the left gutter and the stored deviation in the right one. Both
    columns sit outside the body, and the service has already pushed the rows
    apart so no two boxes overlap.

    Images without landmarks fall back to the anatomical highlight labels from
    SpariskReportPdfService::photoOverlay().
--}}
@php
    $ov = $photo['overlay'] ?? [];
    $labels = $ov['labels'] ?? [];
    $scale = $ov['label_scale'] ?? 1;
    $labelWidth = $photo['label_width'] ?? 52;
    $nameSize = round(9 * $scale, 2);
    $codeSize = round(7 * $scale, 2);
    $degSize = round(8 * $scale, 2);
@endphp

@if (! empty($labels))
    @foreach ($labels as $row)
        @php
            $isRight = ($row['side'] ?? 'left') === 'right';
            $labelLeft = $isRight ? round($photo['box_w'] - $labelWidth, 1) : 0;
        @endphp
        <div class="cm-label {{ $isRight ? 'cm-label-right' : 'cm-label-left' }}"
             style="left: {{ $labelLeft }}pt; top: {{ $row['label_top'] }}pt; width: {{ $labelWidth }}pt; font-size: {{ $nameSize }}px">
            @if (! empty($row['code']))<span class="cm-code" style="font-size: {{ $codeSize }}px">{{ $row['code'] }}</span>@endif
            @if (! empty($row['label']))<span class="cm-name">{{ $row['label'] }}</span>@endif
            @if (! empty($row['deg']))<span class="cm-deg" style="font-size: {{ $degSize }}px">{{ $row['deg'] }}</span>@endif
        </div>
        @if (! empty($row['leader']) && $row['leader']['width'] > 1)
            <div class="cm-leader"
                 style="left: {{ $row['leader']['left'] }}pt; top: {{ $row['leader']['top'] }}pt; width: {{ $row['leader']['width'] }}pt; transform: rotate({{ $row['leader']['angle'] }}deg)"></div>
        @endif
    @endforeach
@else
    @foreach ($photo['levels'] ?? [] as $level)
        <div class="cm-label {{ $level['label_align'] === 'left' ? 'cm-label-right' : 'cm-label-left' }}"
             style="left: {{ $level['label_left'] }}pt; top: {{ round($level['y'] - ($level['code'] !== '' ? 7 : 4), 1) }}pt; width: {{ $labelWidth }}pt; font-size: {{ $nameSize }}px">
            @if ($level['code'] !== '')<span class="cm-code" style="font-size: {{ $codeSize }}px">{{ $level['code'] }}</span>@endif
            <span class="cm-name">{{ $level['label'] }}</span>
        </div>
        <div class="cm-leader"
             style="left: {{ $level['x1'] }}pt; top: {{ $level['y'] }}pt; width: {{ round($level['x2'] - $level['x1'], 1) }}pt"></div>
        <div class="cm-dot"
             style="left: {{ $level['dot_left'] }}pt; top: {{ $level['dot_top'] }}pt"></div>
    @endforeach
@endif
