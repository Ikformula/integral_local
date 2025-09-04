{{--Network Planning and Schedule--}}
<table class="table table-hover table-sm">
    <thead>
    <tr>
        <th colspan="4" class="bg-secondary">Ground Operations Weekly Report</th>
    </tr>
    <tr class="bg-primary">
        <th></th>
        <th>Wk {{ $week_in_focus->week_number }} <small>({{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }})</small></th>
        <th>Previous Wk<small>({{ $presentation_data['titles']['last week'] }})</small></th>
        <th>Difference</th>
        <th>COMMENTS</th>
    </tr>
    </thead>
    <tbody>
    @php
        $current_week_total = $prev_week_total = 0;
    @endphp

    @foreach($form_fields->where('form_type', 'number') as $field)
        @php
            $current_week = isset($presentation_data['current_week'][$field->id]) ? $presentation_data['current_week'][$field->id]['total'] : null;
            $prev_week = isset($presentation_data['previous_week'][$field->id]) ? $presentation_data['previous_week'][$field->id]['total'] : null;
            $variance = isset($current_week) && isset($prev_week) ? ($current_week - $prev_week) : 'NIL';
            $current_week_total += ($field->form_type == 'number' && isset($current_week)) ? $current_week : 0;
            $prev_week_total += ($field->form_type == 'number' && isset($prev_week)) ? $prev_week : 0;
            $variance_direction = is_numeric($variance) ? ($variance < 0 ? 'decrease' : 'increase') : '';
            $comment[$field->id] = findFirstArrayWithValue($form_fields, 'Exit')['id'];
        @endphp
        <tr>
            <td>{{ $field->label }}</td>
            <td>{{ isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : ''}}{{ number_format($current_week) ?? 'N/A'}}{{ isset($field->unit, $current_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
            <td>{{ isset($field->unit, $prev_week) && $field->unit == '₦' ? '₦' : ''}}{{ number_format($prev_week) ?? 'N/A'}}{{ isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
            <td>{{ is_numeric($variance) ? abs($variance) : 'NIL'}} {{ $variance_direction }}</td>
            <td>{{ $presentation_data['current_week'][$fiel]['total'] ?? 'N/A'}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
