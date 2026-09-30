{{--
    APECS posture overlay for one photo.

    Emitted between the photo and its anatomical labels so the layers stack the
    way the reference plates do: grid, green plumb line, red alignment line,
    orange level lines, blue bone segments, landmark markers, then the angle
    labels. Each element is a plain CSS border inside the photo's positioned
    box, so dompdf writes vector operators and the overlay stays sharp instead
    of being flattened into the photo.

    `$photo` is the data the PDF service built for one view and `$upright` is
    true for the front/back plates, whose alignment reads as a green/red pair
    of tags at the top of the photo rather than a single boxed value.
--}}
@php
    $ov = $photo['overlay'] ?? [];
@endphp

@if (! empty($ov))
    {{-- Grid --}}
    @foreach ($ov['grid_v'] as $gx)
        <div class="ov-grid-v" style="left: {{ $gx }}pt; height: {{ $photo['box_h'] }}pt"></div>
    @endforeach
    @foreach ($ov['grid_h'] as $gy)
        <div class="ov-grid-h" style="left: {{ $photo['img_left'] }}pt; top: {{ $gy }}pt; width: {{ $photo['img_w'] }}pt"></div>
    @endforeach

    {{-- Green plumb line, always vertical --}}
    <div class="ov-plumb" style="left: {{ $ov['plumb']['left'] }}pt; height: {{ $photo['box_h'] }}pt"></div>

    {{-- Red alignment line, tilts with the body --}}
    @if (! empty($ov['align']))
        <div class="ov-align" style="left: {{ $ov['align']['left'] }}pt; top: {{ $ov['align']['top'] }}pt; width: {{ $ov['align']['width'] }}pt; transform: rotate({{ $ov['align']['angle'] }}deg)"></div>
    @endif

    {{-- Orange level lines, each carrying its tilt --}}
    @foreach ($ov['levels'] as $level)
        @if ($level['width'] > 0)
            <div class="ov-level" style="left: {{ $level['left'] }}pt; top: {{ $level['top'] }}pt; width: {{ $level['width'] }}pt; transform: rotate({{ $level['angle'] }}deg)"></div>
        @endif
        @if ($level['deg'] !== '')
            <div class="ov-deg" style="left: {{ $level['left'] }}pt; top: {{ round($level['top'] - 10, 1) }}pt">{{ $level['deg'] }}°</div>
        @endif
    @endforeach

    {{-- Blue bone segments --}}
    @foreach ($ov['segments'] as $segment)
        <div class="ov-seg" style="left: {{ $segment['left'] }}pt; top: {{ $segment['top'] }}pt; width: {{ $segment['width'] }}pt; transform: rotate({{ $segment['angle'] }}deg)"></div>
    @endforeach

    {{-- Landmark markers --}}
    @foreach ($ov['markers'] as $marker)
        <div class="ov-mk" style="left: {{ $marker['left'] }}pt; top: {{ $marker['top'] }}pt"></div>
    @endforeach

    {{-- Angle labels --}}
    @if ($ov['angle'] !== '')
        <div class="ov-align-tag" style="left: {{ $photo['img_left'] }}pt; top: {{ round($photo['box_h'] - 13, 1) }}pt">Alignment {{ $ov['angle'] }}°</div>
    @endif
    @if ($upright && $ov['angle'] !== '')
        <div class="ov-tag ov-tag-green" style="left: {{ round($ov['plumb']['left'] - 24, 1) }}pt; top: 2pt">0°</div>
        <div class="ov-tag ov-tag-red" style="left: {{ round($ov['plumb']['left'] + 2, 1) }}pt; top: 2pt">{{ $ov['angle'] }}°</div>
    @endif
@endif
