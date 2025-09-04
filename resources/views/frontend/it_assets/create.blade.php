@extends('frontend.layouts.app')

@section('title', 'Add IT Asset' )


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
                <h3 class="card-title">Register IT Asset</h3>
            </div>
            <div class="card-body">
                <form>
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $logged_in_user->id }}">
                    <div class="row">
                        <div class="col-md-6">
                    <div class="form-group">
                        <label for="staff_ara_id">Staff Member</label>
                        <select class="form-control select2" id="staff_ara_id" name="staff_ara_id">
                            <option value="" selected>Select One (If Applicable)</option>
                            @foreach($data['staff_members'] as $staff_member)
                                <option value="{{ $staff_member->staff_ara_id }}">{{ $staff_member->name }}, {{ $staff_member->staff_ara_id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" class="form-control" id="model" name="model" />
                    </div>

                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" list="brandsList" />
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
                                            <input type="radio" class="form-check-input" id="group_yes" name="group" value="yes" />
                                            <label class="form-check-label" for="group_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="group_no" name="group" value="no" />
                                            <label class="form-check-label" for="group_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Sophos Endpoint</label>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="sophos_yes" name="sophos_endpoint" value="yes" />
                                            <label class="form-check-label" for="sophos_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="sophos_no" name="sophos_endpoint" value="no" />
                                            <label class="form-check-label" for="sophos_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                    <div class="form-group">
                        <label for="office_location">Office Location</label>
                        <select class="form-control" id="office_location" name="office_location">
                            @foreach($data['office_location'] as $office_location)
                                <option>{{ $office_location }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="department_name">Department Name</label>
                        <select class="form-control" id="department_name" name="department_name" required>
                            <option disabled selected>Select One</option>
                            @foreach($data['department_names'] as $department_name)
                            <option value="{{ $department_name->department_name }}">{{ $department_name->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    </div>

                    <div class="col-md-6">

                    <div class="form-group">
                        <label for="device_type">Device Type</label>
                        <select class="form-control" id="device_type" name="device_type">
                            @foreach($data['device_type'] as $device_type)
                            <option>{{ $device_type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="serial_number">Serial Number</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number" />
                    </div>

                    <div class="form-group">
                        <label for="asset_tag">Asset Tag</label>
                        <input type="text" class="form-control" id="asset_tag" name="asset_tag" />
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="in service">In Service</option>
                            <option value="serviceable">Serviceable</option>
                            <option value="nonserviceable">Non-Serviceable</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="1"></textarea>
                    </div>

                        <h6>Add Extra Information for this Asset</h6>
                        <small>e.g Key: RAM, Value: 8GB</small>
                        <div class="row py-2" id="meta-1">
                            <div class="col-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="asset_meta_key[1]" list="asset_meta_keys_datalist">
                                    <label>Key</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="asset_meta_value[1]">
                                    <label>Value</label>
                                </div>
                            </div>
                            <div class="col-2">
{{--                                <button type="button" class="btn btn-danger" onclick="removeThisMeta(1)"><i class="fa fa-times"></i>Remove</button>--}}
                                <button type="button" class="btn btn-primary" onclick="AddNewMeta(1)"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    </div>
                    <!-- Add a submit button here -->
                    <button type="reset" class="btn bg-maroon">Reset</button>
                    <button type="submit" class="btn bg-navy float-right" id="submit-btn">Submit</button>
                </form>
            </div>
        </div>
        </div>
    </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        document.querySelector('form').addEventListener('submit', async (e) => {
            e.preventDefault();

            {{--let asset_keys = {{ json_decode($data['asset_meta_keys']) }};--}}
            $('#submit-btn').attr('disabled', true).html('Submitting');
            const formData = new FormData(e.target);
            const url = '{{ route('frontend.it_assets.store') }}';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Request failed.');
                }

                const data = await response.json();

                if (data.status === 'success') {
                    showInstantToast('IT Asset Registered', 'success');
                } else {
                    // Handle other statuses if needed
                    console.log('Error: Status is not success.');
                    if(typeof data.message !== 'undefined'){
                        showInstantToast(data.message, 'danger');
                    }else{
                        showInstantToast('IT Asset Not Registered', 'danger');
                    }

                }
                const meta_response = data.asset_meta_keys;
                const dataList = document.getElementById('asset_meta_keys_datalist');

                // Clear existing options (if any)
                dataList.innerHTML = '';

                // Populate the datalist with options from the response
                meta_response.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.meta_key;
                    dataList.appendChild(option);
                });

                $('#submit-btn').attr('disabled', false).html('Submit');
            } catch (error) {
                console.error('Error:', error);
                showInstantToast('There was an error: IT Asset Not Registered', 'danger');
                $('#submit-btn').attr('disabled', false).html('Submit');
            }
        });

    </script>
        @include('frontend.it_assets._js-scripts', ['starting_count' => 1])

@endpush
