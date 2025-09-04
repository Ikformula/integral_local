{{--Gross Sales--}}
@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="tableE table-hover table-sm mx-auto" id="ba_{{ $business_area_id }}">
    <thead class="bg-secondary">
    <tr>
        <th colspan="4" class="bg-navy">{{ $business_area->name }} Report</th>
    </tr>
    <tr>
        <td>Gross Sales Revenue (NGN)</td>
        <td>Wk {{ $week_in_focus->week_number }}
{{--            <small>({{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }})</small>--}}
        </td>
        <td>Wk {{ $previousWeek->week_number }}
{{--            <small>({{ $presentation_data['titles']['last week'] }})</small>--}}
        </td>
        <td>Variance</td>
    </tr>
    </thead>
    <tbody>
    @php
        $current_week_total = $prev_week_total = 0;
        //            For RoE
        $roe_field_value = \App\Models\DataPoint::where('business_area_id', $business_area_id)
        ->where('week_range_id', $week_in_focus->id)
        ->where('score_card_form_field_id', 47)
        ->first();
        $roe_field_value = isset($roe_field_value) ? $roe_field_value->data_value : 'N/A';
    @endphp

    @foreach($form_fields as $field)
        @if($field->id != 47)
{{--            For RoE --}}
        @php
        $current_week = isset($presentation_data['current_week'][$field->id]) ? $presentation_data['current_week'][$field->id]['total'] : null;
        $prev_week = isset($presentation_data['previous_week'][$field->id]) ? $presentation_data['previous_week'][$field->id]['total'] : null;
        $variance = isset($current_week) && $current_week != 0 && isset($prev_week) && $prev_week != 0 ? round((($current_week - $prev_week) / $prev_week) * 100, 2).'%' : 'N/A';
        $current_week_total += ($field->form_type == 'number' && isset($current_week)) ? $current_week : 0;
        $prev_week_total += ($field->form_type == 'number' && isset($prev_week)) ? $prev_week : 0;
    @endphp
    <tr>
        <td>
            {{ $field->label }}
            @if($field->id == 46 && $roe_field_value != 'N/A')
                ₦{{ $roe_field_value }}/$
            @endif
        </td>
        <td>{{ isset($current_week) ? '₦' : '' }}{{ number_format($current_week) ?? 'N/A'}}</td>
        <td>{{ isset($prev_week) ? '₦' : '' }}{{ number_format($prev_week) ?? 'N/A'}}</td>
        <td @if($variance < 0)  style="color: #DF356F" @endif>{{ $variance}}</td>
    </tr>
@endif
@endforeach
    <tr>
        <th>Total</th>
        <th>₦{{ number_format($current_week_total) }}</th>
        <th>₦{{ number_format($prev_week_total) }}</th>
        <th>{{ $current_week_total != 0 ? number_format((($current_week_total - $prev_week_total) / $current_week_total) * 100) : 0 }}%</th>
    </tr>

    @if(isset($presentation_mode) && $presentation_mode == 'on')
    @php
        $bsc_stats['Total Sales'] = [
'title' => 'Total Sales',
'weeks' => [
    'Wk '.$week_in_focus->week_number => '₦'.number_format($current_week_total),
   'Wk '.$previousWeek->week_number => '₦'.number_format($prev_week_total)
],
   'variance' => calculateVariance($current_week_total, $prev_week_total)
];
    @endphp

    <script>
        bscStats['Total Sales'] = @json($bsc_stats['Total Sales']);
    </script>
    @endif
    </tbody>
</table>

@if(!isset($no_daily))
<div class="row mt-3">
    <div class="col-12">
        @include('frontend.business_goals.dailies._4')
    </div>
</div>
@endif
