@extends('frontend.layouts.app')

@section('title', 'Manage Objectives, Indicators, and Metrics')

{{--@push('after-styles')--}}
{{--    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">--}}
{{--@endpush--}}

@section('content')
    <div class="container mt-4">
        <h2>Manage Objectives, Indicators, and Metrics</h2>
        <div class="row">
            <!-- Objectives Column -->
            <div class="col-md-4">
                <h4>Objectives</h4>
                <ul class="list-group" id="objective-list">
                    <!-- Preload existing Objectives on page load -->
                    @foreach($sectors as $sector)
                        <li class="list-group-item">
                            <strong>{{ $sector->sector_name }}</strong>
                        </li>
                        @foreach($sector->objectives as $objective)
                            <li class="list-group-item" onclick="loadIndicators({{ $objective->id }})">
                                {{ $objective->objectives }}
                                <div><small>ID: {{ $objective->id }}</small></div>
                                <button onclick="deleteObjective({{ $objective->id }})" class="btn btn-danger btn-sm float-right">Delete</button>
                            </li>
                        @endforeach
                    @endforeach
                </ul>
                <button class="btn btn-primary mt-3" onclick="showObjectiveForm()">Add Objective</button>
                <div id="objective-form" style="display: none;">
                    <input type="text" id="new-objective" placeholder="New Objective" class="form-control my-2">
                    <button onclick="addObjective()" class="btn btn-success">Save Objective</button>
                </div>
            </div>

            <!-- Indicators Column -->
            <div class="col-md-4">
                <h4>Indicators</h4>
                <ul class="list-group" id="indicator-list">
                    <!-- Indicators for a selected objective will load here -->
                </ul>
                <button class="btn btn-primary mt-3" onclick="showIndicatorForm()">Add Indicator</button>
                <div id="indicator-form" style="display: none;">
                    <input type="text" id="new-indicator" placeholder="New Indicator" class="form-control my-2">
                    <button onclick="addIndicator()" class="btn btn-success">Save Indicator</button>
                </div>
            </div>

            <!-- Metrics Column -->
            <div class="col-md-4">
                <h4>Metrics</h4>
                <ul class="list-group" id="metric-list">
                    <!-- Metrics for a selected indicator will load here -->
                </ul>
                <button class="btn btn-primary mt-3" onclick="showMetricForm()">Add Metric</button>
                <div id="metric-form" style="display: none;">
                    <input type="text" id="new-metric" placeholder="New Metric" class="form-control my-2">
                    <input type="text" id="new-metric-unit" placeholder="Unit" class="form-control my-2">
                    <button onclick="addMetric()" class="btn btn-success">Save Metric</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        let selectedObjectiveId = null;
        let selectedIndicatorId = null;

        // Show the Objective form
        function showObjectiveForm() {
            document.getElementById('objective-form').style.display = 'block';
        }

        // Add Objective
        function addObjective() {
            const objective = document.getElementById('new-objective').value;

            fetch('{{ route("frontend.objective.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ sector_id: 1, objective })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    // Append new objective to list
                    document.getElementById('objective-list').innerHTML +=
                        `<li class="list-group-item" onclick="loadIndicators(${data.data.id})">
                ${data.data.objectives}
                <div><small>ID: ${data.data.id}</small></div>
                <button onclick="deleteObjective(${data.data.id})" class="btn btn-danger btn-sm float-right">Delete</button>
            </li>`;
                });
        }

        // Load Indicators for selected Objective
        function loadIndicators(objectiveId) {
            selectedObjectiveId = objectiveId;
            fetch(`{{ url('/frontend/indicators') }}/${objectiveId}`)
                .then(response => response.json())
                .then(data => {
                    let indicatorsHtml = '';
                    data.indicators.forEach(indicator => {
                        indicatorsHtml +=
                            `<li class="list-group-item" onclick="loadMetrics(${indicator.id})">
                        ${indicator.indicator}
                        <div><small>ID: ${indicator.id}</small></div>
                        <button onclick="deleteIndicator(${indicator.id})" class="btn btn-danger btn-sm float-right">Delete</button>
                    </li>`;
                    });
                    document.getElementById('indicator-list').innerHTML = indicatorsHtml;
                });
        }

        // Add Indicator
        function addIndicator() {
            const indicator = document.getElementById('new-indicator').value;

            fetch('{{ route("frontend.indicator.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ objective_id: selectedObjectiveId, indicator })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    // Append new indicator to list
                    document.getElementById('indicator-list').innerHTML +=
                        `<li class="list-group-item" onclick="loadMetrics(${data.data.id})">
                ${data.data.indicator}
                <div><small>ID: ${data.data.id}</small></div>
                <button onclick="deleteIndicator(${data.data.id})" class="btn btn-danger btn-sm float-right">Delete</button>
            </li>`;
                });
        }

        // Load Metrics for selected Indicator
        function loadMetrics(indicatorId) {
            selectedIndicatorId = indicatorId;
            fetch(`{{ url('/frontend/metrics') }}/${indicatorId}`)
                .then(response => response.json())
                .then(data => {
                    let metricsHtml = '';
                    data.metrics.forEach(metric => {
                        metricsHtml +=
                            `<li class="list-group-item">
                        ${metric.metric} (${metric.unit})
                        <div><small>ID: ${metric.id}</small></div>
                        <button onclick="deleteMetric(${metric.id})" class="btn btn-danger btn-sm float-right">Delete</button>
                    </li>`;
                    });
                    document.getElementById('metric-list').innerHTML = metricsHtml;
                });
        }

        // Add Metric
        function addMetric() {
            const metric = document.getElementById('new-metric').value;
            const unit = document.getElementById('new-metric-unit').value;

            fetch('{{ route("frontend.metric.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ indicator_id: selectedIndicatorId, metric, unit })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    // Append new metric to list
                    document.getElementById('metric-list').innerHTML +=
                        `<li class="list-group-item">
                ${data.data.metric} (${data.data.unit})
                <div><small>ID: ${data.data.id}</small></div>
                <button onclick="deleteMetric(${data.data.id})" class="btn btn-danger btn-sm float-right">Delete</button>
            </li>`;
                });
        }
    </script>
@endpush
