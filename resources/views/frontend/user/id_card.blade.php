@extends('frontend.layouts.app')

@section('title', 'Staff ID Card' )

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .id_card_card {
            position: -webkit-sticky;
            position: sticky;
            top: 100px;
        }
    </style>
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-sm-9">
                    <div class="callout callout-info mb-3">
                        <h5>{{ $department_members_uploaded_id_count }} out of {{ $department_members_count }}</h5>

                        <p>Number of staff members in {{ $staff->department_name }} who have updated their ID card on
                            this portal</p>
                    </div>

                    @include('frontend.includes._staff-info-summary')

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-maroon card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="staff-info-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="staff-info-id-info-tab" data-toggle="pill" href="#staff-info-id-info" role="tab" aria-controls="staff-info-id-info" aria-selected="true">ID Info</a>
                                        </li>
                                        @if($logged_in_user->can('manage own unit info') || $logged_in_user->can('update other staff info'))
                                        <li class="nav-item">
                                            <a class="nav-link" id="staff-info-manager-setting-tab" data-toggle="pill" href="#staff-info-manager-setting" role="tab" aria-controls="staff-info-manager-setting" aria-selected="false">Manager Setting</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="staff-info-leavings-tab" data-toggle="pill" href="#staff-info-leavings" role="tab" aria-controls="staff-info-leavings" aria-selected="false">Leaving/Access Control</a>
                                        </li>
                                            @if($hybrid_work_schedules && count($hybrid_work_schedules))
                                        <li class="nav-item">
                                            <a class="nav-link" id="hybrid-work-arrangement-tab" data-toggle="pill" href="#hybrid-work-arrangement" role="tab" aria-controls="hybrid-work-arrangement" aria-selected="false">Hybrid Work Arrangement</a>
                                        </li>
                                        @endif
                                        @endif
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="staff-info-tabContent">
                                        <div class="tab-pane fade show active" id="staff-info-id-info" role="tabpanel" aria-labelledby="staff-info-id-info-tab">
                                            <div class="card arik-card animate__animated animate__backInDown">
                                                {{--                        @if(is_null($staff->id_card_file_name) || $logged_in_user->can('manage own unit info') || $logged_in_user->can('update other staff info'))--}}
                                                <div class="card-header">
                                                    <h3 class="card-title">
                                                        ID Card Update (Kindly fill and submit the form below)
                                                    </h3>
                                                </div>
                                                <div class="card-body">
                                                    <form action="{{ route('frontend.user.profile.uploadIDcard') }}" method="POST"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="staff_ara_id" value="{{ $staff->staff_ara_id }}">
                                                        <div class="form-group">
                                                            <label for="id_card_file">ID Card Photo</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="id_card_file"
                                                                           id="id_card_file" {{ isset($staff->id_card_file_name) ? '' : 'required' }} accept="image/jpeg, image/JPEG, image/png, image/PNG, image/jpg, image/JPG" onchange="showImgPreview(event)">
                                                                    <label class="custom-file-label" for="id_card_file">Choose Scanned
                                                                        image File</label>
                                                                </div>
                                                            </div>
                                                            <span class="text-info">Please scan/capture without the ID card casing</span>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="surname">Surname</label>
                                                            <input type="text" class="form-control"
                                                                   value="{{ isset($staff->surname) ? $staff->surname : '' }}" id="surname"
                                                                   name="surname" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="other_names">Other Names</label>
                                                            <input type="text" class="form-control"
                                                                   value="{{ isset($staff->other_names) ? $staff->other_names : '' }}"
                                                                   id="other_names" name="other_names" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="paypoint">Paypoint</label>
                                                            <input type="text" class="form-control"
                                                                   value="{{ isset($staff->paypoint) ? $staff->paypoint : '' }}"
                                                                   id="paypoint" name="paypoint" maxlength="3" placeholder="LOS">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="location">Location</label>
                                                            <select class="form-control" name="location" id="location" required>
                                                                @if(isset($staff->location))
                                                                    <option selected>{{ $staff->location }}</option>
                                                                @else
                                                                    <option selected disabled>Please select an option</option>
                                                                @endif
                                                                <option>ABUJA FCT</option>
                                                                <option>ABIA</option>
                                                                <option>ADAMAWA</option>
                                                                <option>AKWA IBOM</option>
                                                                <option>ANAMBRA</option>
                                                                <option>BAUCHI</option>
                                                                <option>BAYELSA</option>
                                                                <option>BENUE</option>
                                                                <option>BORNO</option>
                                                                <option>CROSS RIVER</option>
                                                                <option>DELTA</option>
                                                                <option>EBONYI</option>
                                                                <option>EDO</option>
                                                                <option>EKITI</option>
                                                                <option>ENUGU</option>
                                                                <option>GOMBE</option>
                                                                <option>IMO</option>
                                                                <option>JIGAWA</option>
                                                                <option>KADUNA</option>
                                                                <option>KANO</option>
                                                                <option>KATSINA</option>
                                                                <option>KEBBI</option>
                                                                <option>KOGI</option>
                                                                <option>KWARA</option>
                                                                <option>LAGOS</option>
                                                                <option>NASSARAWA</option>
                                                                <option>NIGER</option>
                                                                <option>OGUN</option>
                                                                <option>ONDO</option>
                                                                <option>OSUN</option>
                                                                <option>OYO</option>
                                                                <option>PLATEAU</option>
                                                                <option>RIVERS</option>
                                                                <option>SOKOTO</option>
                                                                <option>TARABA</option>
                                                                <option>YOBE</option>
                                                                <option>ZAMFARA</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="department_name">Department</label>
                                                            <select class="form-control" name="department_name" id="department_name" required>
                                                                @if(isset($staff->department_name))
                                                                    <option selected>{{ $staff->department_name }}</option>
                                                                @else
                                                                    <option selected disabled>Please select an option</option>
                                                                @endif
                                                                @include('includes.partials._departments-option-list')
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="location_in_hq">Current location at head office</label>
                                                            <select class="form-control" name="location_in_hq" id="location_in_hq"
                                                                    required>
                                                                @if(isset($staff->location_in_hq))
                                                                    <option selected>{{ $staff->location_in_hq }}</option>
                                                                @else
                                                                    <option selected disabled>Please select an option</option>
                                                                @endif
                                                                <option>Old building</option>
                                                                <option>New building</option>
                                                                <option>Flight ops building</option>
                                                                <option>Technical hangar</option>
                                                                <option>Transport maintenance</option>
                                                                <option>Catering</option>
                                                                <option>Commercial store</option>
                                                                <option>Main gate</option>
                                                                <option>Domestic Airport</option>
                                                                <option>International Airport</option>
                                                                <option>Outstation</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="id_expiry_date">ID Card Expiry Date</label>
                                                            <input type="date" name="id_expiry_date" class="form-control"
                                                                   id="id_expiry_date" min="{{ \Carbon\Carbon::today()->toDateString() }}"
                                                                   required
                                                                   value="{{ isset($staff->id_expiry_date) ? $staff->id_expiry_date : ''}}">
                                                            <span class="text-muted">This will help for renewal of the ID card</span>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="remarks">Remarks (If any; with regards to your ID Card)</label>
                                                            <textarea class="form-control" name="remarks" id="remarks">{{ $staff->id_remarks ?? '' }}</textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="">Email</label>
                                                            <input type="email" name="email" id="email" class="form-control"
                                                                   value="{{ $staff->email }}" aria-describedby="help_email">
                                                            <small id="help_email" class="text-muted">Official Arik Email</small>
                                                        </div>

                                                        @if($logged_in_user->can('manage own unit info') || $logged_in_user->can('update other staff info'))
                                                        <div class="form-group">
                                                            <label for="employment_category">Employment Category</label>
                                                            <select class="form-control" name="employment_category" id="employment_category">
                                                                @if($staff->employment_category)<option value="{{ $staff->employment_category }}">{{ $staff->employment_category }}</option>@endif
                                                                <option value="full staff">Full Staff</option>
                                                                <option value="contract">Contract</option>
                                                                <option value="NYSC">NYSC</option>
                                                                <option value="intern">Intern</option>
                                                            </select>
                                                        </div>

                                                        <label>Shift Non Shift Status</label>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <!-- radio -->
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="shift_nonshift"
                                                                                   id="NONSHIFT" value="NON-SHIFT" {{ $staff->shift_nonshift == 'NON-SHIFT' ? 'checked' : ''}}>
                                                                            <label class="form-check-label">Non-Shift</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="shift_nonshift"
                                                                                   id="SHIFT" value="SHIFT" {{ $staff->shift_nonshift == 'SHIFT' ? 'checked' : ''}}>
                                                                            <label class="form-check-label">Shift</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                {{--                        @endif--}}
                                                <div class="card-footer">
                                                    <span>If you have any issues or complaints kindly send a message to dapasisi.tom-west@arikair.com or mariam.omoniyi@arikair.com</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($logged_in_user->can('manage own unit info') || $logged_in_user->can('update other staff info'))
                                        <div class="tab-pane fade" id="staff-info-manager-setting" role="tabpanel" aria-labelledby="staff-info-manager-setting-tab">
                                                <form action="{{ route('frontend.user.profile.updateManager') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="staff_ara_id" value="{{ $staff->staff_ara_id }}">

                                                            <div class="form-group">
                                                                <label for="manager_ara_id">Manager</label>
                                                                <select class="form-control" name="manager_ara_id" id="manager_ara_id">
                                                                    @if(!is_null($manager))
                                                                        <option selected value="{{ $staff->manager_ara_id }}">{{ $manager->name }}, {{ $staff->manager_ara_id }}</option>
                                                                    @else
                                                                        <option selected>Please Select One</option>
                                                                    @endif
                                                                    @foreach($department_members as $staff_member)
                                                                        <option value="{{ $staff_member->staff_ara_id }}">{{ $staff_member->name }}, {{ $staff_member->staff_ara_id }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <button type="submit" class="btn btn-primary float-right">Update</button>
                                                        </form>
                                        </div>
                                        <div class="tab-pane fade" id="staff-info-leavings" role="tabpanel" aria-labelledby="staff-info-leavings-tab">
                                            <form action="{{ route('frontend.user.init.deactivateStaff') }}" onsubmit="return confirm('Proceeding will cause this staff member\'s  account to be disabled at a set time, are you sure you want to proceed?')" method="POST">
                                                @csrf
                                                <input type="hidden" name="staff_ara_id" value="{{ $staff->staff_ara_id }}">
                                                <div class="form-group">
                                                    <label>Resignation Date</label>
                                                    <input type="date" class="form-control" name="resigned_on">
                                                </div>
                                                <div class="form-group">
                                                    <label>Deactivate on</label>
                                                    <input type="date" class="form-control" name="deactivate_from" value="{{ now()->toDateString() }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Restrict access from</label>
                                                    <input type="date" class="form-control" name="restrict_access_from" value="{{ now()->toDateString() }}">
                                                </div>
                                                <button type="submit" class="btn btn-danger float-right">Submit</button>
                                            </form>
                                        </div>

                                        @if($hybrid_work_schedules && count($hybrid_work_schedules))
                                            <div class="tab-pane fade" id="hybrid-work-arrangement" role="tabpanel" aria-labelledby="hybrid-work-arrangement-tab">
        <table class="table table-borderless table-striped">
            <thead>
            <tr>
                <th>Week Day</th>
                <th>Location</th>
                <th>Commenced</th>
                <th>Ended</th>
            </tr>
            </thead>
            <tbody>
            @foreach($hybrid_work_schedules as $schedule)
                <tr>
                    <td>{{ ucfirst($schedule->week_day) }}</td>
                    <td>{{ $schedule->location ?? 'On duty'}}</td>
                    <td>@if($schedule->commenced_on) {{ substr($schedule->commenced_on, 0, 10) }} @endif</td>
                    <td>@if($schedule->ended_on) {{ substr($schedule->ended_on, 0, 10) }} @endif</td>
                </tr>
                @endforeach
            <tr>
                <td colspan="4"><strong>Add New Update</strong></td>
            </tr>
            <form action="{{ route('frontend.user.storeRemoteSchedule') }}" method="POST">
                @csrf
            <tr>
                <input type="hidden" name="staff_ara_id" value="{{ $staff->staff_ara_id }}">
                <td>
                    <div class="form">
                        @php($week_days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'])
                        <select name="week_day" class="form-control" required>
                            <option selected disabled>Select a Day</option>
                            @foreach($week_days as $week_day)
                            <option value="{{ $week_day }}">{{ ucfirst($week_day) }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form">
                        @php($locations = ['Remote', 'On duty'])
                        <select name="location" class="form-control" required>
                            <option selected disabled>Select an option</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}">{{ ucfirst($location) }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form">
                        <input type="date" name="commenced_on" class="form-control" required>
                        <label>Commences on</label>
                    </div>
                </td>
                <td>
                    <button type="submit" class="btn bg-navy">Submit</button>
                </td>
            </tr>
            </form>
            </tbody>
        </table>
                                            </div>
                                            @endif

                                        @endif
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>





                </div>
                </div>
                <div class="col-sm-3">
                    <div class="card animate__animated animate__backInDown id_card_card">
                        <img class="card-img rounded"
                             src="{{ !is_null($staff->id_card_file_name) ? asset('img/id_cards/'.$staff->id_card_file_name) : asset('img/ID Card-cuate.svg') }}"
                             id="id-card-preview" alt="ID card for {{ $staff->name }}, {{ $staff->staff_ara_id }}">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>

    <script>
        function showImgPreview(event) {
    if (event.target.files.length > 0) {
        var file = event.target.files[0];
        var allowedTypes = ["image/jpeg", "image/png", "image/jpg"]; // Add any other image MIME types you want to allow

        if (allowedTypes.includes(file.type)) {
            var src = URL.createObjectURL(file);
            var preview = document.getElementById("id-card-preview");
            preview.src = src;
            // preview.style.display = "block";
        } else {
            alert("Please select a valid image file.");
            // Reset the file input if needed
            event.target.value = "";
        }
    }
}
    </script>
@endpush
