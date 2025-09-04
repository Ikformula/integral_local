{{--Revenue Management--}}
@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="tableE table-hover table-sm mx-auto" id="ba_{{ $business_area_id }}">
    <thead>
    <tr>
        <th colspan="4" class="bg-navy">{{ $business_area->name }} Report</th>
    </tr>
    <tr>
        <th colspan="4" class="bg-navy">Weekly Analysis Flown Pax Report - Wk {{ $week_in_focus->week_number }} ({{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }})</th>
    </tr>
    <tr>
        <th>Week-on-Week</th>
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
        $ba_5 = [];
        $custom_order = [49,50,51,52,53,54,55,56,57,58,207,208,209,210,211,345,346,347,348];
        $stat_fields = [50, 52, 57, 58];
        $forecast_fields = [345,346,347,348];
    @endphp

    @foreach($custom_order as $field_id)
        @php
        $field = $form_fields_collection->where('id', $field_id)->first();

if($field->unit != '%'){
        $current_week = isset($presentation_data['current_week'][$field->id]) && isset($presentation_data['current_week'][$field->id]['total']) ? $presentation_data['current_week'][$field->id]['total'] : null;
        $prev_week = isset($presentation_data['previous_week'][$field->id])  && isset($presentation_data['previous_week'][$field->id]['total']) ? $presentation_data['previous_week'][$field->id]['total'] : null;
        $variance = isset($current_week) && $current_week != 0 && isset($prev_week) && $prev_week != 0 ? round((($current_week - $prev_week) / $prev_week) * 100, 2).'%' : 'N/A';
        }else{
    $current_week = isset($presentation_data['current_week'][$field->id]) && isset($presentation_data['current_week'][$field->id]['average']) ? $presentation_data['current_week'][$field->id]['average'] : null;
        $prev_week = isset($presentation_data['previous_week'][$field->id])  && isset($presentation_data['previous_week'][$field->id]['average']) ? $presentation_data['previous_week'][$field->id]['average'] : null;
        $variance = isset($current_week) && $current_week != 0 && isset($prev_week) && $prev_week != 0 ? round((($current_week - $prev_week) / $prev_week) * 100, 2).'%' : 'N/A';
        }

        $current_week_total += ($field->form_type == 'number' && isset($current_week)) ? $current_week : 0;
        $prev_week_total += ($field->form_type == 'number' && isset($prev_week)) ? $prev_week : 0;
    @endphp

        @if(isset($presentation_mode) && $presentation_mode == 'on')
            @if($field_id == 61) @dd($field) @endif
        @if(in_array($field->id, [56, 55]))
            @php
                $bsc_stats[$field->id] = [
        'title' => $field->label,
        'weeks' => [
            'Wk '.$week_in_focus->week_number => (isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : ''). (number_format($current_week) ?? 'N/A').(isset($field->unit, $current_week) && $field->unit != '₦' ? $field->unit : ''),
           'Wk '.$previousWeek->week_number => (isset($field->unit, $prev_week) && $field->unit == '₦' ? '₦' : '').(number_format($prev_week) ?? 'N/A').(isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : '')
    ],
   'variance' => calculateVariance($current_week, $prev_week)
    ];
            @endphp
            <script>
                bscStats[{{ $field->id }}] = @json($bsc_stats[$field->id]);
            </script>
        @endif

        @if(in_array($field->id, $stat_fields) && ($logged_in_user->can('see all business score cards') || in_array($logged_in_user->email, ['ailemen.arumemi-johnson@arikair.com', 'henry.ejiogu@arikair.com'])))
            @php
            $arr_position = array_search($field->id, $stat_fields);

            $forecasted = isset($presentation_data['previous_week'][$forecast_fields[$arr_position]])  && isset($presentation_data['previous_week'][$forecast_fields[$arr_position]]['total']) ? $presentation_data['previous_week'][$forecast_fields[$arr_position]]['total'] : (isset($presentation_data['previous_week'][$forecast_fields[$arr_position]]) && isset($presentation_data['previous_week'][$forecast_fields[$arr_position]]['average']) ? $presentation_data['previous_week'][$forecast_fields[$arr_position]]['average'] : null);
            $stat_variance = calculateVariance($current_week, $forecasted);
            $colour = isset($current_week, $forecasted) ? ($current_week >= $forecasted ? 'text-success' : 'text-danger') : '';
            @endphp
            <script>
                commRevenueStats = commRevenueStats + `<div class="col-md-3">
                    <div class="card">
                        <div class="card-header border-0" style="">
                            <h4 class="mb-0" style="display:inline;">{{ $field->label }}</h4>
                                    {!!  $stat_variance  !!}

                        </div>
                        <div class="card-body pt-0 pb-1">
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted mb-0">Forecast for Wk {{ $week_in_focus->week_number }}</p>
                                    <strong class="d-lg-none">{{ $forecasted ? (isset($field->unit, $forecasted) && $field->unit == '₦' ? '₦' : '').number_format($forecasted).(isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : '') : 'N/A' }}</strong>
                                    <h{{ $field_id == 57 ? '5' : '4' }} class="text-bold d-none d-lg-block">{{ $forecasted ? (isset($field->unit, $forecasted) && $field->unit == '₦' ? '₦' : '').number_format($forecasted).(isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : '') : 'N/A' }}</h{{ $field_id == 57 ? '5' : '4' }}>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-0">Actual Wk {{ $week_in_focus->week_number }}</p>
                                    <strong class="d-lg-none {{ $colour }}">{{ isset($current_week) ? (isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : '').number_format($current_week).(isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : '') : 'N/A' }}</strong>
                                    <h{{ $field_id == 57 ? '5' : '4' }} class="text-bold d-none d-lg-block {{ $colour }}">{{ isset($current_week) ? (isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : '').number_format($current_week).(isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : '') : 'N/A' }}</h{{ $field_id == 57 ? '5' : '4' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            </script>
        @endif
        @endif
    <tr>
        <td @if($field->id == 210) class="bg-success" @endif>{{ $field->label }}</td>
        <td>{{ isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : ''}}{{ number_format($current_week) ?? 'N/A'}}{{ isset($field->unit, $current_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
        <td>{{ isset($field->unit, $prev_week) && $field->unit == '₦' ? '₦' : ''}}{{ number_format($prev_week) ?? 'N/A'}}{{ isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
        <td @if($variance < 0 && $field->id != 211)  style="color: #DF356F"
            @elseif($variance < 0 && $field->id == 211) style="color: #1d1b1b"
            @endif>{{ $variance}}</td>
    </tr>
        @if($field->id == 58)
{{--            Adding last minute cancellations --}}
        <tr>
            <td colspan="4" align="center" class="bg-secondary">Last Minutes Cancellations/ No Show Stat vis a  vis Overbooking Performance Analysis </td>
        </tr>
        @endif
        @if($field->id == 211)
        <tr>
            <td colspan="4" align="center" class="bg-secondary">Forecasts for Subsequent Weeks</td>
        </tr>
        @endif
@endforeach
    </tbody>
</table>


@if(!isset($no_daily))
<div class="row mt-3">
    <div class="col-12">
        @include('frontend.business_goals.dailies._5')
    </div>
</div>
@endif
