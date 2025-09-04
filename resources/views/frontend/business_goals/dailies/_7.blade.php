@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="table table-hover table-sm mb-3" id="ba_{{ $business_area_id }}">
    <thead class="bg-secondary">
    <tr>
        <th colspan="{{ count($dates) + 2 }}" class="bg-navy">{{ $business_area->name }} Report {{ $for_date->toDateString() }}</th>
    </tr>
    @php
        $custom_order = isset($custom_order) ? $custom_order : $form_fields->pluck('id')->toArray();
        $alt_colours = [
            1 => 'rgb(109,208,254, .37)',
            2 => 'rgb(253,150,109, .37)'
    ];
        $items = [
    'FCCPC',
    'NCAA',
    'PCC',
    'LEGAL DEMAND',
    'EMAILS',
    'CALLS'
];
    @endphp
    <tr>
        <td colspan="2"></td>
        @foreach($dates as $date => $date_object)
            <td @if(strval($date) == strval($for_date->toDateString())) style="background-color: #616672" @endif>{{ $date }}</td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
    <tr>
        <th colspan="2">{{ $item }}</th>
    @foreach($dates as $date => $date_object)
{{--        @dd($data_points[strval($date_object->toDateString())])--}}
        @if(isset($data_points[strval($date)]))

        @php
        $date_string = strval($date);
        $field = $form_fields->where('label', $item)->first();
        $data_point = $data_points[$date_string]['data']->where('score_card_form_field_id', $field->id)->first()
        @endphp
        <td>{{ $data_point->data_value ?? '' }}</td>
            @else
            <td></td>
            @endif
            @endforeach
    </tr>
    @endforeach
</tbody>
</table>

<table class="table table-hover table-sm table-bordered" id="ba_2_{{ $business_area_id }}">
    <thead class=" text-center">
    <tr style="background-color: #B3CEEB">
        <th rowspan="2" class="text-left">Escalation Type</th>
        <th rowspan="2" class="text-left">Escalation Nature</th>
        @foreach($dates as $date => $date_object)
            @if($loop->even)
                @php $bg_colour = 'bg-gray disabled'; @endphp
            @else
                @php $bg_colour = 'bg-light'; @endphp
            @endif
            <th colspan="3" @if(strval($date) == strval($for_date->toDateString())) class="text-white" style="background-color: #616672" @else  class="{{ $bg_colour }}" @endif>{{ $date }}</th>
        @endforeach
    </tr>
    <tr>
        @foreach($dates as $date => $date_object)
            @if($loop->even)
                @php $bg_colour = 'bg-gray disabled'; @endphp
            @else
                @php $bg_colour = 'bg-light'; @endphp
            @endif
            <th class="bg-gray">Total</th>
            <th class="{{ $bg_colour }}">Closed</th>
            <th class="{{ $bg_colour }}">Pending</th>
        @endforeach
    </tr>
    </thead>
    @php
        $data = commercialCustRelationsArr();
    @endphp
    <tbody>
    @foreach($data as $item)
        @php
            $field = $form_fields->where('label', $item['Escalation type'].' (pending)')->first();
            $field_closed = $form_fields->where('label', $item['Escalation type'].' (closed)')->first();
        @endphp
        <tr>
            <td>{{ $item['Escalation type'] }}</td>
            <td>{{ $item['Escalation Nature'] }}</td>
            @foreach($dates as $date => $date_object)
                @php
                    $date_string = strval($date);
                    $pending = $data_points[$date_string]['data']->where('score_card_form_field_id', $field->id)->first();
                    $closed = $data_points[$date_string]['data']->where('score_card_form_field_id', $field_closed->id)->first();
                    $total = ($closed->data_value ?? 0) + ($pending->data_value ?? 0);
                @endphp
                @if($loop->even)
                    @php $bg_colour = 'bg-light'; @endphp
                @else
                    @php $bg_colour = ''; @endphp
                @endif
            <th class="{{ $bg_colour }}">{{ $total }}</th>
                <td class="{{ $bg_colour }}">{{ $closed->data_value ?? 0 }}</td>
                <td class="{{ $bg_colour }}">{{ $pending->data_value ?? 0 }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
