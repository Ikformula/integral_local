{{-- Aircraft Status - Daily input --}}
@push('after-styles')
    <style>
        .bg-blue-light {
            background-color: #a3c3f3;
        }
        .bg-blue-green {
            background-color: #a3f3ae;
        }

        .B-737 {
            background-color: #a3c3f3;
        }

        .Q-400 {
            background-color: #a3f3ae;
        }

        th, td {
            max-width: 12rem;
        }
    </style>
    @endpush
<div class="row">
    <div class="col">
        <div class="card mb-4">
            <div class="card-header">
                <h4 >Technical - Aircraft Status Report For {{ \Carbon\Carbon::parse($date)->format('l jS \\of F Y') }}</h4>
            </div>
        </div>
    </div>
</div>
@php

    $statuses_colours = [
        'SERVICEABLE' => '#b2dcb2',
        'MAINTENANCE' => '#e7cab1',
        'STORAGE' => '#d0a1f0',
        'UNSERVICEABLE' => '#f2a1a1',
        '' => 'white'
    ];

$blue_collars = [
  'LH-ENGINE CURRENT FC',
  'LH-ENGINE CURRENT FH',
  'LH-LLP FC',
  'RH-ENGINE CURRENT FC',
  'RH-ENGINE CURRENT FH',
  'RH-LLP FC'
];

$llp_fields = [
    'LH-LLP FC',
    'RH-LLP FC',
    ];

$date_fields['B-737'] = [20, 22, 24, 26];
$date_fields['Q-400'] = [48, 50, 52, 54];

$due_date_statuses = \App\Services\AircraftMelDueDateChecking::checkAllMel();
@endphp

@foreach($fleets as $key => $fleet)
    @include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $key] )
    <div class="row mb-3">
        <div class="col">
            <div class="card mb-4" id="card_{{ $key }}">
{{--                <div class="card-header">--}}
{{--                    <h3>{{ $key }} Fleet</h3>--}}
{{--                </div>--}}
                <div class="card-body p-0">
{{--            <div class="p-0">--}}
                    <table class=" table-bordered table-hover table-striped-columns table-sm mx-auto" id="ba_{{ $key }}">
                        <thead class="shadow">
                        <tr>
                            <th class="bg-gray sticky-column shadow" rowspan="2">Checklist</th>
                            @foreach($fleet['aircrafts'] as $aircraft)
                                <th class="{{ $key }}"><a href="#excel_btn_ba_{{ $key }}">{{ $key }}</a></th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($fleet['aircrafts'] as $aircraft)
                                <th class="{{ $key }}"><a href="#excel_btn_ba_{{ $key }}">{{ $aircraft->registration_number }}</a></th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fleet['checklist'] as $item)
                            <tr class="@if(in_array($item->label, $blue_collars)) bg-blue-light @endif">
                                <th class="sticky-column text-nowrap">{{ $item->label }}</th>
                                @foreach($fleet['aircrafts'] as $aircraft)
                                    @php $submission = $submissions[$aircraft->id . '_' . $item->id] ?? null; @endphp
                                    <td style="
                                    @if($item->label == 'STATUS') background-color: {{ $submission && array_key_exists($submission->item_value, $statuses_colours) ? $statuses_colours[$submission->item_value] : 'white' }} @endif"
                                    class="
@if(in_array($item->label, $llp_fields) && isset($submission, $submission->item_value) && is_numeric($submission->item_value))
                                        {{ $submission->item_value <= 100 ? ($submission->item_value <= 50 ? 'bg-danger' : 'bg-warning' ) : '' }}
                                        @endif

    @if(in_array($item->id, $date_fields[$key]) && $submission && $submission->item_value)
bg-{{ $due_date_statuses[$aircraft->id][$item->id]['colour'] }}
    @endif
"
   >
       @if($item->form_type != 'date')
           {{ $submission ? $submission->item_value : '' }}
       @else
           @if($submission && $submission->item_value){{ \Carbon\Carbon::parse($submission->item_value)->toDateString() }} @endif
       @endif
   </td>
@endforeach
</tr>
@endforeach
</tbody>
</table>
{{--            </div>--}}
                    @if(\Illuminate\Support\Facades\Route::currentRouteName() != 'frontend.business_goals.multi.business.areas')
                        <div class="row justify-content-center">
                            <div class="col-md-5">
                                <a href="#excel_btn_ba_{{ $key }}" class="btn btn-block {{ $key }} m-3">Back to {{ $key }} Top</a>
                            </div>
                        </div>
                    @endif
</div>
</div>
</div>
</div>

@endforeach
