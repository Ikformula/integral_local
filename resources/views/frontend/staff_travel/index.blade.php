@extends('frontend.layouts.app')

@section('title', 'Staff Travel Dashboard')

@push('after-styles')
    <style>
        .content-wrapper {
            background-color: rgba(231, 237, 245, 0.58);
            background: url(https://res.cloudinary.com/anya-ng/image/upload/c_scale,h_1000,w_1500/asadal_stock_80_axsijm) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    </style>
    @include('includes.partials._datatables-css')
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">

            {{-- Filter Form --}}
            <form method="GET" class="row mb-4">
                <div class="col-md-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}" class="form-control">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>

            {{-- Stats Cards --}}
            <div class="row">
                @foreach($stats as $stat)
                    <div class="col-md-3">
                        @component('frontend.components.dashboard_stat_widget', ['icon' => $stat['icon'], 'title' => $stat['title']])
                            {{ $stat['value'] }}
                        @endcomponent
                    </div>
                @endforeach
            </div>


            {{-- Bookings By Month Bar Graph --}}
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-header"><h4>Bookings by Month</h4></div>
                        <div class="card-body">
                            <div style="position: relative; height: 400px;">
                                <canvas id="bookingsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- Ranks By Department Table --}}
                <div class="col">
                    <div class="card">
                        <div class="card-header"><h4>Ranks by Department</h4></div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Total Bookings</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($ranks_by_department as $row)
                                    <tr>
                                        <td>{{ $row->department_name }}</td>
                                        <td>{{ $row->total }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Bookings Table --}}
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-header"><h4>Recent Bookings</h4></div>
                        <div class="card-body table-responsive">
                            @include('frontend.staff_travel._bookings_table')
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('frontend.staff_travel.bookings') }}" class="btn btn-primary">View All Bookings</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(".table").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": true,
            paging: false,
            scrollY: 465,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('.table_wrapper .col-md-6:eq(0)');

        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('bookingsChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($booking_by_month->pluck('month')) !!},
                    datasets: [{
                        label: 'Bookings per Month',
                        data: {!! json_encode($booking_by_month->pluck('total')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });

    </script>
@endpush
