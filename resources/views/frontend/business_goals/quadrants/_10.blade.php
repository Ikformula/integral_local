{{-- Internal Control --}}
@push('after-styles')
    <style>
        td {
            text-align: right;
        }

        .bg-blue-light {
            background-color: #a3c3f3;
        }

        .bg-lighter-blue {
            background-color: #cbddf8;
        }
    </style>
@endpush
<div class="row justify-content-center">
    <div class="@if(isset($presentation_mode) && $presentation_mode == 'on') col-md-10 @else col-md-6 @endif">

@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="table table-hover table-sm table-bordered" id="ba_{{ $business_area_id }}">
    <thead class="bg-secondary">
    <tr>
        <th colspan="5" class="bg-navy">{{ $business_area->name }} Report</th>
    </tr>
    <tr class="bg-blue-light text-dark">
        <th class=" text-center">TRANSACTIONS</th>
        <th class=" text-center">Wk {{ $week_in_focus->week_number }}
{{--            <small>({{ $week_in_focus->from_date->day }} - {{ $week_in_focus->to_date->toDateString() }})</small>--}}
        </th>
        <th class=" text-center">Wk {{ $week_in_focus->week_number - 1 }}
{{--            <small>({{ $presentation_data['titles']['last week'] }})</small>--}}
        </th>
        <th>Amount Variance</th>
        <th>Percentage Variance</th>
    </tr>
    </thead>

    @php
$currencies = ['NGN', 'USD', 'EURO', 'GBP'];
$num_and_currencies = array_merge(['Number'], $currencies);
    $sections = [
  'Payment Request' => $num_and_currencies,
  'Cash Advance' =>  $num_and_currencies,
  'Retirement' =>  $num_and_currencies,
  'Salary Advance' => ['Number', 'NGN'],
  //['Cost Savings' => 'NGN', 'USD', 'EURO', 'GBP'],

];
    $sum = [];
$sum['current_week'] = $sum['prev_week'] = [];



    @endphp

    @foreach($sections['Payment Request'] as $notation)
        @php
            $sum['current_week'][$notation] = $sum['prev_week'][$notation] = 0;
        @endphp
    @endforeach

        <tbody>
    @foreach($sections as $section => $arr)
        <tr class="bg-gray">
            <th>{{ strtoupper($section) }}</th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($num_and_currencies as $notation)
        @php
            $field = $form_fields_collection->where('label', $section.' '.$notation)->first();
@endphp

        @if(is_object($field))
            @php
$current_week = isset($presentation_data['current_week'][$field->id]) ? $presentation_data['current_week'][$field->id]['total'] : null;
$prev_week = isset($presentation_data['previous_week'][$field->id]) ? $presentation_data['previous_week'][$field->id]['total'] : null;
$sum['current_week'][$notation] += isset($current_week) ? $current_week : 0;
$sum['prev_week'][$notation] += isset($prev_week) ? $prev_week : 0;
$variance = varianceDisplay($current_week, $prev_week);
        @endphp
    <tr>
    <td class="text-center">{{ $notation }}</td>
    <td @if($notation == 'Number') class="text-center" @endif>{{ icuNumFormatter($current_week, $notation) }}</td>
    <td @if($notation == 'Number') class="text-center" @endif>{{ icuNumFormatter($prev_week, $notation) }}</td>
        <td style="color: {{ $variance['colour'] }}">{{ $variance['variance'] }}</td>
        <td style="color: {{ $variance['colour'] }}">{{ $variance['percentage'] }}</td>
    </tr>
            @endif
            @endforeach
