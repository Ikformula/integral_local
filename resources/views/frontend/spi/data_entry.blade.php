@extends('frontend.layouts.app')

@section('title', 'SPI Report Entry')


@section('content')
    <div class="container-fluid mt-4">
        <form method="get">
        <div class="row">
            <div class="col-9">
                <div class="form-group">
                    <input type="date" name="for_date" value="{{ substr($for_date->toDateString(), 0, 10) }}" class="form-control">
                </div>
            </div>
            <div class="col-3">
                <button type="submit" class="bg-navy btn btn-block">Set Date of Report</button>
            </div>
        </div>
        </form>
        @php
            $formattedForDate = $for_date->format('Y-m-d'); // Format for comparison
        @endphp
        <div class="row">
            <div class="col">
                @foreach($sectors as $sector)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2>{{ $sector->sector_name }}</h2>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover table-stripped">
                                <thead class="thead-light">
                                <tr>
                                    <th>OBJECTIVES</th>
                                    <th>INDICATORS</th>
                                    <th>METRICS ENTRIES</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sector->objectives as $objective)
                                    @php
                                        $firstIndicator = true;
                                    @endphp
                                    @foreach($objective->indicators as $indicator)
                                        <tr>
                                            <!-- Objectives column -->
                                            @if($firstIndicator)
                                                <td rowspan="{{ $objective->indicators->count() }}"
                                                    style="{{ empty($objective->objectives) ? 'background-color: #f8d7da;' : '' }}">
                                                    <strong>{{ $objective->objectives }}</strong>
                                                </td>
                                            @php $firstIndicator = false; @endphp
                                        @endif

                                        <!-- Indicators column -->
                                            <td style="{{ empty($indicator->indicator) ? 'background-color: #f8d7da;' : '' }}">
                                                {{ $indicator->indicator }}
                                            </td>

                                            <!-- Metrics column with each metric and unit listed -->
                                            <td>
                                                <ul class="list-unstyled mb-0">
                                                    @forelse($indicator->metrics->where('id', '!=', 82) as $metric)
                                                        <li style="{{ empty($metric->metric) ? 'background-color: #f8d7da;' : '' }}">
                                                            <strong>{{ $metric->metric }}</strong> <em>({{ $metric->unit }})</em>
                                                        @php
                                                            // Find the entry for the given date
                                                            $latestEntry = $metric->entries->where('for_date', $formattedForDate)->first();
                                                            $entryData = $latestEntry ? json_decode($latestEntry->entry_data, true) : [];
                                                            $formula = $metric->metric_formula ? json_decode($metric->metric_formula, true) : null;
                                                            $parameters = $formula['parameters'] ?? [['letter' => 'a', 'title' => $metric->metric]];
                                                        @endphp

                                                            <!-- Metric Entry Form Section -->
                                                            <div class="card mt-3">
                                                                <div class="card-header">
                                                                    <h5>Submit Entry for {{ $metric->metric }}.</h5>
                                                                    @if(isset($formula['operation']) && $formula['operation'] != 'a')<p>Formula: {{ $formula['operation'] }}</p>@endif
                                                                </div>
                                                                <div class="card-body">
                                                                    <form id="entry-form-{{ $metric->id }}" onsubmit="return false;">
                                                                        <input type="hidden" name="metric_id" value="{{ $metric->id }}">
                                                                        <input type="hidden" name="for_date" value="{{ $formattedForDate }}">

                                                                        <!-- Parameter Fields -->
                                                                        @foreach($parameters as $index => $parameter)
                                                                            <div class="form-row mb-2 parameter-row">
                                                                                <div class="col-12">
                                                                                    <label>{{ $parameter['title'] }} ({{ $parameter['letter'] }})</label>
                                                                                    <input type="number"
                                                                                           step="0.001"
                                                                                           class="form-control"
                                                                                           name="parameters[{{ $parameter['letter'] }}]"
                                                                                           value="{{ $entryData[$parameter['letter']] ?? '' }}"
                                                                                           placeholder="Enter value for {{ $parameter['title'] }}">
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                        <button type="submit" class="btn bg-navy btn-sm btn-block" id="entry-btn-{{ $metric->id }}" onclick="submitEntry({{ $metric->id }})">Save Entry</button>

                                                                        @if(isset($formula['operation']) && $formula['operation'] != 'a')
                                                                        <div class="form-group mt-2 mb-0">
                                                                            <input type="number"
                                                                                   step="0.001"
                                                                                   class="form-control"
                                                                                   name="amount" id="metric-{{ $metric->id }}-amount"
                                                                                   aria-describedby="helpId"
                                                                                   value="{{ $latestEntry->amount ?? '' }}"
                                                                                    readonly>
                                                                            <small id="helpId"
                                                                                   class="form-text text-muted">Computed Value @if($metric->unit == 'percentage')(%)@endif</small>
                                                                        </div>
                                                                        @endif
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @empty
                                                        <li style="background-color: #f8d7da;">No metrics available</li>
                                                    @endforelse

                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach


                                </tbody>
                            </table>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        function submitEntry(metricId) {
            const form = document.getElementById(`entry-form-${metricId}`);
            const formData = new FormData(form);
            const button = document.getElementById(`entry-btn-${metricId}`);

            button.innerHTML = 'Submitting...';
            fetch('{{ route("frontend.safety_review.metric.report.entry.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showInstantToast('Entry saved successfully!');
                        $(`#metric-${metricId}-amount`).val(data.amount);
                    } else {
                        showInstantToast(`Error: ${data.message}`, 'warning');
                    }

                    button.innerHTML = 'Save Entry';
                })
                .catch(error => {
                    console.error('Error:', error);
                    showInstantToast('An error occurred while saving the entry.', 'danger');
                    button.innerHTML = 'Save Entry';
                });
        }
    </script>

@endpush
