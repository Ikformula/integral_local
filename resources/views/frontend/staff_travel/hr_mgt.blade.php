@extends('frontend.layouts.app')

@section('title', 'Staff Travel Settings')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-hr-advisors-tab" data-toggle="pill"
                                       href="#custom-tabs-one-hr-advisors" role="tab"
                                       aria-controls="custom-tabs-one-hr-advisors"
                                       aria-selected="false">HR Advisors</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-reg-Window-tab" data-toggle="pill"
                                       href="#custom-tabs-one-reg-Window" role="tab" aria-controls="custom-tabs-one-reg-Window"
                                       aria-selected="true">Reg. Window</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill"
                                       href="#custom-tabs-one-messages" role="tab"
                                       aria-controls="custom-tabs-one-messages"
                                       aria-selected="false">Email</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-hr-advisors" role="tabpanel"
                                     aria-labelledby="custom-tabs-one-hr-advisors-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="mb-0">HR Advisors Assignment</h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class="row">
                                                        <div class="col">

                                                            <!-- Add Permission Form -->
                                                            <form id="addPermissionFormm" action="{{ route('frontend.staff_travel.permissions.store') }}" class="mb-4" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <select name="staff_ara_id" class="form-control" required id="user-select">
                                                                            <option value="">Select Advisor</option>
                                                                            @foreach($staff_members as $staff_member)
                                                                                <option value="{{ $staff_member->staff_ara_id }}">{{ $staff_member->name_and_ara }},
                                                                                    ({{ $staff_member->email }})
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <select name="department_name" class="form-control" required>
                                                                            <option disabled selected>Select Department</option>
                                                                            @include('includes.partials._departments-option-list')
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="submit" class="btn btn-primary w-100">Assign</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <!-- Permissions Table -->
                                                    <div class="table-responsive">
                                                        <table id="permissions-table" class="table table-bordered table-striped w-100">
                                                            <thead>
                                                            <tr>
{{--                                                                <th>S/N</th>--}}
                                                                <th>Advisor</th>
                                                                <th>Department</th>
                                                                <th>Last Updated</th>
                                                                <th colspan="2">Actions</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="permissions-tbody">

                                                                @foreach($hr_advisors as $hr_advisor)
                                                                @php
                                                                    $staff_member = $hr_advisor->staff_member;
                                                                @endphp
                                                                <tr data-id="{{ $loop->iteration }}">
                                                                    <td>{{ $staff_member->name_and_ara }}</td>

                                                                    <form id="" action="{{ route('frontend.staff_travel.permissions.update', $hr_advisor->id) }}" class="mb-4" method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                    <td>
                                                                        <span class="department-name">{{ $hr_advisor->department_name }}</span>
                                                                        <select class="form-control department-edit" name="department_name">
                                                                            @foreach($departments as $department)
                                                                                <option {{ $department == $hr_advisor->department_name ? 'selected' : '' }}>
                                                                                    {{ $department }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>{{ $hr_advisor->updated_at }}</td>
                                                                    <td>
                                                                        <button class="btn btn-sm btn-warning edit-btn" type="submit">Update</button>
                                                                    </td>
                                                                    </form>

                                                                    <td>
                                                                    <form id="" action="{{ route('frontend.staff_travel.permissions.delete', $hr_advisor->id) }}" class="mb-4" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                            <button class="btn btn-sm btn-danger delete-btn" type="submit">Delete</button>
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
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-reg-Window" role="tabpanel"
                                     aria-labelledby="custom-tabs-one-reg-Window-tab">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Year</th>
                                            <th>Open at</th>
                                            <th>Close at</th>
                                            <th width="10rem">Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <form action="{{ route('frontend.staff_travel.store.window') }}" method="POST" onsubmit="return confirm('This action will close any open registration windows, proceed?');">
                                            @csrf
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><input type="date" name="from_date" class="form-control" required></td>
                                                <td><input type="date" name="to_date" class="form-control" required></td>
                                                <td><input type="text" name="remarks" class="form-control-lg"></td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary">Add New Window</button>
                                                </td>
                                            </tr>
                                        </form>
                                        @foreach($stb_reg_windows as $window)
                                            <form class="form-inline" method="post" action="{{ route('frontend.staff_travel.close.window', ['stbRegistrationWindow' => $window]) }}" {{ $now > $window->from_date && $now < $window->to_date || is_null($window->closed_at)? '' : 'disabled'}}>
                                                @csrf
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $window->window_year }}</td>
                                                    <td>{{ substr($window->from_date, 0, 10) }}</td>
                                                    <td>{{ substr($window->to_date, 0, 10) }}</td>

                                                    <td><input type="text" class="form-control-lg" required name="remarks" value="{{ $window->remarks }}"></td>
                                                    <td>
                                                        <button type="submit" class="btn btn-warning {{ !is_null($window->closed_at) ? 'disabled' : '' }}">Close</button>
                                                    </td>
                                                </tr>
                                            </form>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel"
                                     aria-labelledby="custom-tabs-one-messages-tab">
                                    @if(isset($current_window))
                                    <form action="{{ route('frontend.staff_travel.send.email') }}" method="POST" onsubmit="return confirm('Confirm you want to send this email. {{ $now_in_current_window ? '' : ' We are not currently in this time window.' }}')">
                                        @csrf
                                        <div class="form-group mt-3">
                                            <label>Email Address(es) to Send to</label>
                                            <textarea class="form-control" name="emails">arikleanoperation@arikair.com</textarea>
                                            <small class="text-muted">If more than one email address, separate each using commas.</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Cc</label>
                                            <textarea class="form-control" name="cc_emails"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Bcc</label>
                                            <textarea class="form-control" name="bcc_emails"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="email_body">Email Body</label>
                                            <textarea class="form-control" name="email_body" id="email_body" rows="3">
                                                Dear Team,<br> <br> The Staff Travel Beneficiary portal is open for registration of your friends and family<br> from&nbsp;<b>{{ $current_window->from_date->toDateString() }}</b><br> <strong></strong> to <strong>{{ $current_window->to_date->toDateString() }}</strong>.<br> <br> Kindly take advantage of this window to get all your beneficiaries registered accurately as no registration or modifications will be allowed outside this window.<br> Do reach out to your HR advisor for any concerns you may have about the registration.<br> <br> Thanks and regards,<br> HR Team.

<br>
                                            </textarea>
                                        </div>

                                            <div class="form-group">
                                            <button type="submit" class="btn btn-danger float-right">Send Email</button>
                                            </div>
                                    </form>
                                    @else
                                    <span class="text-info">No open registration window</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>


        </div>
    </section>
@endsection


@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.BBBselect').select2({
            theme: 'bootstrap4'
        });

        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#email_body'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush

