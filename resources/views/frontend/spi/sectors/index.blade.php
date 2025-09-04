@extends('frontend.layouts.app')

@section('title',  'SRB Sectors')

@push('after-styles')
    @include('includes.partials._datatables-css')

@endpush


@section('content')

    <div class="container-fluid my-4">
    <h1 class="mb-4">Sectors, Objectives, Indicators, and Metrics</h1>

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
                                            <div><small>ID: {{ $objective->id }}</small></div>
                                        </td>
                                    @php $firstIndicator = false; @endphp
                                @endif

                                <!-- Indicators column -->
                                    <td style="{{ empty($indicator->indicator) ? 'background-color: #f8d7da;' : '' }}">
                                        {{ $indicator->indicator }}
                                        <div><small>ID: {{ $indicator->id }}</small></div>
                                    </td>

                                    <!-- Metrics column with each metric and unit listed -->
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @forelse($indicator->metrics as $metric)
                                                <li style="{{ empty($metric->metric) ? 'background-color: #f8d7da;' : '' }}">
                                                    <strong>{{ $metric->metric }}</strong> <em>({{ $metric->unit }})</em>
                                                    <div><small>ID: {{ $metric->id }}</small></div>
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

@endsection
