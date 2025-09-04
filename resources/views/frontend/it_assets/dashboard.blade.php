@extends('frontend.layouts.app')

@section('title',  'IT Assets Dashboard' )

@push('after-styles')
    @include('includes.partials._datatables-css')

    <style>
        .scrollable-div {
            height: 350px; /* Change this value to set the desired fixed height */
            overflow: auto; /* This will enable vertical scrolling if content exceeds the height */
        }

         .flex-fill {
             height: 100%;
         }

         thead {
             position: sticky;
             top: 0;
             background-color: #fff;
         }

        .sticky-column {
            position: sticky;
            left: 0;
            z-index: 1;
            background-color: #f2f2f2;
        }
    </style>

@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                @include('frontend.components.dashboard_stat_widget', [
                    'title' => 'Assets Total',
                    'slot' => $totalAssets
                ])
            </div>

            <div class="col">
                @include('frontend.components.dashboard_stat_widget', [
                    'title' => 'With Sophos',
                    'slot' => $sophosCount
                ])
            </div>

            <div class="col">
                @include('frontend.components.dashboard_stat_widget', [
                    'title' => 'With Asset Tag',
                    'slot' => $withAssetTagCount.'/'.$totalAssets
                ])
            </div>
        </div>


        <div class="row d-flex">
            <div class="col-md-4 flex-fill">
                <div class="card shadow">
                    <div class="card-body">
                        <p class="text-center">
                            <strong>Device Type</strong>
                        </p>

                        @foreach($deviceTypeCounts as $key => $value)
                            @include('frontend.it_assets._progress-bar-chart', ['key' => $key, 'value' => $value, 'total' => $totalAssets])
                        @endforeach

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-footer">
                                <a href="{{ route('frontend.it_assets.list') }}" class="btn bg-maroon float-right">View All IT
                                    Assets</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 flex-fill">
                <div class="card shadow">
                    <div class="card-body">
                        <p class="text-center">
                            <strong>Brands</strong>
                        </p>
                        @foreach($brandCounts as $key => $value)
                            @include('frontend.it_assets._progress-bar-chart', ['key' => $key, 'value' => $value, 'total' => $totalAssets])
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="col-md-3 flex-fill">
                <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="chart-responsive"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                <canvas id="pieChart" height="20%" width="20%" style="display: block; width: 20%; height: 20%;" class="chartjs-render-monitor"></canvas>
                            </div>
                            <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-12">
                            <ul class="chart-legend clearfix">
                                @foreach($statusCounts as $key => $value)
                                    <li><i class="far fa-dot-circle" style="color: {{ $pieColour[$key] }}"></i> {{ $key }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                    <!-- /.row -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @include('frontend.it_assets._dash-table', [
                    'card_title' => 'Assets By Departments/Brand',
                    'y_name' => 'Department',
                    'y_axis' => $data['department_names'],
                    'x_axis' => $data['brands'],
                    'matrix' => $statsByDepartmentAndDeviceType
                ])
            </div>
            <div class="col-md-6">
                @include('frontend.it_assets._dash-table', [
                    'card_title' => 'Assets By Departments/Device Types',
                    'y_name' => 'Department',
                    'y_axis' => $data['department_names'],
                    'x_axis' => $data['device_type'],
                    'matrix' => $statsByDepartmentAndDeviceType
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @include('frontend.it_assets._dash-table', [
                    'card_title' => 'Assets By Office Location/Brand',
                    'y_name' => 'Location',
                    'y_axis' => $data['office_location'],
                    'x_axis' => $data['brands'],
                    'matrix' => $statsByOfficeLocationAndDeviceType
                ])
            </div>
            <div class="col-md-6">
                @include('frontend.it_assets._dash-table', [
                    'card_title' => 'Assets By Office Location/Device Type',
                    'y_name' => 'Location',
                    'y_axis' => $data['office_location'],
                    'x_axis' => $data['device_type'],
                    'matrix' => $statsByOfficeLocationAndDeviceType
                ])
            </div>
        </div>


    </div>
@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        var table = $("#it-assets").DataTable({
            // "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "responsive": false,
            "lengthChange": false,
            "autoWidth": true,
            paging: false,
            scrollY: 465,
            scrollX: true,
            scrollCollapse: true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        }).buttons().container().appendTo('#it-assets_wrapper .col-md-6:eq(0)');
    </script>


    <script src="{{ asset('adminlte3.2/plugins/chart.js/Chart.min.js') }}"></script>

    <script>
        //-------------
        // - PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData = {
            labels: [
                @foreach($statusCounts as $key => $value)
                    '{{ $key }}',
                @endforeach
            ],
            datasets: [
                {
                    data: [@foreach($statusCounts as $key => $value)
                        '{{ $value }}',
                        @endforeach],
                    backgroundColor: [@foreach($statusCounts as $key => $value)
                        '{{ $pieColour[$key] }}',
                        @endforeach]
                }
            ]
        }
        var pieOptions = {
            legend: {
                display: false
            }
        }
        // Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        // eslint-disable-next-line no-unused-vars
        var pieChart = new Chart(pieChartCanvas, {
            type: 'doughnut',
            data: pieData,
            options: pieOptions
        })

        //-----------------
        // - END PIE CHART -
        //-----------------

    </script>
@endpush
