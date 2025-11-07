<!-- resources/views/frontend/ecs_bookings/index.blade.php -->
@extends('frontend.layouts.app')

@push('after-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">

<link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet"
      href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush
@php
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'view';
@endphp
@section('title', 'ECS '.($filter == 'refunds' ? 'Refunds' : 'Requests'). ' List')

@section('content')

<div class="container-fluid">
    @if(!isset($is_client))
    <div class="row mb-3">
        <div class="col-12">
            @if($filter == 'refunds')
            <a href="{{ route('frontend.ecs_refunds.createGroupRefunds') }}" class="btn btn-primary">Add Refunds</a>
            @else
            <a href="{{ route('frontend.ecs_bookings.create') }}" class="btn btn-primary">Add New Request</a>
                @endif
        </div>
    </div>
    @endif

    <form method="GET" class="mb-3">
        <input type="hidden" name="filter" value="{{ $filter }}">
        <div class="row align-items-end">
            <div class="col-md-2">
                <label>Date From</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label>Date To</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            @if(!isset($is_client))
            <div class="col-md-2">
                <label>Client</label>
                <select name="client_id" class="form-control select2">
                    <option value="">All</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name_and_balance }}</option>
                    @endforeach
                </select>
            </div>
            @if($isSupervisorOrSuperUser)
            <div class="col-md-2">
                <label>Agent</label>
                <select name="agent_user_id" class="form-control select2">
                    <option value="">All</option>
                    @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ request('agent_user_id') == $agent->id ? 'selected' : '' }}>{{ $agent->full_name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

{{--            <div class="col-md-2">--}}
{{--                <label class="d-block">&nbsp;</label>--}}
{{--                <div class="form-check">--}}
{{--                    <input class="form-check-input" type="checkbox" name="include_internally_approved" id="include_internally_approved" value="1" {{ request('include_internally_approved') ? 'checked' : '' }}>--}}
{{--                    <label class="form-check-label" for="include_internally_approved">--}}
{{--                        Include Internally Approved--}}
{{--                    </label>--}}
{{--                </div>--}}
{{--            </div>--}}
            @endif

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('frontend.ecs_flight_transactions.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ECS Requests List</h3>
                </div>
                <div class="card-body">
                    @if(!isset($is_client))
                    <form id="bulk-verify-form" action="{{ route('frontend.ecs_flight_transactions.bulk_verify') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="transaction_ids" id="bulk-ids">

                        <button type="button" class="btn btn-success mb-2" onclick="submitBulkVerify()">
                            Mark Selected as Verified
                        </button>

                        <span class="ml-2 text-muted">
        Selected: <strong id="selected-count">0</strong>
    </span>
                    </form>
                    @endif

                    <table class="table table-bordered table-hover w-100">
                        <thead>
                            <tr>
                                @if(!isset($is_client))
                                <th><input type="checkbox" id="check-all"></th>
                                @endif
                                <th>#</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Travel Date</th>
                                <th>Ticket Number</th>
                                <th>Booking Reference</th>
                                <th>Source</th>
{{--                                @if(!isset($is_client))--}}
                                <th>Client</th>
{{--                                @endif--}}
{{--                                <th>Penalties</th>--}}
                                <th>Amount</th>
                                <th>Remarks</th>
                                <th>Position</th>
                                @if($isSupervisorOrSuperUser)
                                <th>Agent</th>
                                @endif
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ecs_flight_transactions as $key => $item)
                                <tr class="trx-row">
                                    @if(!isset($is_client))
                                    <td>
                                        @if(!$item->pushed_to_client_at)
                                            <input type="checkbox" class="row-check" value="{{ $item->id }}">
                                        @endif
                                    </td>
                                    @endif
                                <td>{{ $ecs_flight_transactions->firstItem() + $key }}</td>
                                <td>{{ $item->created_at->toDateString() }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ optional($item->for_date)->toDateString() }}</td>
                                <td>{{ $item->ticket_number }}</td>
                                <td>{{ $item->booking_reference }}</td>
{{--                                <td>{{ $item->penalties }}</td>--}}
                                <td>{{ $item->source }}</td>
{{--                                @if(!isset($is_client))--}}
                                <td>{{ $item->client->name ?? '' }}</td>
{{--                                @endif--}}
                                <td>{{ number_format($item->totalAmount()) }}</td>
                                <td>{!! $item->remarks !!}</td>
                                <td>{{ $item->position }}</td>
                                @if($isSupervisorOrSuperUser)
                                <td>{{ $item->agentUser->full_name ?? '' }}</td>
                                @endif
                                <td>
                                    @php
                                        $status_arr = [
                        'colour' => 'secondary',
                        'text' => 'UNAPPROVED'
                        ];
                        if($item->client_approved_at)
                        $status_arr = ['colour' => 'success', 'text' => 'APPROVED'];
                        if(is_null($item->client_approved_at) && $item->client_disputed_at)
                        $status_arr = ['colour' => 'warning', 'text' => 'DISPUTED'];
                                    @endphp

                                    @if(!isset($is_client))
                                {{-- <a href="{{ route('frontend.ecs_flight_transactions.show', $item->id) }}" class="btn btn-sm btn-info">View</a>--}}
                                <a href="{{ route('frontend.ecs_flight_transactions.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>



                                @if(!$item->internal_approved_at && $filter == 'verify')
                                    @if(!$item->internal_approved_at)
                                <a href="#" class="btn btn-sm btn-warning btn-verify" data-id="{{ $item->id }}">Verify</a>
                                @else
                                      <a href="#" class="btn btn-sm btn-warning btn-recall" data-id="{{ $item->id }}">Recall</a>
                                @endif
                                @endif


                                    @else
                                        @if($item->pushed_to_client_at)
                                            @if(is_null($item->client_approved_at))

                                                <button type="button" class="btn btn-sm btn-{{ $status_arr['text'] == 'DISPUTED' ? 'outline-danger' : 'danger' }}" data-toggle="modal"
                                                        data-target="#dispute-modal-{{ $item->id }}-Id">Dispute{{ $status_arr['text'] == 'DISPUTED' ? 'd' : '' }}
                                                </button>


                                                <div class="modal fade" id="dispute-modal-{{ $item->id }}-Id" tabindex="-1" role="dialog"
                                                     aria-labelledby="dispute-modal-{{ $item->id }}-TitleId" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="dispute-modal-{{ $item->id }}-TitleId">Dispute Transaction</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body p-0">
                                                                <form action="{{ route('frontend.ecs_client_portal.disputeFlightTrx', $item->id) }}" method="POST"
                                                                      class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                                    @csrf
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <ul>
                                <li><strong>Entered on: </strong> {{ $item->created_at->toDateString() }}</li>
                                <li><strong>Pax Name: </strong> {{ $item->name }}</li>
                                <li><strong>Travel Date: </strong> {{ optional($item->for_date)->toDateString() }}</li>
                                <li><strong>Ticket Number: </strong> {{ $item->ticket_number }}</li>
                                <li><strong>PNR: </strong> {{ $item->booking_reference }}</li>
                                <li><strong>Source: </strong> {{ $item->source }}</li>
                                <li><strong>Amount: </strong> {{ number_format($item->totalAmount()) }}</li>
                                                                            </ul>
                                                                        </div>
                                                                        <!-- /.card-footer -->
                                                                        <div class="card-footer">
                                                                            <div class="img-push">
                                                                                <div class="input-group input-group-sm">
                                                                                    <input type="text" name="dispute_comment" class="form-control form-control-sm" placeholder="Press enter to post comment" value="{{ $item->dispute_comment }}" required>
                                                                                    <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-paper-plane"></i></button>
                  </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.card-footer -->
                                                                    </div>
                                                                    <!-- /.card -->

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <form action="{{ route('frontend.ecs_client_portal.approveFlightTrx', $item->id) }}" method="POST"
                                                      class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                </form>

                                            @else
                                                Apprvd.: {{ $item->client_approved_at->toDateString() }}
                                            @endif
                                        @endif
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-2">
                        {{ $ecs_flight_transactions->links() }}
                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


<script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4'
    });
