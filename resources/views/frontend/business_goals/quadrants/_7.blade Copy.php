{{--Customer Relations (Talk2Us)--}}
@include('frontend.business_goals.parts.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="table table-hover table-sm mb-5" id="ba_{{ $business_area_id }}">
    <thead>
    <tr>
        <th colspan="4" style="background-color: #B3CEEB">{{ $business_area->name }} Report</th>
    </tr>
    <tr  style="background-color: #B3CEEB">
        <th>Description of Incoming Task</th>
        <th>Wk {{ $week_in_focus->week_number }} <small>({{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }})</small></th>
        <th>Previous Wk<small>({{ $presentation_data['titles']['last week'] }})</small></th>
        <th>% Variance</th>
    </tr>
    </thead>
    <tbody>
    @php
        $current_week_total = $prev_week_total = 0;
$items = [
    'FCCPC',
    'NCAA',
    'LEGAL DEMAND',
    'EMAILS',
    'CALLS'
];
    @endphp

    @foreach($items as $item)
        @php
        $field = $form_fields_collection->where('label', $item)->first();
        $current_week = isset($presentation_data['current_week'][$field->id]) ? $presentation_data['current_week'][$field->id]['total'] : null;
        $prev_week = isset($presentation_data['previous_week'][$field->id]) ? $presentation_data['previous_week'][$field->id]['total'] : null;
        $variance = isset($current_week) && isset($prev_week) ? round((($current_week - $prev_week) / $current_week) * 100, 2).'%' : '-';
        $current_week_total += ($field->form_type == 'number' && isset($current_week)) ? $current_week : 0;
        $prev_week_total += ($field->form_type == 'number' && isset($prev_week)) ? $prev_week : 0;
    @endphp
    <tr>
        <td>{{ $field->label }}</td>
        <td>{{ isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : ''}}{{ number_format($current_week) ?? '-'}}{{ isset($field->unit, $current_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
        <td>{{ isset($field->unit, $prev_week) && $field->unit == '₦' ? '₦' : ''}}{{ number_format($prev_week) ?? '-'}}{{ isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
        <td @if($variance < 0)  style="color: #DF356F" @endif>{{ $variance}}</td>
    </tr>
@endforeach

    </tbody>
</table>

@include('frontend.business_goals.parts.table2xlsx_button', ['table_id' => 'ba_2_'. $business_area_id] )
<table class="table table-hover table-sm table-bordered" id="ba_2_{{ $business_area_id }}">
    <thead style="background-color: #B3CEEB" class=" text-center">
    <tr>
        <th rowspan="2" class="text-left">Escalation Type</th>
        <th rowspan="2" class="text-left">Escalation Nature</th>
        <th colspan="2">Week over week comparison</th>
        <th colspan="2">Status</th>
    </tr>
    <tr>
        <th><small>{{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }}</small></th>
        <th><small>{{ $presentation_data['titles']['last week'] }}</small></th>
        <th>Closed</th>
        <th>Pending</th>
    </tr>
    </thead>
    <tbody>

    @php

$data = [
    ['label' => 'Online reservation error', 'nature' => 'Complaint'],
    ['label' => 'New Refund requests', 'nature' => 'Request'],
    ['label' => 'Pending refund request', 'nature' => 'Complaint'],
    ['label' => 'Downgrade', 'nature' => 'Complaint'],
    ['label' => 'Denied boarding', 'nature' => 'Complaint'],
    ['label' => 'Cancelled flight', 'nature' => 'Complaint'],
    ['label' => 'Baggage', 'nature' => 'Complaint'],
    ['label' => 'Fraudulent use of ticket', 'nature' => 'Complaint'],
    ['label' => 'Forgotten item on board', 'nature' => 'Complaint'],
    ['label' => 'Ticket Modification', 'nature' => 'Request'],
    ['label' => 'Ticket revalidation lieu of refunds', 'nature' => 'Request'],
    ['label' => 'Inquiries/ Internal external feedback', 'nature' => 'Request']
];

    $current_week_pending_total = $prev_week_pending_total = $current_week_closed_total = $prev_week_closed_total = 0;
    @endphp

    @foreach($data as $item)
        @php
            $field = $form_fields_collection->where('label', $item['label'].' (pending)')->first();
            $field_closed = $form_fields_collection->where('label', $item['label'].' (closed)')->first();
            $field_pending_value = isset($presentation_data['current_week'][$field->id]) ? $presentation_data['current_week'][$field->id]['total'] : null;
            $field_closed_value = isset($presentation_data['current_week'][$field_closed->id]) ? $presentation_data['current_week'][$field_closed->id]['total'] : null;

            $current_week = $field_pending_value + $field_closed_value;

            $prev_field_pending_value = isset($presentation_data['previous_week'][$field->id]) ? $presentation_data['previous_week'][$field->id]['total'] : null;
            $prev_field_closed_value = isset($presentation_data['previous_week'][$field_closed->id]) ? $presentation_data['previous_week'][$field_closed->id]['total'] : null;

            $prev_week = $prev_field_pending_value + $prev_field_closed_value;
            $current_week_pending_total += isset($field_pending_value) ? $field_pending_value : 0;
            $current_week_closed_total += isset($field_closed_value) ? $field_closed_value : 0;
            $prev_week_pending_total += isset($prev_field_pending_value) ? $prev_field_pending_value : 0;
            $prev_week_closed_total += isset($prev_field_closed_value) ? $prev_field_closed_value : 0;
        @endphp
        <tr>
            <td>{{ $field->label }}</td>
            <td>{{ $item['nature'] }}</td>
            <td class="text-center">{{ number_format($field_pending_value + $field_closed_value) ?? '-'}}</td>
            <td class="text-center">{{ number_format($prev_field_pending_value + $prev_field_closed_value) ?? '-'}}</td>
            <td class="text-center">{{ number_format($field_closed_value) ?? '-'}}</td>
            <td class="text-center">{{ number_format($field_pending_value) ?? '-'}}</td>
        </tr>
    @endforeach
    <tr>
        <td>Total</td>
        <td></td>
        <td style="background-color: #B3CEEB" class=" text-center">{{ number_format($current_week_pending_total + $current_week_closed_total) }}</td>
        <td style="background-color: #B3CEEB" class=" text-center">{{ number_format($prev_week_pending_total + $prev_week_closed_total) }}</td>
        <td style="background-color: #B3CEEB" class=" text-center">{{ number_format($current_week_closed_total) }}</td>
        <td style="background-color: #B3CEEB" class=" text-center">{{ number_format($current_week_pending_total) }}</td>
    </tr>
    </tbody>
</table>


