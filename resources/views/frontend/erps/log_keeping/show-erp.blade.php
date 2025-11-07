@extends('frontend.layouts.app')

@section('title', 'LogKeeper - '. $erp->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-footer">
                    <h6>Add Log</h6>
                </div>
                <div class="card-body">
                    <form id="logForm">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="message_from">Message From:</label>
                                    <input type="text" class="form-control" id="message_from" list="message_from_list" required>
                                    <datalist id="message_from_list">
                                        @php $froms = collect($logkeeps)->pluck('message_from')->unique()->filter()->values(); @endphp
                                        @foreach($froms as $from)
                                        <option value="{{ $from }}">{{ $from }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="message_to">Message To:</label>
                                    <input type="text" class="form-control" id="message_to" list="message_to_list" required>
                                    <datalist id="message_to_list">
                                        @php $tos = collect($logkeeps)->pluck('message_to')->unique()->filter()->values(); @endphp
                                        @foreach($tos as $to)
                                        <option value="{{ $to }}">{{ $to }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="event_summary">Event Summary:</label>
                            <textarea class="form-control" id="event_summary" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitLogBtn">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5 order-sm-first order-md-last">
            <div class="card">
                <div class="card-header">
                    <strong>ERP Info</strong>
                    <div class="card-tools">
                    <form action="{{ route('frontend.log_keeping.delete.erp', $erp) }}" method="POST" onsubmit="return confirm('Deleting this will also delete all the logs under it. Continue?')" class="form-inline">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete This ERP</button>
                    </form>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.log_keeping.update.erp', $erp) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $erp->title }}">
                        </div>

                        <div class="form-group">
                            <label>Purpose</label>
                            <input type="text" name="purpose" class="form-control" value="{{ $erp->purpose }}">
                        </div>

                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control">{{ $erp->remarks }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-info float-right">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Logkeeping</strong>
                    <button class="btn btn-primary float-right" id="printButton">Print Table</button>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped" id="logkeeping-data">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Message From</th>
                                <th>Message To</th>
                                <th>Event Summary</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody">
                            @foreach ($logkeeps as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->message_from }}</td>
                                <td>{{ $log->message_to }}</td>
                                <td>{{ $log->event_summary }}</td>
                                <td>{{ $log->created_at }}</td>
                                <td>
                                    <form action="{{ route('frontend.log_keeping.delete.logkeep') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this log? This action is irreversible')">
                                        @csrf
                                        <input type="hidden" name="logkeep_id" value="{{ $log->id }}">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
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
<script>
    $(document).ready(function() {

        // Helper to update datalists with new values
        function updateDatalists(newFrom, newTo) {
            let $fromList = $('#message_from_list');
            let $toList = $('#message_to_list');
            if (newFrom && $fromList.find('option[value="' + newFrom.replace(/"/g, '&quot;') + '"]').length === 0) {
                $fromList.append('<option value="' + $('<div>').text(newFrom).html() + '">' + $('<div>').text(newFrom).html() + '</option>');
            }
            if (newTo && $toList.find('option[value="' + newTo.replace(/"/g, '&quot;') + '"]').length === 0) {
                $toList.append('<option value="' + $('<div>').text(newTo).html() + '">' + $('<div>').text(newTo).html() + '</option>');
            }
        }

        // Submit log data to Laravel backend
        $("#logForm").submit(function(event) {
            event.preventDefault();
            const erp_id = {{ $erp->id }};
            const message_from = $("#message_from").val();
            const message_to = $("#message_to").val();
            const event_summary = $("#event_summary").val();
            const _token = '{{ csrf_token() }}';
            const $btn = $('#submitLogBtn');
            $btn.prop('disabled', true).text('Submitting...');

            $.ajax({
                type: "POST",
                url: "{{ route('store.logkeep') }}",
                data: {
                    erp_id: erp_id,
                    message_from: message_from,
                    message_to: message_to,
                    event_summary: event_summary,
                },
                success: function(response) {
                    // Prepend the record to the table
                    const created_at = response.created_at;
                    const log_id = response.log_id;
                    const newRow = `
                    <tr class="animate__animated animate__backInDown">
                        <td>${log_id}</td>
                        <td>${message_from}</td>
                        <td>${message_to}</td>
                        <td>${event_summary}</td>
                        <td>${created_at}</td>
                        <td><form action="{{ route('frontend.log_keeping.delete.logkeep') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this log? This action is irreversible')">
                        @csrf
                            <input type="hidden" name="logkeep_id" value="${log_id}">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form></td>
                    </tr>
                `;
                    $("#logTableBody").prepend(newRow);

                    // Update datalists with new values
                    updateDatalists(message_from, message_to);

                    // Reset the form after submission
                    $("#logForm")[0].reset();
                    $btn.prop('disabled', false).text('Submit');
                },
                error: function(error) {
                    console.error("Error submitting log: ", error);
                    $btn.prop('disabled', false).text('Submit');
                }
            });
        });

        @include('frontend.erps.log_keeping._print-js')

    });
</script>
@endpush
