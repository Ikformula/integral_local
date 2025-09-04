{{--Network Planning and Schedule--}}
@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="tableE table-hover table-striped table-sm mx-auto" id="ba_{{ $business_area_id }}">
    <thead>
    <tr>
        <th colspan="4" class="bg-navy">{{ $business_area->name }} </th>
    </tr>
    <tr  style="background-color: #B3CEEB">
        <th>Weekly Report</th>
        <th>Wk {{ $week_in_focus->week_number }}
{{--            <small>({{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }})--}}
            </small></th>
        <th>Wk {{ $previousWeek->week_number }}
{{--            <small>({{ $presentation_data['titles']['last week'] }})</small>--}}
        </th>
        <th>Variance</th>
    </tr>
    </thead>
    <tbody>
    @php
        $current_week_total = $prev_week_total = 0;
        $custom_order = [59, 60, 61, 62, 63, 64, 65, 66, 67, 72, 68, 387, 69, 70];
    @endphp

    @foreach($custom_order as $field_id)
        @php
            $field = $form_fields_collection->where('id', $field_id)->first();

            $current_week = isset($presentation_data['current_week'][$field_id]) ? (isset($presentation_data['current_week'][$field_id]['total']) ? $presentation_data['current_week'][$field_id]['total'] : $presentation_data['current_week'][$field_id]['average']) : null;
            $prev_week = isset($presentation_data['previous_week'][$field_id]) ? (isset($presentation_data['previous_week'][$field_id]['total']) ? $presentation_data['previous_week'][$field_id]['total'] : $presentation_data['previous_week'][$field_id]['average']) : null;
            $variance = isset($current_week) && isset($prev_week) && $prev_week != 0 ? round((($current_week - $prev_week) / $prev_week) * 100, 2).'%' : 'N/A';
            $current_week_total += ($field->form_type == 'number' && isset($current_week)) ? $current_week : 0;
            $prev_week_total += ($field->form_type == 'number' && isset($prev_week)) ? $prev_week : 0;
    @endphp

{{--        Added on Aug 18, 2025 --}}
        @if(isset($presentation_mode) && $presentation_mode == 'on' && in_array($field_id, [59, 61]))
            @php
                $bsc_stats[$field->label.' (Planning)'] = [
                    'title' => ($field_id == 59 ? 'Completion Factor' : $field->label).' (Planning)',
                    'weeks' => [
                        'Wk '.$week_in_focus->week_number => ($presentation_data['current_week'][$field_id]['average'] ?? 'N/A').'%',
                       'Wk '.$previousWeek->week_number => ($presentation_data['previous_week'][$field_id]['average'] ?? 'N/A').'%'
                ],
               'variance' => calculateVariance($presentation_data['current_week'][$field_id]['average'] ?? 'N/A', $presentation_data['previous_week'][$field_id]['average'] ?? 'N/A'),
            'unit_symbol' => '%'
                ];
            @endphp

            <script>
                bscStats['{{ $field->label .' (Planning)' }}'] = @json($bsc_stats[$field->label.' (Planning)']);
            </script>
        @endif


        @if(isset($presentation_mode) && $presentation_mode == 'on')
{{--        @if(in_array($field_id, [68, 62, 61, 66]))--}}
        @if(in_array($field_id, [68, 62, 66]))
            @php
            $bsc_stats[$field_id] = [
    'title' => $field->label == 'PAX' ? 'Total PAX' : $field->label,
    'weeks' => [
        'Wk '.$week_in_focus->week_number => (isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : ''. (checkIntNumber($current_week) ?? 'N/A')).(isset($field->unit, $current_week) && $field->unit != '₦' ? $field->unit : ''),
       'Wk '.$previousWeek->week_number => (isset($field->unit, $prev_week) && $field->unit == '₦' ? '₦' : ''.(checkIntNumber($prev_week) ?? 'N/A')).(isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : '')
],
   'variance' => calculateVariance($current_week, $prev_week)
];
            @endphp

            <script>
                bscStats[{{ $field_id }}] = @json($bsc_stats[$field_id]);
            </script>
            @endif
            @endif

            <span id="available-aircraft" data-bsc-available-aircraft-cw="" data-bsc-available-aircraft-pw="" style="display: none"></span>

    <tr>
        <td>{{ $field->label }}</td>
        <td>{{ isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : ''}}{{ checkIntNumber($current_week) ?? 'N/A'}}{{ isset($field->unit, $current_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
        <td>{{ isset($field->unit, $prev_week) && $field->unit == '₦' ? '₦' : ''}}{{ checkIntNumber($prev_week) ?? 'N/A'}}{{ isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
        <td @if($variance < 0)  style="color: #DF356F" @endif>{{ $variance}}</td>
    </tr>
@endforeach
    </tbody>
</table>


@if(!isset($no_daily))
<div class="row mt-3">
    <div class="col-12">
        @include('frontend.business_goals.dailies._6')
    </div>
</div>
@endif
