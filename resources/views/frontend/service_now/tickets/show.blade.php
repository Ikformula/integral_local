@extends('frontend.layouts.app')

@section('title', 'Issue #' . $ticket->ticket_id_number)

@push('after-styles')
    @include('includes.partials._datatables-css')

    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush



@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7">
                    <div class="card arik-card mb-3">
                        <div class="card-header">
                            <h6>{{ $ticket->title }} - {{ $ticket->status }}</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped" id="ticket">
                                    <tbody>
                                    <tr>
                                        <th>Concerned Staff</th>
                                        <td>@if(isset($ticket->concerned_staff_ara_id, $ticket->concernedStaff)){{ $ticket->concernedStaff->name }} ({{ $ticket->concerned_staff_ara_id }})@endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td>{{ $ticket->ticketType->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{!! $ticket->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Assigned to</th>
                                        <td>{{ isset($ticket->agent) ? $ticket->agent->full_name : '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Priority</th>
                                        <td><i class="fas fa-{{ $ticket->priorityUI() }}"></i>
                                            {{ ucfirst($ticket->priority) }}</td>
                                    </tr>
                                    @php($ticket_type = $ticket->ticketType)
                                    <tr>
                                        <th>Aging</th>
                                        <td><span
                                                class="badge badge-{{ $ticket->agingColour() }}">{{ $ticket->created_at->diffForHumans() }}
                                                {{ $ticket->agingData() }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $ticket->status }}</td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="card direct-chat direct-chat-primary">
                        <div class="card-header ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title">Ticket Logs and Notes</h3>

                            <div class="card-tools">
                                {{--                  <span title="3 New Messages" class="badge badge-primary">3</span>--}}
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages" id="chat-container">
                                <!-- Message. Default to the left -->
                                @foreach ($ticket->logs as $note)
                                    <div
                                        class="direct-chat-msg {{ $note->triggerer_user_id == $logged_in_user->id ? 'right' : '' }}"
                                    ">
                                    <div class="direct-chat-infos clearfix">
                                        <span
                                            class="direct-chat-name float-{{ $note->triggerer_user_id == $logged_in_user->id ? 'right' : 'left' }}">@if($note->user){{ $note->user->full_name }} @else
                                                <i>SYSTEM</i> @endif</span>
                                        <span
                                            class="direct-chat-timestamp float-{{ $note->triggerer_user_id == $logged_in_user->id ? 'left' : 'right' }}">{{ $note->created_at->toDayDateTimeString() }}</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    @if($note->user)
                                        <img class="direct-chat-img"
                                             src="https://eu.ui-avatars.com/api/?name={{ $note->user->full_name }}&background={{ $note->triggerer_user_id == $logged_in_user->id ? '34000D' : '032560' }}&color=F5F4F4"
                                             alt="message user image">
                                @endif
                                <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        @if($note->title)
                                            <strong>{{ $note->title }}</strong><br>
                                        @endif

                                        @if($note->description)
                                            {!! $note->description !!}
                                        @endif
                                    </div>
                                    <!-- /.direct-chat-text -->
                            </div>
                        @endforeach

                        <!-- /.direct-chat-msg -->

                        </div>
                        <!-- /.direct-chat-pane -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <h6>Add Log</h6>
                        <form action="{{ route('frontend.service_now.tickets.addLog', $ticket)}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>Title</label>
                                <input name="title" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Log</button>
                        </form>
                    </div>
                    <!-- /.card-footer-->
                </div>
            </div>
            <div class="col-md-5">

                <div class="row">
                    <div class="col">
                        <div class="card arik-card">
                            <div class="card-header">
                                <h6>Update Ticket/Issue</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('frontend.service_now.tickets.update', $ticket) }}"
                                      method="POST">
                                    @csrf

                                    @if((isset($staff_member) && $staff_member->staff_ara_id == $ticket->concerned_staff_ara_id) || ($user_is_service_now_agent && $logged_in_user->can('handle service now tickets')))
                                        <div class="form-group" id="status">
                                            <label>Status</label>
                                            <div class="custom-control custom-radio">
                                                @foreach ($form_values['statuses'] as $status)
                                                    <div class="form-check">
                                                        <input type="radio" id="status_{{ $status }}" name="status" class="custom-control-input" value="{{ $status }}" {{ $status == $ticket->status ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="status_{{ $status }}">{{ ucfirst($status) }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    @endif

                                    @if(isset($staff_member) && $staff_member->staff_ara_id == $ticket->concerned_staff_ara_id)
                                        <div class="form-group" id="ratingField" style="display: none;">
                                            <label for="rating">How was the support experience? We'll appreciate your honest feedback!:</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating"
                                                       id="goodRating" value="good" @if(isset($ticket->rating) && $ticket->rating == 'good') checked @endif>
                                                <label class="form-check-label" for="goodRating"><i
                                                        class="fas fa-thumbs-up text-success"></i> Good</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating"
                                                       id="okayRating" value="okay" @if(isset($ticket->rating) && $ticket->rating == 'okay') checked @endif>
                                                <label class="form-check-label" for="okayRating"><i
                                                        class="fas fa-hand-paper text-warning"></i> Okay</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating"
                                                       id="notGoodRating" value="not_good" @if(isset($ticket->rating) && $ticket->rating == 'not_good') checked @endif>
                                                <label class="form-check-label" for="notGoodRating"><i
                                                        class="fas fa-thumbs-down text-danger"></i> Not Good</label>
                                            </div>
                                        </div>

                                    @endif

                                    @if($user_is_service_now_agent)
                                        <div class="form-group">
                                            <label for="ticket_type">Ticket Type</label>
                                            <select id="ticket_type" name="type_id" class="custom-select select2"
                                                    aria-describedby="ticket_typeHelpBlock">
                                                @foreach ($form_values['ticket_types'] as $ticket_type)
                                                    <option {{ $ticket_type->id == $ticket->type_id ? 'selected' : '' }}
                                                            value="{{ $ticket_type->id }}">{{ $ticket_type->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="concerned_staff_ara_id">Concerned Staff ARA ID</label>
                                            <select id="concerned_staff_ara_id" name="concerned_staff_ara_id"
                                                    class="custom-select select2"
                                                    aria-describedby="concerned_staff_ara_idHelpBlock">
                                                @foreach ($form_values['staff_members'] as $staff_member)
                                                    <option
                                                        {{ $staff_member->staff_ara_id == $ticket->concerned_staff_ara_id ? 'selected' : '' }}
                                                        value="{{ $staff_member->staff_ara_id }}">
                                                        {{ $staff_member->surname }} {{ $staff_member->other_names }}
                                                        (ARA{{ $staff_member->staff_ara_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span id="concerned_staff_ara_idHelpBlock" class="form-text text-muted">Not
            required (Only if applicable)</span>
                                        </div>

                                        <div class="form-group">
                                            <label>Priority Level</label>
                                            @foreach ($form_values['priorities'] as $value)
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input name="priority" id="priorities_{{ $loop->iteration }}"
                                                           type="radio" class="custom-control-input"
                                                           value="{{ $value }}" required="required"
                                                        {{ $ticket->priority == $value ? 'checked="checked"' : '' }}>
                                                    <label for="priorities_{{ $loop->iteration }}"
                                                           class="custom-control-label">{{ ucfirst($value) }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <button type="submit" class="btn bg-gradient-navy float-right">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user_is_service_now_agent || isset($ticket->agent))
                <div class="row">
                    <div class="col">
                        <div class="card arik-card">
                            <div class="card-header">
                                <h6>Assignee</h6>
                            </div>
                            <div class="card-body">
                                @if($user_is_service_now_agent)
                                    <form action="{{ route('frontend.service_now.tickets.update', $ticket) }}"
                                          method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="user_id">Assigned to</label>
                                            <select id="assigned_to_agent_user_id" name="assigned_to_agent_user_id"
                                                    class="form-control select2"
                                                    aria-describedby="assigned_to_agent_user_idHelpBlock">
                                                @if(isset($ticket->assigned_to_agent_user_id))
                                                    <option value="{{ $ticket->assigned_to_agent_user_id }}" selected>
                                                        {{ $ticket->agent->full_name }}</option>
                                                @else
                                                    <option selected disabled>Select One</option>
                                                @endif
                                                @foreach ($form_values['agents'] as $user)
                                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="notify_agent"
                                                       value="1" @if ($ticket->notify_agent == 1) checked @endif>
                                                <label class="form-check-label">Notify Assignee</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="user_id">Escalate to</label>
                                            <select id="escalate_to_user_id" name="escalate_to_user_id"
                                                    class="form-control select2"
                                                    aria-describedby="escalate_to_user_idHelpBlock">
                                                @if(isset($ticket->escalate_to_user_id))
                                                    <option value="{{ $ticket->escalate_to_user_id }}" selected">
                                                    {{ $ticket->escalateToUser->full_name }}</option>
                                                @else
                                                    <option selected disabled">Select One</option>
                                                @endif
                                                @foreach ($form_values['agents'] as $user)
                                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="notify_escalation_user" value="1"
                                                       @if($ticket->notify_escalation_user == 1) checked @endif>
                                                <label class="form-check-label">Notify Escalation Lead (upon delayed
                                                    resolution)</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn bg-gradient-navy float-right">Update</button>
                                    </form>
                                @elseif(isset($ticket->agent))
                                    <strong>Assigned
                                        to: </strong>{{ $ticket->agent->full_name }}@if(isset($ticket->agent->staff_member))
                                        , ARA{{ $ticket->agent->staff_member->staff_ara_id }}@endif
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                @endif

                @if($user_is_service_now_agent && $logged_in_user->can('handle service now tickets'))
                       <div class="row">
                            <div class="col-12">
                                <form action="{{ route('frontend.service_now.tickets.delete', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-block">Delete this Ticket</button>
                                </form>
                            </div>
                        </div>
                @endif
            </div>
        </div>
    </section>

@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function () {
// Scroll to the bottom of the chat container
            var chatContainer = document.getElementById('chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>

    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>

    <!-- Select2 -->
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
//Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });

        $("#tickets").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            paging: false,
            scrollY: 465,
            "buttons": ["colvis"]
        }).buttons().container().appendTo('#tickets_wrapper .col-md-6:eq(0)');

        @if(isset($staff_member) && $staff_member->staff_ara_id == $ticket->concerned_staff_ara_id)

        // Function to show/hide the rating field based on the selected status
        function toggleRatingField() {
            var statusRadios = document.querySelectorAll('input[name="status"]:checked');
            var status = statusRadios.length > 0 ? statusRadios[0].value : null;
            var ratingField = document.getElementById('ratingField');
            console.log(status);
            // If status is 'resolved', 'closed', or 'completed', show the rating field; otherwise, hide it
            if (status === 'resolved' || status === 'closed' || status === 'completed') {
                ratingField.style.display = 'block';
            } else {
                ratingField.style.display = 'none';
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            toggleRatingField(); // Initial call to toggleRatingField
            var statusRadios = document.querySelectorAll('input[name="status"]');
            statusRadios.forEach(function(radio) {
                radio.addEventListener('change', toggleRatingField);
            });
        });
        @endif
    </script>

@endpush
