@extends('frontend.layouts.app')

@section('title', 'SPI Metric Formulae')


@section('content')
    <div class="container-fluid mt-4">

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
                                    <th>METRICS</th>
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
                        <strong>{{ $metric->metric }}</strong>
                        <em>({{ $metric->unit }})</em>

                        <!-- Metric Formula Section -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Set Formula for {{ $metric->metric }}</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    // Decode the metric formula JSON if available
                                    $formula = $metric->metric_formula ? json_decode($metric->metric_formula, true) : null;
                                    $parameters = $formula['parameters'] ?? [['letter' => 'a', 'title' => $metric->metric]];
                                    $operation = $formula['operation'] ?? 'a';
                                @endphp

                                <form id="formulaForm_{{ $metric->id }}"
                                      class="formula-form"
                                      data-metric-id="{{ $metric->id }}">
                                    <div class="form-group">
                                        <label><strong>Parameters</strong></label>
                                        <div id="paramsContainer_{{ $metric->id }}">
                                            @foreach($parameters as $index => $parameter)
                                                <div
                                                    class="form-row mb-2 parameter-row">
                                                    <div class="col-3">
                                                        <input type="text"
                                                               class="form-control param-letter"
                                                               name="parameters[{{ $index }}][letter]"
                                                               value="{{ $parameter['letter'] }}"
                                                               readonly>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text"
                                                               class="form-control param-title"
                                                               name="parameters[{{ $index }}][title]"
                                                               value="{{ $parameter['title'] }}"
                                                               placeholder="Enter Title">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button"
                                                class="btn btn-secondary btn-sm"
                                                onclick="addParamRow({{ $metric->id }})">
                                            Add Parameter
                                        </button>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="operation_{{ $metric->id }}"><strong>Operation</strong></label>
                                        <input type="text"
                                               class="form-control operation-input"
                                               id="operation_{{ $metric->id }}"
                                               name="operation"
                                               value="{{ $operation }}"
                                               placeholder="e.g., (a / b) * c">
                                    </div>

                                    <button type="button" class="btn btn-primary"
                                            onclick="submitFormula({{ $metric->id }})">
                                        Save Formula
                                    </button>
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
        function addParamRow(metricId) {
            const container = document.getElementById(`paramsContainer_${metricId}`);
            const rowCount = container.querySelectorAll('.parameter-row').length;

            const rowHtml = `
            <div class="form-row mb-2 parameter-row">
                <div class="col-3">
                    <input type="text" class="form-control param-letter" name="parameters[${rowCount}][letter]" value="${String.fromCharCode(97 + rowCount)}" readonly>
                </div>
                <div class="col-9">
                    <input type="text" class="form-control param-title" name="parameters[${rowCount}][title]" placeholder="Enter Title">
                </div>
            </div>
        `;

            container.insertAdjacentHTML('beforeend', rowHtml);
        }

        function submitFormula(metricId) {
            const form = document.getElementById(`formulaForm_${metricId}`);
            const formData = new FormData(form);

            // Include metric ID
            formData.append('metric_id', metricId);

            fetch('{{ route("frontend.safety_review.formulae.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showInstantToast('Formula saved successfully!');
                    } else {
                        showInstantToast('Failed to save formula.', 'warning');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endpush
