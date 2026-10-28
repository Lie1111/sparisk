{{--
    The compact measurement table beside a posture photo.

    The photo labels already carry the level name, so the table uses the
    short measurement label and five columns: marker, section, deviation,
    status and the clinical note. Measured/reference values stay in the
    extended angle breakdown.
--}}
<table class="data compact">
    <thead>
        <tr>
            <th class="c-code">Marker</th>
            <th class="c-sec">Section</th>
            <th class="c-dev">Deviation</th>
            <th class="c-corr">Status</th>
            <th>Note</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($view['rows'] as $row)
            <tr>
                <td>{{ $row['code'] !== '' ? $row['code'] : '—' }}</td>
                <td>
                    <div>{{ $row['short_name'] }}</div>
                </td>
                <td class="c-dev val" style="color: {{ $row['color'] }}">{{ $row['deviation'] !== null ? $row['deviation'].'°' : '—' }}</td>
                <td style="color: {{ $row['color'] }}">{{ $row['correction'] }}</td>
                <td class="muted">{{ $row['note'] ?: '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">No measurements captured for this view.</td></tr>
        @endforelse
    </tbody>
</table>
