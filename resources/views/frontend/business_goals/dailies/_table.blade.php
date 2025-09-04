
@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="table table-hover table-sm" id="ba_{{ $business_area_id }}">
    <thead class="bg-secondary">
    <tr>
        <th colspan="{{ count($dates) + 1 }}" class="bg-navy">{{ $business_area->name }} Daily Report {{ $end_date->format('l jS') }} to {{ $for_date->format('l jS \\of F Y') }}</th>
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
        @if($form_field)
        <tr>
            <th>{{ $form_field->label }}</th>
            @foreach($dates as $date => $date_object)
                @php
                    $field_data = $data_points[$date]['data']->where('score_card_form_field_id', $order)->first();
                @endphp

                <td
                    style="border: 2px solid #bab9b9;
                    @if(isset($largest_value) && $form_field->form_type == 'number' && isset($field_data) && isset($field_data->data_value) && $largest_value[$form_field->id] != 0 && is_numeric($largest_value[$form_field->id]))
                    @php
                        $percentage = ($field_data->data_value / $largest_value[$form_field->id]) * 100;
                    @endphp
                        background: linear-gradient(0deg, {{ $colour }} {{ $percentage }}%, rgba(255,255,255,1) {{ $percentage }}%);
                    @endif
                        ">
                    @if(isset($field_data) && isset($field_data->data_value))
                        @if(isset($form_field->unit) && $form_field->unit == '₦'){{ $form_field->unit }}@endif{{ $field_data->data_value }}@if(isset($form_field->unit) && $form_field->unit != '₦'){{ $form_field->unit }}@endif
                    @else
                        {{ 'N/A' }}
                    @endif
                </td>
            @endforeach
        </tr>
        @endif
    @endforeach

    </tbody>
</table>
