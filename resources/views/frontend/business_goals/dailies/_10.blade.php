{{--Internal Control--}}
@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )

@php
$amount_fields = [321, 322, 323, 324, 326, 327, 328, 329, 331, 332, 333, 334, 336, 340, 341, 342, 343]
@endphp
<table class="table table-hover table-sm" id="ba_{{ $business_area_id }}">
    <thead class="bg-secondary">
    <tr>
        <th colspan="{{ count($dates) + 1 }}" class="bg-navy">{{ $business_area->name }} Daily
            Report {{ $end_date->format('l jS') }} to {{ $for_date->format('l jS \\of F Y') }}</th>
    </tr>
    @php
        $custom_order = isset($custom_order) ? $custom_order : $form_fields->pluck('id')->toArray();
        $alt_colours = [
            1 => 'rgb(109,208,254, .37)',
            2 => 'rgb(253,150,109, .37)'
    ];
    @endphp
    <tr>
        <td></td>
        @foreach($dates as $date => $date_object)
            <td @if(strval($date) == strval($for_date->toDateString())) style="background-color: #616672" @endif>{{ $date }}</td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($custom_order as $order)
        @php
            $form_field = $form_fields->find($order);
            $colour = $alt_colours[($loop->even ? 2 : 1)];
        @endphp
        <tr>
            <th>@if(in_array($form_field->id, [338, 339])) {{ substr($form_field->label, 0, -4)  }} @else {{ $form_field->label }} @endif</th>
            @foreach($dates as $date => $date_object)
                @php
                    $field_data = $data_points[$date]['data']->where('score_card_form_field_id', $order)->first();
                @endphp

                <td
                    class="@if(in_array($form_field->id, $amount_fields)) text-right @else text-center @endif"
                    style="border: 2px solid #bab9b9;
                    @if(isset($largest_value) && $form_field->form_type == 'number' && isset($field_data) && isset($field_data->data_value) && $largest_value[$form_field->id] != 0 && is_numeric($largest_value[$form_field->id]))
                    @php
                        $percentage = ($field_data->data_value / $largest_value[$form_field->id]) * 100;
                    @endphp
                        background: linear-gradient(0deg, {{ $colour }} {{ $percentage }}%, rgba(255,255,255,1) {{ $percentage }}%);
                    @endif
                        ">
                    @if(isset($field_data) && isset($field_data->data_value))
                        @if(in_array($form_field->id, [338, 339]))
                            @php
                                $observations = json_decode($field_data->data_value);
$counter = 0;
                            @endphp
                            @foreach($observations as $field_value)
                            @php
                                $counter++;
                                $field_value = get_object_vars($field_value);
                                $this_observation_completed_department = array_key_first($field_value);
                            @endphp

                                <span class="text-gray-dark">{{ $this_observation_completed_department }}: </span> <strong> {{ $field_value[array_key_first($field_value)] }}</strong><br>
                            @endforeach
                        @else

                            @if(isset($form_field->unit) && $form_field->unit == '₦'){{ $form_field->unit }}@endif
                            @if(in_array($form_field->id, $amount_fields))
                                @php $unit = substr($form_field->label, -3); @endphp
                                {{ icuNumFormatter($field_data->data_value, $unit) }} {{ $unit }}
                            @else
                                {{ $field_data->data_value }}
                            @endif
                            @if(isset($form_field->unit) && $form_field->unit != '₦'){{ $form_field->unit }}@endif

                        @endif

                    @else
                        {{ 'N/A' }}
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach

    </tbody>
</table>
