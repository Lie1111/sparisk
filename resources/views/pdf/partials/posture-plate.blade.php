{{--
    One posture photo with its APECS overlay and gutter labels.

    The photo is drawn inside a fixed-size box the service computes in points.
    Every layer is absolutely positioned inside that box, so the labels always
    stay in the gutters and the lines stay on the body regardless of the photo
    proportions.

    `$image` is one entry from SpariskReportPdfService::imageData() and
    `$upright` is true for the front/back plates, whose alignment tags read as
    a green/red pair at the top of the photo.
--}}
@php
    $image = $image ?? null;
    $upright = $upright ?? true;
@endphp

@if ($image)
    <div class="photo-block">
        @if (empty($image['box_w']))
            <img class="plain-photo" src="{{ $image['data'] }}" alt="{{ $image['label'] }}">
        @else
            <div class="cm-box" style="width: {{ $image['box_w'] }}pt; height: {{ $image['box_h'] }}pt">
                <img class="cm-img" style="left: {{ $image['img_left'] }}pt; width: {{ $image['img_w'] }}pt; height: {{ $image['box_h'] }}pt" src="{{ $image['data'] }}" alt="{{ $image['label'] }}">
                @include('pdf.partials.posture-overlay', ['photo' => $image, 'upright' => $upright])
                @include('pdf.partials.posture-labels', ['photo' => $image])
            </div>
        @endif
    </div>
@else
    <p class="muted" style="margin: 0">No photo was captured for this view.</p>
@endif
