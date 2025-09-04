@extends('frontend.layouts.app')

@push('after-styles')
    <style>
        .scrollable-div {
            height: 550px; /* Change this value to set the desired fixed height */
            overflow: auto; /* This will enable vertical scrolling if content exceeds the height */
        }

        thead {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 9;
        }

        .sticky-column {
            position: sticky;
            left: 0;
            z-index: 10;
            background-color: #f2f2f2;
        }

        th {
            min-width: 10rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form method="GET" action="{{ route('frontend.aircraft_status.index') }}" class="form-inline mb-4">
                    <div class="row">
                        <div class="col-8">
                    <div class="form-group">
                        <input type="date" id="date" name="for_date" class="form-control" max="{{ now()->toDateString() }}" value="{{ $date }}">
                        <label for="date" class="mr-2">Select Date:</label>
                    </div>
                        </div>
                    <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">View</button>
                    </div>
                    </div>
                </form>
            </div>
        </div>

@php
$datalists = [
  'LOCATION' => [
      'LAGOS',
      'ETHIOPIA',
      'SOUTH AFRICA',
      'ABUJA',
      'PORT HARCOURT',
      'DELTA',
],
'STATUS' => [
    'SERVICEABLE',
    'MAINTENANCE',
    'STORAGE',
    'UNSERVICEABLE',
]
];
@endphp

        @foreach($fleets as $key => $fleet)
        <div class="row">
            <div class="col">
        <div class="card mb-4">
            <div class="card-header">
                <h3>{{ $key }} Fleet Status for {{ \Carbon\Carbon::parse($date)->format('l jS \\of F Y') }}</h3>
            </div>
            <div class="card-body table-responsive scrollable-div p-0">
                <table class="table table-bordered table-hover table-striped table-striped-columns">
                    <thead class="thead-light shadow">
                    <tr>
                        <th class="bg-light sticky-column">Checklist</th>
                        @foreach($fleet['aircrafts'] as $aircraft)
                            <th class="bg-light">{{ $aircraft->registration_number }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fleet['checklist'] as $item)
                        <tr>
                            <th class="sticky-column shadow bg-white">{{ $item->label }}</th>
                            @foreach($fleet['aircrafts'] as $aircraft)
                                @php $submission = $submissions[$aircraft->id . '_' . $item->id] ?? null; @endphp
                                    <td class="">
@if($item->form_type === 'text')
    @if(in_array($item->label, ['STATUS', 'LOCATION']))
        <select class="form-control"
                data-checklist-id="{{ $item->id }}"
                data-aircraft-id="{{ $aircraft->id }}"
                data-date="{{ $date }}"
                onchange="saveStatus(this)"
                    >
            <option {{ $submission ? '' : 'selected' }} disabled>Select a {{ $item->label }}</option>
            @foreach($datalists[$item->label] as $list_item)
                <option {{ $submission && $list_item == $submission->item_value ? 'selected' : '' }}>{{ $list_item }}</option>
                @endforeach
        </select>
                                            @else
    <textarea class="form-control"
              rows="1"
              data-checklist-id="{{ $item->id }}"
              data-aircraft-id="{{ $aircraft->id }}"
              data-date="{{ $date }}"
              onchange="saveStatus(this)">{{ $submission ? $submission->item_value : '' }}</textarea>
                                            @endif
@else
    <input type="{{ $item->form_type === 'date' ? 'date' : 'number' }}"
           class="form-control"
           data-checklist-id="{{ $item->id }}"
           data-aircraft-id="{{ $aircraft->id }}"
           data-date="{{ $date }}"
           value="{{ $submission ? $submission->item_value : '' }}"
           onchange="saveStatus(this)">
@endif
</td>

                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            </div>
        </div>

        @endforeach

    </div>
@endsection

@push('after-scripts')
    <script>
        function saveStatus(element) {
            let data = {
                checklist_id: element.dataset.checklistId,
                aircraft_id: element.dataset.aircraftId,
                item_value: element.value,
                for_date: element.dataset.date
            };

            console.log(Date.now() + ' - Item value: ' + data.item_value);

            fetch('{{ route("frontend.aircraft_status.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Saved successfully');
                        showInstantToast(element.value + ' saved')
                    }
                }).catch(error => console.error('Error:', error));
        }

    </script>
@endpush
