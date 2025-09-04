{{--Revenue Management--}}

@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="table table-hover table-sm" id="ba_{{ $business_area_id }}">
    <thead class="bg-secondary">
    <tr>
        <th colspan="{{ count($dates) + 1 }}" class="bg-navy">{{ $business_area->name }} Report {{ $for_date->toDateString() }}</th>
    </tr>
    @php
        $custom_order = [49,50,51,52,53,54,55,56,57,58,207,208,209,210,211,345,346,347,348];
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
    <tbody>
    @foreach($custom_order as $order)
        @php
            $form_field = $form_fields->find($order);
            $colour = $alt_colours[($loop->even ? 2 : 1)];
        @endphp
        <tr>
            <th @if($form_field->id == 210) class="bg-success" @endif>{{ $form_field->label }}</th>
            @foreach($dates as $date => $date_object)
                @php
                    $field_data = $data_points[$date]['data']->where('score_card_form_field_id', $order)->first();
                @endphp

                <td
                    style="border: 2px solid #bab9b9;
                    @if(isset($largest_value) && $form_field->form_type == 'number' && isset($field_data) && isset($field_data->data_value))
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
        @if($order == 58)
            {{--            Adding last minute cancellations --}}
            <tr>
                <td colspan="{{ count($dates) + 1 }}" align="center" class="bg-secondary">Last Minutes Cancellations/ No Show Stat vis a vis Overbooking Performance Analysis </td>
            </tr>
        @endif
        @if($order == 211)
            <tr>
                <td colspan="{{ count($dates) + 1 }}" align="center" class="bg-secondary">Forecasts for Subsequent Weeks</td>
            </tr>
        @endif
    @endforeach

    </tbody>
</table>
