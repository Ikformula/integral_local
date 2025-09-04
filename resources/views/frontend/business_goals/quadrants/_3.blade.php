{{-- Operational Delivery / Customer Service --}}
@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="table table-hover table-sm mx-auto" id="ba_{{ $business_area_id }}">
    <thead class="bg-secondary">
    <tr>
        <th colspan="5" class="bg-navy">{{ $business_area->name }} Report</th>
    </tr>
    <tr>
        <td></td>
        <td>Target</td>
        <td>Actual: Wk {{ $week_in_focus->week_number }}
{{--            <small>({{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }})</small>--}}
        </td>
        <td>Wk {{ $previousWeek->week_number }}
{{--            <small>({{ $presentation_data['titles']['last week'] }})</small>--}}
        </td>
        <td>Previous Mth <small>({{ $presentation_data['titles']['last month'] }})</small></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>On Time Punctuality</td>
        <td>{{ $presentation_data['current_week']['1']['total'] ?? $presentation_data['current_week']['1']['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['current_week']['2']['total'] ?? $presentation_data['current_week']['2']['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['previous_week']['2']['total'] ?? $presentation_data['previous_week']['2']['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['previous_month']['2']['total'] ?? $presentation_data['previous_month']['2']['average'] ?? 'N/A'}}%</td>
    </tr>

    @if(isset($presentation_mode) && $presentation_mode == 'on')
        @php
            $bsc_stats['OTP (Flt. Ops)'] = [
    'title' => 'OTP (Flt. Ops)',
    'weeks' => [
        'Wk '.$week_in_focus->week_number => ($presentation_data['current_week']['2']['average'] ?? 'N/A').'%',
       'Wk '.$previousWeek->week_number => ($presentation_data['previous_week']['2']['average'] ?? 'N/A').'%'
],
   'variance' => calculateVariance($presentation_data['current_week']['2']['average'] ?? 'N/A', $presentation_data['previous_week']['2']['average'] ?? 'N/A', 0, $presentation_data['current_week']['1']['average'] ?? null),
   'target' => $presentation_data['current_week']['1']['average'] ?? 'N/A',
   'target_colours' => [
       'a' => bscTargetReachColour($presentation_data['current_week']['1']['average'] ?? 'N/A', $presentation_data['current_week']['2']['average'] ?? 'N/A'),
       'b' => bscTargetReachColour($presentation_data['current_week']['1']['average'] ?? 'N/A', $presentation_data['previous_week']['2']['average'] ?? 'N/A')
],
'unit_symbol' => '%'
];
        @endphp

        <script>
            bscStats['OTP (Flt. Ops)'] = @json($bsc_stats['OTP (Flt. Ops)']);
        </script>
    @endif

    <tr>
        <td>Load Factor</td>
        @php
            $load_factor_target = findFirstArrayWithValue($form_fields, 'Load Factor - Target');
            $load_factor_actual = findFirstArrayWithValue($form_fields, 'Load Factor - Actual');
        @endphp
        <td>{{ $presentation_data['current_week'][$load_factor_target['id']]['total'] ?? $presentation_data['current_week'][$load_factor_target['id']]['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['current_week'][$load_factor_actual['id']]['total'] ?? $presentation_data['current_week'][$load_factor_actual['id']]['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['previous_week'][$load_factor_actual['id']]['total'] ?? $presentation_data['previous_week'][$load_factor_actual['id']]['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['previous_month'][$load_factor_actual['id']]['total'] ?? $presentation_data['previous_month'][$load_factor_actual['id']]['average'] ?? 'N/A'}}%</td>
    </tr>
    @if(isset($presentation_mode) && $presentation_mode == 'on')
        @php
            $bsc_stats['Load Factor'] = [
    'title' => 'Load Factor',
    'weeks' => [
        'Wk '.$week_in_focus->week_number => ($presentation_data['current_week'][$load_factor_actual['id']]['average'] ?? 'N/A').'%',
       'Wk '.$previousWeek->week_number => ($presentation_data['previous_week'][$load_factor_actual['id']]['average'] ?? 'N/A').'%'
],
   'variance' => calculateVariance($presentation_data['current_week'][$load_factor_actual['id']]['average'] ?? 'N/A', $presentation_data['previous_week'][$load_factor_actual['id']]['average'] ?? 'N/A', 0,  $presentation_data['current_week'][$load_factor_target['id']]['average'] ?? null),
   'target' => $presentation_data['current_week'][$load_factor_target['id']]['average'] ?? 'N/A',
   'target_colours' => [
       'a' => bscTargetReachColour($presentation_data['current_week'][$load_factor_target['id']]['average'] ?? 'N/A', $presentation_data['current_week'][$load_factor_actual['id']]['average'] ?? 'N/A'),
       'b' => bscTargetReachColour($presentation_data['current_week'][$load_factor_target['id']]['average'] ?? 'N/A', $presentation_data['previous_week'][$load_factor_actual['id']]['average'] ?? 'N/A')
],
'unit_symbol' => '%'
];
        @endphp

    <script>
        bscStats['Load Factor'] = @json($bsc_stats['Load Factor']);
    </script>
    @endif
    <tr>
        @php
            $completion_factor_target = findFirstArrayWithValue($form_fields, 'Completion Factor - Target');
            $completion_factor_actual = findFirstArrayWithValue($form_fields, 'Completion Factor - Actual');
        @endphp
        <td>Completion Factor</td>
        <td>{{ $presentation_data['current_week'][$completion_factor_target['id']]['total'] ?? $presentation_data['current_week'][$completion_factor_target['id']]['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['current_week'][$completion_factor_actual['id']]['total'] ?? $presentation_data['current_week'][$completion_factor_actual['id']]['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['previous_week'][$completion_factor_actual['id']]['total'] ?? $presentation_data['previous_week'][$completion_factor_actual['id']]['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['previous_month'][$completion_factor_actual['id']]['total'] ?? $presentation_data['previous_month'][$completion_factor_actual['id']]['average'] ?? 'N/A'}}%</td>
    </tr>
{{--    changed on Aug 18, 2025 to use that from Planning and schedule--}}
    @if(isset($presentation_mode) && $presentation_mode == 'on')
@php
    $bsc_stats['Completion Factor'] = [
        'title' => 'Completion Factor',
        'weeks' => [
            'Wk '.$week_in_focus->week_number => ($presentation_data['current_week'][$completion_factor_actual['id']]['average'] ?? 'N/A').'%',
           'Wk '.$previousWeek->week_number => ($presentation_data['previous_week'][$completion_factor_actual['id']]['average'] ?? 'N/A').'%'
    ],
   'variance' => calculateVariance($presentation_data['current_week'][$completion_factor_actual['id']]['average'] ?? 'N/A', $presentation_data['previous_week'][$completion_factor_actual['id']]['average'] ?? 'N/A', 0,  $presentation_data['current_week'][$completion_factor_target['id']]['average'] ?? null),
   'target' => $presentation_data['current_week'][$completion_factor_target['id']]['average'] ?? 'N/A',
   'target_colours' => [
       'a' => bscTargetReachColour($presentation_data['current_week'][$completion_factor_target['id']]['average'] ?? 'N/A', $presentation_data['current_week'][$completion_factor_actual['id']]['average'] ?? 'N/A'),
       'b' => bscTargetReachColour($presentation_data['current_week'][$completion_factor_target['id']]['average'] ?? 'N/A', $presentation_data['previous_week'][$completion_factor_actual['id']]['average'] ?? 'N/A')
],
'unit_symbol' => '%'
    ];
@endphp

    <script>
        bscStats['Completion Factor'] = @json($bsc_stats['Completion Factor']);
    </script>
    @endif
    @php
        $mishandled = findFirstArrayWithValue($form_fields, 'Mishandled Baggages')['id'];
        $damaged = findFirstArrayWithValue($form_fields, 'Damaged Bags')['id'];
    @endphp
    <tr>
        <td>Mishandled Baggage</td>
        <td></td>
        <td>{{ $presentation_data['current_week'][$mishandled]['total'] ?? $presentation_data['current_week'][$mishandled]['average'] ?? 'N/A'}}</td>
        <td>{{ $presentation_data['previous_week'][$mishandled]['total'] ?? $presentation_data['previous_week'][$mishandled]['average'] ?? 'N/A'}}</td>
        <td>{{ $presentation_data['previous_month'][$mishandled]['total'] ?? $presentation_data['previous_month'][$mishandled]['average'] ?? 'N/A'}}</td>
    </tr>
    <tr>
        <td>Damaged Bags</td>
        <td></td>
        <td>{{ $presentation_data['current_week'][$damaged]['total'] ?? $presentation_data['current_week'][$damaged]['average'] ?? 'N/A'}}</td>
        <td>{{ $presentation_data['previous_week'][$damaged]['total'] ?? $presentation_data['previous_week'][$damaged]['average'] ?? 'N/A'}}</td>
        <td>{{ $presentation_data['previous_month'][$damaged]['total'] ?? $presentation_data['previous_month'][$damaged]['average'] ?? 'N/A'}}</td>
    </tr>
{{--    <tr>--}}
{{--        <td>Pilfered Bags</td>--}}
{{--        <td></td>--}}
{{--        <td>{{ $presentation_data['current_week'][$pilfered]['total'] ?? 'N/A'}}</td>--}}
{{--        <td>{{ $presentation_data['previous_week'][$pilfered]['total'] ?? 'N/A'}}</td>--}}
{{--        <td>{{ $presentation_data['previous_month'][$pilfered]['total'] ?? 'N/A'}}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>Handled Bags</td>--}}
{{--        <td></td>--}}
{{--        <td>{{ $presentation_data['current_week'][$handled]['total'] ?? 'N/A'}}</td>--}}
{{--        <td>{{ $presentation_data['previous_week'][$handled]['total'] ?? 'N/A'}}</td>--}}
{{--        <td>{{ $presentation_data['previous_month'][$handled]['total'] ?? 'N/A'}}</td>--}}
{{--    </tr>--}}


    <tr>
        @php $complaints_target = findFirstArrayWithValue($form_fields, 'Customer Complaints/Requests Target')['id'];
        // $Complaints = findFirstArrayWithValue($form_fields, 'Complaints')['id'];
        $Number_of_Complains = findFirstArrayWithValue($form_fields, 'Number of Complains')['id'];
        // $Requests = findFirstArrayWithValue($form_fields, 'Requests')['id'];

        $total_pax_data = \App\Models\DataPoint::where('score_card_form_field_id', 66)
        ->where('week_range_id', $week_in_focus->id)
        ->first();

        $complaints = $presentation_data['current_week'][$Number_of_Complains]['total'] ?? null;
        $total_pax = isset($total_pax_data) ? $total_pax_data->data_value : null;
        $complaints_per_thousand = isset($total_pax, $complaints) ? round(($complaints/$total_pax) * 1000, 2) : 'N/A';

        $prev_total_pax_data = \App\Models\DataPoint::where('score_card_form_field_id', 66)
        ->where('week_range_id', $previousWeek->id)
        ->first();
        $prev_complaints = $presentation_data['previous_week'][$Number_of_Complains]['total'] ?? null;
        $prev_total_pax = isset($prev_total_pax_data) ? $prev_total_pax_data->data_value : null;
        $prev_complaints_per_thousand = isset($prev_total_pax, $prev_complaints) ? round(($prev_complaints/$prev_total_pax) * 1000, 2) : 'N/A';

        @endphp

       <td>Customer Complaints <br><small>(Complaints/1000 pax)</small></td>
        <td>{{ $presentation_data['current_week'][$complaints_target]['total'] ?? $presentation_data['current_week'][$complaints_target]['average'] ?? 'N/A'}}</td>

        <td title="Number of complaints: {{ $complaints }}">
            @if(isset($presentation_mode))<a href="#escalation_tb"><i class="fa fa-link"></i></a>@endif
{{--            {{ $presentation_data['current_week'][$Complaints]['total'] ?? $presentation_data['current_week'][$Complaints]['average'] ?? 'N/A'}} complaints,--}}
            {{ $complaints_per_thousand }}
        {{--    Customer Complains {{ $presentation_data['current_week'][$Requests]['total'] ?? $presentation_data['current_week'][$Requests]['average'] ?? 'N/A'}} requests</td>--}}
        <td title="Number of complaints: {{ $prev_complaints }}">
{{--            {{ $presentation_data['previous_week'][$Complaints]['total'] ?? $presentation_data['previous_week'][$Complaints]['average'] ?? ''}} complaints,--}}
            {{ $prev_complaints_per_thousand }}
        {{--    {{ $presentation_data['previous_week'][$Requests]['total'] ?? $presentation_data['previous_week'][$Requests]['average'] ?? 'N/A'}} requests</td>--}}
        <td></td>
    </tr>

    <tr>
        @php
            $tech_desp_target = findFirstArrayWithValue($form_fields, 'Technical Despatch Rate Targeted')['id'];
            $tech_desp_actual = findFirstArrayWithValue($form_fields, 'Technical Despatch Rate Actual')['id'];
        @endphp
        <td>Technical Dispatch Rate</td>
        <td>{{ $presentation_data['current_week'][$tech_desp_target]['total'] ?? $presentation_data['current_week'][$tech_desp_target]['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['current_week'][$tech_desp_actual]['total'] ?? $presentation_data['current_week'][$tech_desp_actual]['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['previous_week'][$tech_desp_actual]['total'] ?? $presentation_data['previous_week'][$tech_desp_actual]['average'] ?? 'N/A'}}%</td>
        <td>{{ $presentation_data['previous_month'][$tech_desp_actual]['total'] ?? $presentation_data['previous_month'][$tech_desp_actual]['average'] ?? 'N/A'}}%</td>
    </tr>

    </tbody>
</table>

@php
$delays_id = findFirstArrayWithValue($form_fields, 'Delays JSON')['id'];
@endphp


<div class="row my-2">
    <div class="col-12">
        <div class="card card-warning">
            <div class="card-header">
                <strong>Delay Category & Occurrence</strong>
            </div>
            <div class="card-body">
                <div class="row my-2">

                    @if(array_key_exists($delays_id, $presentation_data['current_week']))
                    <div class="col-6">
                        <h5>Wk {{ $week_in_focus->week_number }}</h5>
                        <canvas id="delay_occurencesChart" class="delay_occurencesChart"
                                style="min-height: 700px; height: 700px; max-height: 700px; max-width: 100%;"></canvas>
                    </div>
                    @endif


                        @if(array_key_exists($delays_id, $presentation_data['previous_week']))
                    <div class="col-6">
                        <h5>Wk {{ $week_in_focus->week_number - 1 }}</h5>
                        <canvas id="delay_occurencesChart_prev" class="delay_occurencesChart_prev"
                                    @php $h = isset($presentation_mode) ? 600 : 700; @endphp
                                style="min-height: {{ $h }}px; height: {{ $h }}px; max-height: {{ $h }}px; max-width: 100%;"></canvas>
                    </div>
                            @endif

                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>


@if(array_key_exists($delays_id, $presentation_data['current_week']) || array_key_exists($delays_id, $presentation_data['previous_week']))
<script src="{{ asset('adminlte3.2/plugins/chart.js/Chart.min.js') }}"></script>
@endif

@if(array_key_exists($delays_id, $presentation_data['current_week']))
    @include('frontend.business_goals.partials._flight-ops-delivery-chart-script', ['week' => 'current_week', 'chart_className' => 'delay_occurencesChart'])
@endif


@if(array_key_exists($delays_id, $presentation_data['previous_week']))
    @include('frontend.business_goals.partials._flight-ops-delivery-chart-script', ['week' => 'previous_week', 'chart_className' => 'delay_occurencesChart_prev'])
@endif



@if(!isset($no_daily))
<div class="row mt-3">
    <div class="col-12">
        @include('frontend.business_goals.dailies._3')
    </div>
</div>
@endif
