@extends('frontend.layouts.app')

@section('title', 'SPI Targets')


@section('content')
    <div class="container-fluid mt-4">
        <form method="get">
        <div class="row">
            <div class="col-9">
                <div class="form-group">
                    <select name="year" class="form-control" required>
                        @for($y = 2023; $y <= now()->year; $y++)
                        <option {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-3">
                <button type="submit" class="bg-navy btn btn-block">Set Year</button>
            </div>
        </div>
        </form>

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
                                    <th>METRICS & TARGETS ({{ $year }})</th>
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
                                                    @forelse($indicator->metrics as $metric)
                                                        <li style="{{ empty($metric->metric) ? 'background-color: #f8d7da;' : '' }}">
                                                            <strong>{{ $metric->metric }}</strong> <em>({{ $metric->unit }})</em>
                                                            <button
                                                                type="button"
                                                                id="centrik-btn-{{ $metric->id }}"
                                                                class="m-1 btn btn-sm btn-{{ $metric->is_centrik_item ? 'warning' : 'secondary' }}"
                                                                onclick="setCentrikStatus({{ $metric->id }})"
                                                            >{{ $metric->is_centrik_item ? '' : 'Not ' }}On Centrik</button>
                                                            <div class="row mt-1">
                                                                @php
                                                                    $target_values = [];
                                                                    $target_values[$metric->id] = $metric->targets->where('year', $year)->first(); @endphp
                                                                @foreach($target_labels as $target_label)
                                                                    <div class="col bg-{{ $target_colours[$loop->index] }} py-2">
                                                                        <input
                                                                            class="form-control target-input"
                                                                            name="{{ $target_label }}[{{ $metric->id }}]"
                                                                            data-metric-id="{{ $metric->id }}"
                                                                            data-target-label="{{ $target_label }}"
                                                                            value="{{  $target_values[$metric->id] ? $target_values[$metric->id]->$target_label : '' }}"
                                                                        >
                                                                    </div>
                                                                @endforeach

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
        document.addEventListener('DOMContentLoaded', function() {
            const targetInputs = document.querySelectorAll('.target-input');

            targetInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const metricId = this.dataset.metricId;
                    const targetLabel = this.dataset.targetLabel;
                    console.log(targetLabel);
                    const value = this.value;
                    const year = @json($year); // Pass the year to JavaScript

                    // Prepare the data to be sent to the backend
                    const formData = new FormData();
                    formData.append('metric_id', metricId);
                    formData.append('year', year);
                    formData.append(targetLabel, value);
                    formData.append('target_colour_direction', targetLabel);

                    // Send the data via fetch
                    fetch('{{ route("frontend.safety_review.targets.update") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // console.log(`Metric target updated successfully for ${targetLabel}`);
                                showInstantToast(`Metric target updated successfully for ${targetLabel}`);
                            } else {
                                // console.error('Error updating metric target:', data.message);
                                showInstantToast('Error updating metric target:' + data.message, 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });

        function setCentrikStatus(metric_id){
            const formData = new FormData();
            formData.append('metric_id', metric_id);

            fetch('{{ route("frontend.safety_review.centrik.status.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if(data.is_centrik == 1){
                            $('#centrik-btn-' + metric_id).attr('class', 'btn btn-sm btn-primary').html('On Centrik');
                        }else{
                            $('#centrik-btn-' + metric_id).attr('class', 'btn btn-sm btn-secondary').html('Not On Centrik');
                        }

                        showInstantToast('Centrik Status updated', 'success');
                    } else {
                        showInstantToast('Error updating metric Centrik Status :' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>

@endpush
