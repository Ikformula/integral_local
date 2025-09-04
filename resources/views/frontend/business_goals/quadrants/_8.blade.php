{{-- Finance - Business Management --}}
@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="tableE table-hover table-sm mx-auto" id="ba_{{ $business_area_id }}">
    <thead>
    <tr>
        <th colspan="4" class="bg-navy">{{ $business_area->name }} </th>
    </tr>
    <tr  style="background-color: #B3CEEB">
        <th>Weekly Report</th>
        <th>Wk {{ $week_in_focus->week_number }}
{{--            <small>({{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }})</small>--}}
        </th>
        <th>Wk {{ $previousWeek->week_number }}
{{--            <small>({{ $presentation_data['titles']['last week'] }})</small>--}}
        </th>
        <th>Variance</th>
    </tr>
    </thead>
    <tbody>
    @php
        $current_week_total = $prev_week_total = 0;
    @endphp

    @foreach($form_fields as $field)
        @php
        $current_week = isset($presentation_data['current_week'][$field->id]) ? $presentation_data['current_week'][$field->id]['total'] : null;
        $prev_week = isset($presentation_data['previous_week'][$field->id]) ? $presentation_data['previous_week'][$field->id]['total'] : null;
        $variance = isset($current_week) && $current_week != 0 && isset($prev_week) && $prev_week != 0 ? round((($current_week - $prev_week) / $prev_week) * 100, 2).'%' : 'N/A';
        $current_week_total += ($field->form_type == 'number' && isset($current_week)) ? $current_week : 0;
        $prev_week_total += ($field->form_type == 'number' && isset($prev_week)) ? $prev_week : 0;
    @endphp
    <tr>
        <th>{{ $field->label }}</th>
        <td>{{ isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : ''}}{{ number_format($current_week) ?? 'N/A'}}{{ isset($field->unit, $current_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
        <td>{{ isset($field->unit, $prev_week) && $field->unit == '₦' ? '₦' : ''}}{{ number_format($prev_week) ?? 'N/A'}}{{ isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
        <td @if($variance < 0)  style="color: #DF356F" @endif>{{ $variance}}</td>
    </tr>
@endforeach
    </tbody>
</table>

@if(!isset($no_daily))
<div class="row mt-3">
    <div class="col-12">
        @include('frontend.business_goals.dailies._8')
    </div>
</div>
@endif
