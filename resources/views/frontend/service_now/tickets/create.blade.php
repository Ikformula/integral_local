@extends('frontend.layouts.app')

@section('title', 'Add a Ticket/Issue')

@push('after-styles')
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="card arik-card">
                        <div class="card-header">
                            <h4>Create Ticket/Issue - {{ $group->name }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('frontend.service_now.tickets.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" id="title" required>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description" id="description" rows="5"></textarea>
                                    <small id="emailHelp" class="form-text text-muted">Kindly explain the issue as detailed as possible</small>
                                </div>
                                <div class="form-group">
                                    <label for="ticket_type">Type of Issue</label>
                                    <select id="ticket_type" name="type_id" class="form-control select2" aria-describedby="ticket_typeHelpBlock">
                                        @foreach($form_values['ticket_types'] as $ticket_type)
                                            <option value="{{ $ticket_type->id }}">{{ $ticket_type->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

{{--                                <div class="form-group">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Group</label>--}}
{{--                                        <input type="text" class="form-control" name="group_id" id="group_id" value="">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="type">Group</label>--}}
{{--                                    <div>--}}
{{--                                        <select id="group_id" name="group_id" class="form-control select2" required="required">--}}
{{--                                            @foreach($form_values['groups'] as $groups)--}}
{{--                                                @if($groups->id == 1)--}}
{{--                                                <option selected value="{{ $groups->id }}">{{ $groups->name }}</option>--}}
{{--                                                @endif--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                for I.T. Department only--}}
                                <input type="hidden" name="group_id" value="{{ $group->id }}">

                                @if($user_is_service_now_agent)
                                <div class="form-group">
                                    <label for="concerned_staff_ara_id">Concerned Staff ARA ID</label>
                                        <select id="concerned_staff_ara_id" name="concerned_staff_ara_id" class="form-control select2" aria-describedby="concerned_staff_ara_idHelpBlock" required>
                                        <option selected disabled>Select One</option>
                                            @foreach($form_values['staff_members'] as $staff_member)
                                                <option value="{{ $staff_member->staff_ara_id }}">{{ $staff_member->surname }} {{ $staff_member->other_names }} (ARA{{ $staff_member->staff_ara_id }})</option>
                                            @endforeach
                                        </select>

                                </div>


                                <div class="form-group">
                                    <label for="user_id">Assigned to</label>
                                        <select id="assigned_to_agent_user_id" name="assigned_to_agent_user_id" class="form-control select2" aria-describedby="assigned_to_agent_user_idHelpBlock" required>
                                        <option selected disabled>Select One</option>
                                            @foreach($form_values['agents'] as $user)
                                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                            @endforeach
                                        </select>
                                </div>

                    <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="notify_agent" value="1">
                          <label class="form-check-label">Notify Assignee</label>
                        </div>
                      </div>


                                <div class="form-group">
                                    <label for="user_id">Escalate to</label>
                                        <select id="escalate_to_user_id" name="escalate_to_user_id" class="form-control select2" aria-describedby="escalate_to_user_idHelpBlock" required>
                                        <option disabled>Select One</option>
                                            @foreach($form_values['agents'] as $user)
                                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                            @endforeach
                                        </select>
                                </div>

                    <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="notify_escalation_user" value="1">
                          <label class="form-check-label">Notify Escalation Lead (upon delayed resolution)</label>
                        </div>
                      </div>


                                <div class="form-group">
                                    <label>Origin Type</label>
                                    <div>
                                        @foreach($form_values['origin_types'] as $key => $value)
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input name="origin_type" id="origin_type_{{ $loop->iteration }}" type="radio" class="custom-control-input" value="{{ $value }}" required="required">
                                                <label for="origin_type_{{ $loop->iteration }}" class="custom-control-label">{{ ucfirst($key) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                    <input type="hidden" name="concerned_staff_ara_id" value="{{ $staff_member->staff_ara_id }}"
                                    <input name="origin_type" type="hidden" value="Integral">
                                @endif

                                <div class="form-group">
                                    <label>Priority Level</label>
                                    <div>
                                        @foreach($form_values['priorities'] as $value)
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input name="priority" id="priorities_{{ $loop->iteration }}" type="radio" class="custom-control-input" value="{{ $value }}" required="required">
                                                <label for="priorities_{{ $loop->iteration }}" class="custom-control-label">{{ ucfirst($value) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <button type="submit" class="btn bg-navy float-right">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('after-scripts')

    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.getElementById('description'))
            .catch(error => {
                console.error(error);
            });
    </script>

<script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
