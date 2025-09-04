
{{--People & Organisational Development--}}
@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
@push('after-styles')
    <style>
        .bg-blue-light {
            background-color: #f6f8fb;
        }
    </style>
@endpush
@php
$custom_order = [
    16, 29, 386, 32, 34, 33, 31, 36, 35, 24
];
$percents = [];
$temp_staff_current = $temp_staff_previous = $temp_staff_variance = 0;
// $drugs_testing = [26, 25];


@endphp
<table class="table-hover table-bordered table-sm mx-auto" id="ba_{{ $business_area_id }}">
    <thead class="bg-secondary">
    <tr>
        <th colspan="5" class="bg-navy">{{ $business_area->name }}  Report</th>
    </tr>
    <tr>
        <td></td>
        <td>Wk {{ $week_in_focus->week_number }}
        </td>
        <td>Wk {{ $previousWeek->week_number }}
        </td>
        <td>Variance</td>
    </tr>
    </thead>
    <tbody>

    @foreach($custom_order as $order)
    @php
    $field = $form_fields_collection->where('id', $order)->first();
    $current_week = $presentation_data['current_week'][$order]['total'] ?? ($presentation_data['current_week'][$order]['average'] ?? 'N/A');
    $previous_week = $presentation_data['previous_week'][$order]['total'] ?? ($presentation_data['previous_week'][$order]['average'] ?? 'N/A');
    $variance_data = varianceDisplay($current_week, $previous_week);
    @endphp
    @if($order == 32)
        <tr class="bg-blue-light ">
            <th class="">Temporary Staff
            </th>
            <td id="temp-staff-current-week">N/A</td>
            <td id="temp-staff-previous-week">N/A</td>
            <td id="temp-staff-variance">N/A</td>
        </tr>
        @endif
    <tr class="
@if(in_array($order, [32, 34, 33])) text-secondary bg-blue-light
@php
        $temp_staff_current += (is_numeric($current_week) ? $current_week : 0);
        $temp_staff_previous += (is_numeric($previous_week) ? $previous_week : 0);
    @endphp
    @endif">

        <th>{{ $field->label }}</th>
        <td>{{ $current_week }}{{ $order == 386 && $current_week != 'N/A' ? '%' : '' }}</td>
        <td>{{ $previous_week }}{{ $order == 386 && $previous_week != 'N/A' ? '%' : '' }}</td>
         <td>{{ $variance_data['display text'] }}</td>
    </tr>

    @endforeach

    <tr>
        <th colspan="5">Drugs & Alcohol</th>
    </tr>

    @php
        $field = $form_fields_collection->where('id', 26)->first();
        $current_week = $presentation_data['current_week'][26]['total'] ?? 'N/A';
        $previous_week = $presentation_data['previous_week'][26]['total'] ?? 'N/A';
        $variance_data = varianceDisplay($current_week, $previous_week);
    @endphp
    <tr>
        <th>Total Number of Staff tested</th>

        <td>{{ $current_week }}</td>
        <td>{{ $previous_week }}</td>
        <td>{{ $variance_data['display text'] }}</td>
    </tr>


    @php
        $field = $form_fields_collection->where('id', 25)->first();
        $current_week = $presentation_data['current_week'][25]['total'] ?? 'N/A';
        $previous_week = $presentation_data['previous_week'][25]['total'] ?? 'N/A';
        $variance_data = varianceDisplay($current_week, $previous_week);
    @endphp
    <tr>
        <th>Total Number of Staff tested positive</th>

        <td>{{ $current_week }}</td>
        <td>{{ $previous_week }}</td>
        <td>{{ $variance_data['display text'] }}</td>
    </tr>

    <tr style="background-color: #c2c2ff;">
        <th>LOCATION</th>

        <th colspan="2">NUMBER OF STAFF</th>
        <th></th>
    </tr>
    @php
    $current_week_location_total = $previous_week_location_total = 0;
    @endphp
    @for($i = 370; $i <= 384; $i++)
        @php
            $field = $form_fields_collection->where('id', $i)->first();
            $current_week = $presentation_data['current_week'][$i]['total'] ?? 'N/A';
            $previous_week = $presentation_data['previous_week'][$i]['total'] ?? 'N/A';
            $variance_data = varianceDisplay($current_week, $previous_week);

        $current_week_location_total += is_numeric($current_week) ? $current_week : 0;
        $previous_week_location_total += is_numeric($previous_week) ? $previous_week : 0;
        @endphp
        <tr>
            <th>{{ $field->label }}</th>

            <td>{{ $current_week }}</td>
            <td>{{ $previous_week }}</td>
            <td>{{ $variance_data['display text'] }}</td>
        </tr>
    @endfor
    <tr>
        <th>TOTAL</th>

        <th>{{ $current_week_location_total != 0 ? $current_week_location_total : 'N/A' }}</th>
        <th>{{ $previous_week_location_total != 0 ? $previous_week_location_total : 'N/A' }}</th>
        @php $variance_data = $current_week_location_total != 0 && $previous_week_location_total != 0 ? varianceDisplay($current_week_location_total, $previous_week_location_total) : 'N/A'; @endphp
        <th>{{ $variance_data['display text'] ?? 'N/A' }}</th>
    </tr>
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', function (){
        let temp_current = {{ $temp_staff_current }};
        let temp_previous = {{ $temp_staff_previous }};

        if(temp_current != 0){
            document.getElementById('temp-staff-current-week').innerHTML = temp_current;
        }
        if(temp_previous != 0){
            document.getElementById('temp-staff-previous-week').innerHTML = temp_previous;
        }

        document.getElementById('temp-staff-variance').innerHTML = '{{ strtolower(varianceDisplay($temp_staff_current, $temp_staff_previous)['display text']) }}';

    }, false);
</script>

@if(!isset($no_daily))
<div class="row mt-3">
    <div class="col-12">
        @include('frontend.business_goals.dailies._1')
    </div>
</div>
@endif
