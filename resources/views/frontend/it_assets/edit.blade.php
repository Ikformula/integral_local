@extends('frontend.layouts.app')

@section('title', 'Edit IT Asset' )


@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush


@section('content')
    <div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <div class="card arik-card">
            <div class="card-header">
                <h3 class="card-title">Edit IT Asset</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('frontend.it_assets.update', $it_asset->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $logged_in_user->id }}">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="staff_ara_id">Staff Member</label>
                                <select class="form-control select2" id="staff_ara_id" name="staff_ara_id">
                                    <option value="" selected>Select One (If Applicable)</option>
                                    @foreach($data['staff_members'] as $staff_member)
                                        <option value="{{ $staff_member->staff_ara_id }}" {{ $it_asset->staff_ara_id == $staff_member->staff_ara_id ? 'selected' : '' }}>
                                            {{ $staff_member->name }}, {{ $staff_member->staff_ara_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" class="form-control" id="model" name="model" value="{{ $it_asset->model }}" />
                            </div>

                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand" list="brandsList" value="{{ $it_asset->brand }}" />
                                <datalist id="brandsList">
                                    @foreach($data['brands'] as $brand)
                                        <option value="{{ $brand }}">{{ $brand }}</option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Group</label>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="group_yes" name="group" value="yes" {{ $it_asset->group == 'yes' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="group_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="group_no" name="group" value="no" {{ $it_asset->group == 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="group_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Sophos Endpoint</label>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="sophos_yes" name="sophos_endpoint" value="yes" {{ $it_asset->sophos_endpoint == 'yes' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sophos_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="sophos_no" name="sophos_endpoint" value="no" {{ $it_asset->sophos_endpoint == 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sophos_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="office_location">Office Location</label>
                                <select class="form-control" id="office_location" name="office_location">
                                    @foreach($data['office_location'] as $office_location)
                                        <option {{ $it_asset->office_location === $office_location ? 'selected' : '' }}>{{ $office_location }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="department_name">Department Name</label>
                                <select class="form-control select2" id="department_name" name="department_name" required>
                                    <option disabled>Select One</option>
                                    @foreach($data['department_names'] as $department_name)
                                        <option value="{{ $department_name->department_name }}" {{ $it_asset->department_name === $department_name->department_name ? 'selected' : '' }}>
                                            {{ $department_name->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="device_type">Device Type</label>
                                <select class="form-control" id="device_type" name="device_type">
                                    @foreach($data['device_type'] as $device_type)
                                        <option {{ $it_asset->device_type === $device_type ? 'selected' : '' }}>{{ $device_type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="serial_number">Serial Number</label>
                                <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ $it_asset->serial_number }}" />
                            </div>

                            <div class="form-group">
                                <label for="asset_tag">Asset Tag</label>
                                <input type="text" class="form-control" id="asset_tag" name="asset_tag" value="{{ $it_asset->asset_tag }}" />
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="in service" {{ $it_asset->status === 'in service' ? 'selected' : '' }}>In Service</option>
                                    <option value="serviceable" {{ $it_asset->status === 'serviceable' ? 'selected' : '' }}>Serviceable</option>
                                    <option value="nonserviceable" {{ $it_asset->status === 'nonserviceable' ? 'selected' : '' }}>Non-Serviceable</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="1">{{ $it_asset->remarks }}</textarea>
                            </div>

                            <h6>Extra Information for this Asset</h6>
                            <small>e.g Key: RAM, Value: 8GB</small>

                            @foreach($it_asset->assetMeta as $index => $asset_meta)
                                <div class="row py-2" id="meta-{{ $index + 1 }}">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="asset_meta_key[{{ $index + 1 }}]" value="{{ $asset_meta->meta_key }}">
                                            <label>Key</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="asset_meta_value[{{ $index + 1 }}]" value="{{ $asset_meta->meta_value }}">
                                            <label>Value</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-danger" onclick="removeThisMeta({{ $index + 1 }})"><i class="fa fa-times"></i></button>
                                        <button type="button" class="btn btn-primary" onclick="AddNewMeta({{ $index + 1 }})"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>
                    <!-- Add a submit button here -->
                    <button type="submit" class="btn bg-navy float-right">Update</button>
                </form>

            </div>
        </div>
        </div>
    </div>
    </div>
@endsection

@push('after-scripts')
    @include('frontend.it_assets._js-scripts', ['starting_count' => $it_asset->assetMeta->count() + 1])
@endpush
