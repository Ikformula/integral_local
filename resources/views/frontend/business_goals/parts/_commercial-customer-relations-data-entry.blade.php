@php
    $comm_fields = commercialCustRelationsArr();
@endphp

<div class="table-responsive">
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Escalation type</th>
        <th>Escalation Nature</th>
        <th class="bg-light">CLOSED</th>
        <th class="bg-secondary">PENDING</th>
    </tr>
    </thead>
    <tbody>
        @foreach($comm_fields as $comm_field)
            @php
            $field = $form_fields->where('label', $comm_field['Escalation type'].' (pending)')->first();
            $closed_field = $form_fields->where('label', $comm_field['Escalation type'].' (closed)')->first();
            @endphp
            @isset($data_points)
                @php
                    $field_value = $data_points->where('business_area_id', $business_area->id)->where('score_card_form_field_id', $field->id)->where('week_range_id', $week_range_id)->first();
                    $closed_field_value = $data_points->where('business_area_id', $business_area->id)->where('score_card_form_field_id', $closed_field->id)->where('week_range_id', $week_range_id)->first();
                @endphp
            @endisset
            <tr>
                <td>{{ $comm_field['Escalation type'] }}</td>
                <td>{{ $comm_field['Escalation Nature'] }}</td>
                <td class="py-1">
                    <input type="number" min="0" step="0.01" class="form-control-sm" name="form_field[{{ $closed_field->id }}]" @isset($closed_field_value) value="{{ $closed_field_value->data_value }}" required @endisset title="Closed">
                    <small class="text-muted">CLOSED</small>
                </td>
                <td class="py-1">
                    <input type="number" min="0" step="0.01" class="form-control-sm" name="form_field[{{ $field->id }}]" @isset($field_value) value="{{ $field_value->data_value }}" required @endisset title="Pending">
                    <small class="text-muted">PENDING</small>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
