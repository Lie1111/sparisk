{{--
    The compact measurement table beside a posture photo.

    The photo labels already carry the level name, so the table can use the
    short measurement label and four columns: marker, section, deviation and
    status. Measured/reference values stay in the extended angle breakdown.
--}}
<table class="data compact">
    <thead>
        <tr>
            <th class="c-code">Marker</th>
            <th class="c-sec">Section</th>
            <th class="c-dev">Deviation</th>
            <th class="c-corr">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($view['rows'] as $row)
            <tr>
                <td>{{ $row['code'] !== '' ? $row['code'] : '—' }}</td>
                <td>
                    <div>{{ $row['short_name'] }}</div>
                    <div class="muted" style="font-size: 6.5px; margin-top: 1px">
                        Measured {{ $row['measured'] }}° · Ref {{ $row['reference'] !== null ? $row['reference'].'°' : 'Baseline' }}@if ($row['tolerance'] !== null) ±{{ $row['tolerance'] }}°@endif
                    </div>
                </td>
                <td class="c-dev val" style="color: {{ $row['color'] }}">{{ $row['deviation'] !== null ? $row['deviation'].'°' : '—' }}</td>
                <td style="color: {{ $row['color'] }}">{{ $row['correction'] }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">No measurements captured for this view.</td></tr>
        @endforelse
    </tbody>
</table>
<p class="plate-note">
    Deviation is measured from the applicable SATA reference. The status bands are explained on page 1.
</p>
