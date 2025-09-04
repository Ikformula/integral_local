<!-- resources/views/frontend/ecs_refunds/index.blade.php -->
@extends('frontend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('title', 'Refund List')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn bg-navy" data-toggle="modal" data-target="#add-refunds-modal-Id">Add Refund</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-group-refunds-modal-Id">Add Group Refunds</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Refund List</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>Name</th>
                                <th>Ticket Number</th>
                                <th>Booking Reference</th>
                                <th>Route</th>
                                <th>Travel Date</th>
                                <th>Class</th>
                                <th>Amount Refundable</th>
                                <th>Remarks</th>
                                <th>Agent</th>
{{--                                <th>Actions</th>--}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->client_idRelation->name }}</td>
<td>{{ $item->name }}</td>
<td>{{ $item->ticket_number }}</td>
<td>{{ $item->booking_reference }}</td>
<td>{{ $item->route }}</td>
<td>{{ $item->travel_date }}</td>
<td>{{ $item->ticket_class }}</td>
<td>{{ number_format($item->amount_refundable) }}</td>
<td>{!! $item->remarks !!}</td>
<td>{{ $item->agent_user_idRelation->full_name }}</td>

{{--            <td>--}}
{{--                                        <a href="{{ route('frontend.ecs_refunds.show', $item->id) }}" class="btn btn-sm btn-info">View</a>--}}
{{--                                        <a href="{{ route('frontend.ecs_refunds.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>--}}
{{--                                        <form action="{{ route('frontend.ecs_refunds.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>--}}
{{--                                        </form>--}}
{{--                                    </td>--}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-refunds-modal-Id" tabindex="-1" role="dialog" aria-labelledby="add-refunds-modal-TitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="add-refunds-modal-TitleId">Add</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            @include('frontend.ecs_refunds._create-form')
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="add-group-refunds-modal-Id" tabindex="-1" role="dialog" aria-labelledby="add-group-refunds-modal-TitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="add-group-refunds-modal-TitleId">Add</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('frontend.ecs_refunds._create-group-refunds-form')
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
@endpush
