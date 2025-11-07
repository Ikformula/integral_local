<!-- resources/views/frontend/avsec_vehicles/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New Vehicles')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Register a Vehicle</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.avsec_vehicles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @php
                        $staff_member = $logged_in_user->staff_member;
                        @endphp

                        <div class="form-group">
                            <label for="staff_ara_id">Staff Member</label>
                            <select name="staff_ara_id" id="staff_ara_id" class="form-control" required>
                                @if($logged_in_user->can('manage avsec portals') || $logged_in_user->can('update other staff info'))
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\StaffMember::all() as $option)
                                <option value="{{ $option->staff_ara_id }}">{{ $option->name_and_ara }}</option>
                                @endforeach
                                @else
                                <option value="{{ $staff_member->staff_ara_id }}">{{ $staff_member->name_and_ara }}</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="line_manager_staff_ara_id">Line Manager</label>
                            <select name="line_manager_staff_ara_id" id="line_manager_staff_ara_id" class="form-control" style="width:100%">
                                <option value="">-- Select Line Manager --</option>
                                @foreach(\App\Models\StaffMember::all() as $manager)
                                    <option value="{{ $manager->staff_ara_id }}" {{ old('line_manager_staff_ara_id') == $manager->staff_ara_id ? 'selected' : '' }}>{{ $manager->name_and_ara }} ({{ $manager->department_name }})</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="car_model">Registered Name on the vehicle</label>
                            <input type="text" name="registered_name_on_vehicle" id="registered_name_on_vehicle" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Type of Vehicle</label>
                            <input list="vehicle_types" name="vehicle_type" id="vehicle_type" class="form-control" required value="{{ old('vehicle_type') }}" placeholder="Select or type vehicle type">
                            <datalist id="vehicle_types">
                                <option value="Sedan"></option>
                                <option value="Salon"></option>
                                <option value="HatchBack"></option>
                                <option value="Station Wagon"></option>
                                <option value="SUV"></option>
                                <option value="Minivan"></option>
                                <option value="Van"></option>
                                <option value="Pickup"></option>
                                <option value="Motorcycle"></option>
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label for="car_model">Model</label>
                            <input type="text" name="car_model" id="car_model" class="form-control" required>
                        </div>


                        <div class="form-group">
                            <label for="reg_number">Registered Plate number</label>
                            <input type="text" name="reg_number" id="reg_number" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="colour">Current Colour</label>
                            <input type="color" name="colour" id="colour" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <input type="text" name="brand" id="brand" class="form-control" required>
                        </div>


                        {{-- @if($logged_in_user->can('manage avsec portals'))--}}
                        {{-- <div class="form-group">--}}
                        {{-- <label for="sticker_number">Sticker Number</label>--}}
                        {{-- <input type="text" name="sticker_number" id="sticker_number" class="form-control" required>--}}
                        {{-- </div>--}}
                        {{-- @endif--}}

                        {{-- <div class="form-group">--}}
                        {{-- <label for="attended_by_user_id">Attended By</label>--}}
                        {{-- <select name="attended_by_user_id" id="attended_by_user_id" class="form-control" required>--}}
                        {{-- <option value="">-- Select --</option>--}}
                        {{-- @foreach(\App\Models\Auth\User::all() as $option)--}}
                        {{-- <option value="{{ $option->id }}">{{ $option->full_name }}</option>--}}
                        {{-- @endforeach--}}
                        {{-- </select>--}}
                        {{-- </div>--}}

                        <div class="form-group">
                            <label for="registration_cert">Registration Cert</label>
                            <input type="file" name="registration_cert" id="registration_cert" class="form-control-file" accept="image/*" onchange="previewImage(event, 'registration_cert')">
                            <div class="mt-2">
                                <img id="preview_registration_cert" src="#" alt="Preview" class="img-thumbnail" style="display:none; max-height:150px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="proof_of_ownership">Proof Of Ownership</label>
                            <input type="file" name="proof_of_ownership" id="proof_of_ownership" class="form-control-file" accept="image/*" onchange="previewImage(event, 'proof_of_ownership')">
                            <div class="mt-2">
                                <img id="preview_proof_of_ownership" src="#" alt="Preview" class="img-thumbnail" style="display:none; max-height:150px;">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('frontend.avsec_vehicles.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<!-- Load CKEditor only if needed -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    document.querySelectorAll('textarea').forEach(function(textarea) {
        if (textarea.id) {
            ClassicEditor.create(textarea).catch(error => {
                console.error(error);
            });
        }
    });

    function previewImage(event, fieldId) {
        var input = event.target;
        var preview = document.getElementById('preview_' + fieldId);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

@push('after-scripts')
{{--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}
{{--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
{{--<script>--}}
{{--    $(document).ready(function() {--}}
{{--        $('#line_manager_staff_ara_id').select2({--}}
{{--            placeholder: '-- Select Line Manager --',--}}
{{--            allowClear: true,--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}

<script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $('select').select2({
        placeholder: '-- Select One --',
        allowClear: true,
        theme: 'bootstrap4'
    });
</script>
@endpush
