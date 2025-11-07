<!-- resources/views/frontend/avsec_vehicles/edit.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Edit Vehicles')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Vehicles</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.avsec_vehicles.update', $item->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        onsubmit="return confirm('Are you sure you want to update this?')">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="staff_ara_id">Staff Member</label>
                            <select name="staff_ara_id" id="staff_ara_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\StaffMember::all() as $option)
                                <option value="{{ $option->staff_ara_id }}" {{ $item->staff_ara_id == $option->staff_ara_id ? 'selected' : '' }}>{{ $option->name_and_ara }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="line_manager_staff_ara_id">Line Manager</label>
                            <select name="line_manager_staff_ara_id" id="line_manager_staff_ara_id" class="form-control" style="width:100%">
                                <option value="">-- Select Line Manager --</option>
                                @foreach(\App\Models\StaffMember::all() as $manager)
                                    <option value="{{ $manager->staff_ara_id }}" {{ old('line_manager_staff_ara_id', $item->line_manager_staff_ara_id) == $manager->staff_ara_id ? 'selected' : '' }}>{{ $manager->name_and_ara }} ({{ $manager->department_name }})</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="car_model">Registered Name on the vehicle</label>
                            <input type="text" name="registered_name_on_vehicle" id="registered_name_on_vehicle" value="{{ $item->registered_name_on_vehicle }}" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Type of Vehicle</label>
                            <input list="vehicle_types" name="vehicle_type" id="vehicle_type" class="form-control" required value="{{ old('vehicle_type', $item->vehicle_type) }}" placeholder="Select or type vehicle type">
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
                            <input type="text" name="car_model" id="car_model" class="form-control" value="{{ $item->car_model }}" required>
                        </div>

                        <div class="form-group">
                            <label for="colour">Current Colour</label>
                            <input type="color" name="colour" id="colour" class="form-control" value="{{ $item->colour }}" required>
                        </div>

                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <input type="text" name="brand" id="brand" class="form-control" value="{{ $item->brand }}" required>
                        </div>

                        <div class="form-group">
                            <label for="reg_number">Registration Number</label>
                            <input type="text" name="reg_number" id="reg_number" class="form-control" value="{{ $item->reg_number }}" required>
                        </div>



                        {{-- <div class="form-group">--}}
                        {{-- <label for="attended_by_user_id">Attended By</label>--}}
                        {{-- <select name="attended_by_user_id" id="attended_by_user_id" class="form-control" required>--}}
                        {{-- <option value="">-- Select --</option>--}}
                        {{-- @foreach(\App\Models\Auth\User::all() as $option)--}}
                        {{-- <option value="{{ $option->id }}" {{ $item->attended_by_user_id == $option->id ? 'selected' : '' }}>{{ $option->full_name }}</option>--}}
                        {{-- @endforeach--}}
                        {{-- </select>--}}
                        {{-- </div>--}}

                        <div class="form-group">
                            <label for="registration_cert">Registration Cert</label>
                            @if($item->registration_cert)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $item->registration_cert) }}" alt="Current Image" class="img-thumbnail" width="150">
                            </div>
                            @endif
                            <input type="file" name="registration_cert" id="registration_cert" class="form-control-file" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label for="proof_of_ownership">Proof Of Ownership</label>
                            @if($item->proof_of_ownership)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $item->proof_of_ownership) }}" alt="Current Image" class="img-thumbnail" width="150">
                            </div>
                            @endif
                            <input type="file" name="proof_of_ownership" id="proof_of_ownership" class="form-control-file" accept="image/*">
                        </div>

                        @if($logged_in_user->can('manage avsec portals'))
                        <div class="form-group">
                            <label for="sticker_number">Sticker Number</label>
                            <input type="text" name="sticker_number" id="sticker_number" class="form-control" value="{{ $item->sticker_number }}" required>
                        </div>

                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="sticker_category_id">
                                <option value="">-- Select One</option>
                                @foreach(\App\Models\AvsecVehicleStickerCategory::all() as $category)
                                <option value="{{ $category->id }}" {{ $item->sticker_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Effective Date</label>
                            <input type="date" class="form-control" name="effective_date" value="{{ substr($item->effective_date, 0, 10) }}">
                        </div>
                        @endif

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.avsec_vehicles.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    document.querySelectorAll('textarea').forEach(function(textarea) {
        ClassicEditor.create(textarea).catch(error => {
            console.error(error);
        });
    });
</script>
@endpush

@push('after-scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
      href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<script>
    document.querySelectorAll('textarea').forEach(function(textarea) {
        ClassicEditor.create(textarea).catch(error => {
            console.error(error);
        });
    });

    $(document).ready(function() {
        $('#line_manager_staff_ara_id').select2({
            placeholder: '-- Select Line Manager --',
            allowClear: true,
            theme: 'bootstrap4'
        });
    });
</script>
@endpush
