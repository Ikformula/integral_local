<!-- resources/views/frontend/ecs_bookings/index.blade.php -->
@extends('frontend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('title', 'ECS Booking List')

@section('content')
    <div class="container-fluid">
        @if(!$logged_in_user->isEcsClient)
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('frontend.ecs_bookings.create') }}" class="btn btn-primary">Add New Booking</a>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ECS Booking List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Reference</th>
                                <th>Client</th>
                                <th>Penalties</th>
                                <th>Ticket Fare</th>
                                <th>Remarks</th>
                                <th>Date</th>
                                @if(!$logged_in_user->isEcsClient)
                                <th>Agent</th>
                                @endif
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->booking_reference }}</td>
                                    @if(!$logged_in_user->isEcsClient)
                                    <td><a href="{{ route('frontend.ecs_clients.show', $item->client_id) }}" target="_blank">{{ $item->client_idRelation->name_and_balance }} <i class="fa-solid fa-up-right-from-square"></i></a></td>
                                        @else
                                        <td>{{ $item->client_idRelation->name_and_balance }}</td>
                                    @endif
                                    <td>{{ $item->penalties }}</td>
                                    <td>{{ number_format($item->ticket_fare) }}</td>
                                    <td>{!! $item->remarks !!}</td>
                                    <td>{{ $item->for_date }}</td>

                                    @if(!$logged_in_user->isEcsClient)
                                    <td>{{ $item->agent_user_idRelation->full_name }}</td>
                                    @endif

                                    <td>
                                        <a href="{{ route('frontend.ecs_bookings.show', $item->id) }}" class="btn btn-sm btn-info">View</a>
{{--                                        <a href="{{ route('frontend.ecs_bookings.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>--}}
{{--                                        <form action="{{ route('frontend.ecs_bookings.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>--}}
{{--                                        </form>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = new DataTable('.table', {
                "paging": false,
                scrollY: 465,
                layout: {
                    top: {
                        searchBuilder: { }
                    },
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });
    </script>

    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