</script>

<script>
    $(document).ready(function() {
        var table = new DataTable('.table', {
            "paging": false,
            scrollY: 465,
            layout: {
                top: {
                    searchBuilder: {}
                },
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            }
        });
        // Handle Verify button
        $('.btn-verify').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm('Are you sure you want to verify this transaction?')) {
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ config('app.url') }}/ecs_flight_transactions/' + id + '/verify'
                });
                form.append('@csrf');
                form.append('<input type="hidden" name="_method" value="POST">');
                $('body').append(form);
                form.submit();
            }
        });

        // Handle Recall button
        $('.btn-recall').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm('Are you sure you want to recall this transaction?')) {
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ config('app.url') }}/ecs_flight_transactions/' + id + '/recall'
                });
                form.append('@csrf');
                form.append('<input type="hidden" name="_method" value="POST">');
                $('body').append(form);
                form.submit();
            }
        });

        // Handle Reject button
        var rejectId = null;
        $('.btn-reject').on('click', function(e) {
            e.preventDefault();
            rejectId = $(this).data('id');
            $('#rejection_comment').val('');
            $('#rejectModal').modal('show');
        });
        $('#rejectForm').on('submit', function(e) {
            e.preventDefault();
            var comment = $('#rejection_comment').val();
            var form = $('<form>', {
                'method': 'POST',
                'action': '{{ config('app.url') }}/ecs_flight_transactions/' + rejectId + '/reject'
            });
            form.append('@csrf');
            form.append('<input type="hidden" name="_method" value="POST">');
            form.append($('<input>', {
                type: 'hidden',
                name: 'rejection_comment',
                value: comment
            }));
            $('body').append(form);
            form.submit();
        });

        // Handle Push to Client button
        $('.btn-push').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm('Are you sure you want to push this transaction to the client?')) {
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ config('app.url') }}/ecs_flight_transactions/' + id + '/push'
                });
                form.append('@csrf');
                form.append('<input type="hidden" name="_method" value="POST">');
                $('body').append(form);
                form.submit();
            }
        });


    });
</script>

@if(!isset($is_client))
<script>
    function updateSelectedCount() {
        let count = document.querySelectorAll('.row-check:checked').length;
        document.getElementById('selected-count').innerText = count;
    }

    // Check / Uncheck All
    document.getElementById('check-all').addEventListener('change', function() {
        let isChecked = this.checked;
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.checked = isChecked;
            highlightRow(cb);
        });
        updateSelectedCount();
    });

    // Highlight Row on Change
    document.querySelectorAll('.row-check').forEach(cb => {
        cb.addEventListener('change', function() {
            highlightRow(cb);
            updateSelectedCount();
        });
    });

    function highlightRow(cb) {
        let row = cb.closest('tr');
        row.style.backgroundColor = cb.checked ? '#d1ffd1' : '';
    }

    // Submit Selected IDs
    function submitBulkVerify() {
        let ids = Array.from(document.querySelectorAll('.row-check:checked')).map(cb => cb.value);
        if (ids.length === 0) {
            alert('No transactions selected.');
            return;
        }
        document.getElementById('bulk-ids').value = JSON.stringify(ids);
        document.getElementById('bulk-verify-form').submit();
    }
</script>
@endif

<script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4'
    });
</script>
@endpush
