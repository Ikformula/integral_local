@extends('frontend.layouts.app')

@section('title', 'Staff Travel Dashboard' )


@push('after-styles')
    <style>
        .content-wrapper {
            background-color: rgba(231, 237, 245, 0.58);
            background: url(https://res.cloudinary.com/anya-ng/image/upload/v1750252982/bright-office-working_ynw7bb.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    </style>
@endpush


@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($stats as $stat)
                    <div class="col-md-4">
                        @component('frontend.components.dashboard_stat_widget', ['icon' => $stat['icon'], 'title' => $stat['title']])
                            {{ $stat['value'] }}
                        @endcomponent
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            My Bookings
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>S/N</th>
{{--                                    <th>Staff ARA ID</th>--}}
{{--                                    <th>Username</th>--}}
                                    <th>Beneficiary</th>
                                    <th>Request Date</th>
                                    <th>Request Time</th>
                                    <th>Departure</th>
                                    <th>Returns</th>
                                    <th>Adult</th>
                                    <th>Child</th>
                                    <th>Infant</th>
                                    <th>PNR</th>
                                    <th>IP Address</th>
                                    <th>Comp Name</th>
                                    <th>Transaction ID</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($staff_travel_bookings as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
{{--                                        <td>{{ $booking->staff_ara_id }}</td>--}}
{{--                                        <td>{{ $booking->username }}</td>--}}
                                        <td>{{ $booking->beneficiary->name }}</td>
                                        <td>{{ $booking->request_date }}</td>
                                        <td>{{ $booking->request_time }}</td>
                                        <td>{{ $booking->departure }}</td>
                                        <td>{{ $booking->returns }}</td>
                                        <td>{{ $booking->adult }}</td>
                                        <td>{{ $booking->child }}</td>
                                        <td>{{ $booking->infant }}</td>
                                        <td>{{ $booking->pnr }}</td>
                                        <td>{{ $booking->ip_address }}</td>
                                        <td>{{ $booking->comp_name }}</td>
                                        <td>{{ $booking->transaction_id }}</td>
                                        <td>{{ $booking->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