@endforeach
    <tr>
        <th>TOTAL</th>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @foreach($num_and_currencies as $notation)
        @php
            $variance = varianceDisplay($sum['current_week'][$notation], $sum['prev_week'][$notation]);
        @endphp
                    <tr class="bg-blue-light text-center">
                        <th>{{ $notation }}</th>
                    <th @if($notation == 'Number') class="text-center" @else  class="text-right" @endif>{{ icuNumFormatter($sum['current_week'][$notation], $notation) }}</th>
                    <th @if($notation == 'Number') class="text-center" @else  class="text-right" @endif>{{ icuNumFormatter($sum['prev_week'][$notation], $notation) }}</th>
                        <td style="color: {{ $variance['colour'] }}">{{ $variance['variance'] }}</td>
                        <td style="color: {{ $variance['colour'] }}">{{ $variance['percentage'] }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-white"> - </td>
                </tr>
                    <tr class="bg-gray">
                        <th>MEMO REVIEWED</th>
                        @php
                            $field = $form_fields_collection->where('label', 'Memo Reviewed')->first();
            @endphp

            @if(is_object($field))
                @php
                    $current_week = isset($presentation_data['current_week'][$field->id]) ? $presentation_data['current_week'][$field->id]['total'] : null;
                    $prev_week = isset($presentation_data['previous_week'][$field->id]) ? $presentation_data['previous_week'][$field->id]['total'] : null;
            $variance = varianceDisplay($current_week, $prev_week);
                @endphp
            <td class="text-center">{{ $current_week }}</td>
            <td class="text-center">{{ $prev_week }}</td>
            <td style="color: {{ $variance['colour'] }}">{{ $variance['variance'] }}</td>
            <td style="color: {{ $variance['colour'] }}">{{ $variance['percentage'] }}</td>
                @endif
        </tr>


    @php
    $observations_completed_current_field =  $form_fields_collection->where('label', 'Observations Completed JSON')->first();
    $observations_ongoing_current_field =  $form_fields_collection->where('label', 'Observations Ongoing JSON')->first();

    $current_week = isset($presentation_data['current_week'][$observations_completed_current_field->id]) ? json_decode($presentation_data['current_week'][$observations_completed_current_field->id]['total']) : null;
    $prev_week = isset($presentation_data['previous_week'][$observations_completed_current_field->id]) ? json_decode($presentation_data['previous_week'][$observations_completed_current_field->id]['total']) : null;

    $current_week_ongoing = isset($presentation_data['current_week'][$observations_ongoing_current_field->id]) ? json_decode($presentation_data['current_week'][$observations_ongoing_current_field->id]['total']) : null;
    $prev_week_ongoing = isset($presentation_data['previous_week'][$observations_ongoing_current_field->id]) ? json_decode($presentation_data['previous_week'][$observations_ongoing_current_field->id]['total']) : null;


    $current_week_arr = $prev_week_arr = $current_week_arr_ongoing = $prev_week_arr_ongoing = $dept_keys = $dept_keys_ongoing = [];
    $current_week_completed_total = $prev_week_completed_total = $current_week_ongoing_total = $prev_week_ongoing_total = 0;
    @endphp

    {{--    Splitting the if conditionals 4:00pm, 26th of Nov, 2024 - start --}}
    @if(isset($current_week) || isset($prev_week) || isset($current_week_ongoing) || isset($prev_week_ongoing))

        {{-- Initialize totals and arrays --}}
        @php
            $dept_keys = [];
            $dept_keys_ongoing = [];
            $current_week_completed_total = 0;
            $prev_week_completed_total = 0;
            $current_week_ongoing_total = 0;
            $prev_week_ongoing_total = 0;

            $current_week_arr = [];
            $prev_week_arr = [];
            $current_week_arr_ongoing = [];
            $prev_week_arr_ongoing = [];
        @endphp

        {{-- Process Completed --}}
        @if(isset($current_week))
            @foreach($current_week as $current_week_data)
                @php
                    $current_week_data = (array) $current_week_data;
                    $dept_keys[] = array_key_first($current_week_data);
                    $current_week_arr[array_key_first($current_week_data)] = $current_week_data[array_key_first($current_week_data)];
                    $current_week_completed_total += $current_week_arr[array_key_first($current_week_data)];
                @endphp
            @endforeach
        @endif

        @if(isset($prev_week))
            @foreach($prev_week as $prev_week_data)
                @php
                    $prev_week_data = (array) $prev_week_data;
                    $dept_keys[] = array_key_first($prev_week_data);
                    $prev_week_arr[array_key_first($prev_week_data)] = $prev_week_data[array_key_first($prev_week_data)];
                    $prev_week_completed_total += $prev_week_arr[array_key_first($prev_week_data)];
                @endphp
            @endforeach
        @endif

        {{-- Process Ongoing --}}
        @if(isset($current_week_ongoing))
            @foreach($current_week_ongoing as $current_week_data)
                @php
                    $current_week_data = (array) $current_week_data;
                    $dept_keys_ongoing[] = array_key_first($current_week_data);
                    $current_week_arr_ongoing[array_key_first($current_week_data)] = $current_week_data[array_key_first($current_week_data)];
                    $current_week_ongoing_total += $current_week_arr_ongoing[array_key_first($current_week_data)];
                @endphp
            @endforeach
        @endif

        @if(isset($prev_week_ongoing))
            @foreach($prev_week_ongoing as $prev_week_data)
                @php
                    $prev_week_data = (array) $prev_week_data;
                    $dept_keys_ongoing[] = array_key_first($prev_week_data);
                    $prev_week_arr_ongoing[array_key_first($prev_week_data)] = $prev_week_data[array_key_first($prev_week_data)];
                    $prev_week_ongoing_total += $prev_week_arr_ongoing[array_key_first($prev_week_data)];
                @endphp
            @endforeach
        @endif

        {{-- Combine unique department keys --}}
        @php
            $depts = array_unique(array_merge($dept_keys, $dept_keys_ongoing));
        @endphp

        {{-- Display Observations --}}
        <tr class="bg-gray">
            <th>OBSERVATIONS</th>
            <th class="text-center">{{ $current_week_completed_total + $current_week_ongoing_total }}</th>
            <th class="text-center">{{ $prev_week_completed_total + $prev_week_ongoing_total }}</th>
            @php
            $variance = varianceDisplay($current_week_completed_total + $current_week_ongoing_total, $prev_week_completed_total + $prev_week_ongoing_total);
            @endphp
            <td style="color: {{ $variance['colour'] }}">{{ $variance['variance'] }}</td>
            <td style="color: {{ $variance['colour'] }}">{{ $variance['percentage'] }}</td>
        </tr>

        {{-- Display Completed --}}
        <tr class="bg-lighter-blue">
            <th>COMPLETED</th>
            <th class="text-center">{{ $current_week_completed_total }}</th>
            <th class="text-center">{{ $prev_week_completed_total }}</th>
            @php
                $variance = varianceDisplay($current_week_completed_total, $prev_week_completed_total);
            @endphp
            <td style="color: {{ $variance['colour'] }}">{{ $variance['variance'] }}</td>
            <td style="color: {{ $variance['colour'] }}">{{ $variance['percentage'] }}</td>
        </tr>

        @if(!empty($depts))
            @foreach($depts as $dept_key)
                <tr>
                    <th class="text-center">{{ $dept_key }}</th>
                    <td class="text-center">{{ $current_week_arr[$dept_key] ?? '0' }}</td>
                    <td class="text-center">{{ $prev_week_arr[$dept_key] ?? '0' }}</td>
                    @php
                        $variance = varianceDisplay($current_week_arr[$dept_key] ?? 0, $prev_week_arr[$dept_key] ?? 0);
                    @endphp
                    <td style="color: {{ $variance['colour'] }}">{{ $variance['variance'] }}</td>
                    <td style="color: {{ $variance['colour'] }}">{{ $variance['percentage'] }}</td>
                </tr>
            @endforeach
        @endif

        {{-- Display Ongoing --}}
        <tr class="bg-lighter-blue">
            <th>ON-GOING</th>
            <th class="text-center">{{ $current_week_ongoing_total }}</th>
            <th class="text-center">{{ $prev_week_ongoing_total }}</th>
            @php
                $variance = varianceDisplay($current_week_ongoing_total, $prev_week_ongoing_total);
            @endphp
            <td style="color: {{ $variance['colour'] }}">{{ $variance['variance'] }}</td>
            <td style="color: {{ $variance['colour'] }}">{{ $variance['percentage'] }}</td>
        </tr>

        @if(!empty($depts))
            @foreach($depts as $dept_key)
                <tr>
                    <th class="text-center">{{ $dept_key }}</th>
                    <td class="text-center">{{ $current_week_arr_ongoing[$dept_key] ?? '0' }}</td>
                    <td class="text-center">{{ $prev_week_arr_ongoing[$dept_key] ?? '0' }}</td>
                    @php
                        $variance = varianceDisplay($current_week_arr_ongoing[$dept_key] ?? 0, $prev_week_arr_ongoing[$dept_key] ?? 0);
                    @endphp
                    <td style="color: {{ $variance['colour'] }}">{{ $variance['variance'] }}</td>
                    <td style="color: {{ $variance['colour'] }}">{{ $variance['percentage'] }}</td>
                </tr>
            @endforeach
        @endif

    @endif
    {{--    Splitting the if conditionals 4:00pm, 26th of Nov, 2024 - end --}}

    <tr class="bg-gray">
            <th colspan="5">COST SAVINGS</th>
        </tr>

    @foreach($num_and_currencies as $notation)
        @php
            $field = $form_fields_collection->where('label', 'Cost Savings '.$notation)->first();
        @endphp

        @if(is_object($field))
            @php
                $current_week = isset($presentation_data['current_week'][$field->id]) ? $presentation_data['current_week'][$field->id]['total'] : null;
                $prev_week = isset($presentation_data['previous_week'][$field->id]) ? $presentation_data['previous_week'][$field->id]['total'] : null;
                $variance = varianceDisplay($current_week, $prev_week);

            @endphp
            <tr class="bg-blue-light">
                <th class=" text-center">{{ $notation }}</th>
                <th class=" text-right">{{ icuNumFormatter($current_week, $notation) }}</th>
                <th class=" text-right">{{ icuNumFormatter($prev_week, $notation) }}</th>
                <td style="color: {{ $variance['colour'] }}">{{ $variance['variance'] }}</td>
                <td style="color: {{ $variance['colour'] }}">{{ $variance['percentage'] }}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

    </div>
</div>

@if(!isset($no_daily))
<div class="row mt-3">
    <div class="col-12">
        @include('frontend.business_goals.dailies._10')
    </div>
</div>
@endif
