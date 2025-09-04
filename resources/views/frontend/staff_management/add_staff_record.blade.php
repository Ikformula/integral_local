@extends('frontend.layouts.app')

@section('title', 'Add New Staff Records' )

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-sm-12">
                    <div class="card arik-card shadow">
                        <div class="card-header">
                            <strong>Add New Staff Records</strong>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <form action="{{ route('frontend.staff_info_management.storeStaffRecords') }}" method="POST">
                                    @csrf
{{--                                        <input type="hidden" name="id" value="">--}}

                                        <div class="form-row">
{{--                                            <div class="form-group col-md-6">--}}
{{--                                                <label for="staffId">Staff ID</label>--}}
{{--                                                <input type="text" class="form-control" id="staffId" name="staff_id" placeholder="ARA1090" required>--}}
{{--                                            </div>--}}
                                            <div class="form-group col-md-6">
                                                <label for="staffAraId">Staff ARA ID</label>
                                                <input type="text" class="form-control" id="staffAraId" name="staff_ara_id" PLACEHOLDER="1090" maxlength="5" required>
                                                <small class="text-info">Don't add the 'ARA' prefix</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="@arikair.com">
                                            </div>
                                        </div>

{{--                                        <div class="form-row">--}}
{{--                                            <div class="form-group col-md-6">--}}
{{--                                                <label for="idCardFileName">ID Card File Name</label>--}}
{{--                                                <input type="text" class="form-control" id="idCardFileName" name="id_card_file_name">--}}
{{--                                            </div>--}}
{{--                                            --}}
{{--                                        </div>--}}

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="surname">Surname</label>
                                                <input type="text" class="form-control" id="surname" name="surname">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="otherNames">Other Names</label>
                                                <input type="text" class="form-control" id="otherNames" name="other_names">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="department">Department</label>
                                                <select class="form-control" id="department" name="department_name" required>
                                                    @include('includes.partials._departments-option-list')
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="unit">Unit</label>
                                                <input type="text" class="form-control" id="unit" name="unit">
                                            </div>
                                        </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="jobTitle">Job Title</label>
                                            <input type="text" class="form-control" id="jobTitle" name="job_title" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="grade">Grade</label>
                                            <input type="text" class="form-control" id="grade" name="grade">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="location">Location</label>
                                            <select class="form-control" id="location" name="location">
                                                @foreach($locations as $location)
                                                    <option value="{{$location['location']}}">{{$location['location']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{--                                            <div class="form-group col-md-6">--}}
                                        {{--                                                <label for="location2">Location 2</label>--}}
                                        {{--                                                <select class="form-control" id="location2" name="location_2">--}}
                                        {{--                                                    <option value="locationA">Location A</option>--}}
                                        {{--                                                    <option value="locationB">Location B</option>--}}
                                        {{--                                                    --}}
                                        {{--                                                </select>--}}
                                        {{--                                            </div>--}}
                                        <div class="form-group col-md-6">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" id="gender" name="gender">
                                                <option selected disabled>Please select one</option>
                                                <option>Male</option>
                                                <option>Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="location_in_hq">Location in HQ</label>
                                            <select class="form-control" name="location_in_hq" id="location_in_hq"
                                                    required>
                                                <option selected disabled>Please select an option</option>
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
                                        <div class="form-group col-md-6">
                                            <label for="status">Status</label>
                                            <input type="text" class="form-control" id="status" name="status" value="active">
                                        </div>
                                    </div>

{{--                                        <div class="form-row">--}}
{{--                                            <div class="form-group col-md-6">--}}
{{--                                                <label for="managerAraId">Manager ARA ID</label>--}}
{{--                                                <input type="text" class="form-control" id="managerAraId" name="manager_ara_id">--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group col-md-6">--}}
{{--                                                <label for="department2">Department 2</label>--}}
{{--                                                <input type="text" class="form-control" id="department2" name="department_name_2">--}}
{{--                                            </div>--}}
{{--                                            --}}
{{--                                        </div>--}}

{{--                                        <div class="form-row">--}}
{{--                                            <div class="form-group col-md-6">--}}
{{--                                                <label for="idRemarks">ID Remarks</label>--}}
{{--                                                <textarea class="form-control" id="idRemarks" name="id_remarks" rows="3"></textarea>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group col-md-6">--}}
{{--                                                <label for="idExpiryDate">ID Expiry Date</label>--}}
{{--                                                <input type="date" class="form-control" id="idExpiryDate" name="id_expiry_date">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}



                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="resignedOn">Resigned On</label>
                                                <input type="datetime-local" class="form-control" id="resignedOn" name="resigned_on">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="restrictAccessFrom">Restrict Access From</label>
                                                <input type="datetime-local" class="form-control" id="restrictAccessFrom" name="restrict_access_from">
                                            </div>
                                        </div>

{{--                                        <div class="form-row">--}}
{{--                                            <div class="form-group col-md-6">--}}
{{--                                                <label for="staffTravelBlockedAt">Staff Travel Blocked At</label>--}}
{{--                                                <input type="datetime-local" class="form-control" id="staffTravelBlockedAt" name="staff_travel_blocked_at">--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group col-md-6">--}}
{{--                                                <label for="stbAccessCode">STB Access Code</label>--}}
{{--                                                <input type="text" class="form-control" id="stbAccessCode" name="stb_access_code">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="stbAccessCodeExpiresAt">STB Access Code Expires At</label>
                                                <input type="datetime-local" class="form-control" id="stbAccessCodeExpiresAt" name="stb_access_code_expires_at">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="paypoint">Paypoint</label>
                                                <input type="text" class="form-control" id="paypoint" name="paypoint">
                                            </div>
                                        </div>





                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="currentEmploymentStatus">Current Employment Status</label>
                                                <input type="text" class="form-control" id="currentEmploymentStatus" name="current_employment_status">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="staffCadre">Staff Cadre</label>
                                                <input type="text" class="form-control" id="staffCadre" name="staff_cadre">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="staffCategory">Staff Category</label>
                                                <input type="text" class="form-control" id="staffCategory" name="staff_category">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="employmentCategory">Employment Category</label>
                                                <input type="text" class="form-control" id="employmentCategory" name="employment_category" value="contract staff">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="age">Age</label>
                                                <input type="number" min="0" class="form-control" id="age" name="age">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="yearsOfService">Years of Service</label>
                                                <input type="number" min="0" class="form-control" id="yearsOfService" name="years_of_service">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="region">Region</label>
                                                <input type="text" class="form-control" id="region" name="region">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="state">State</label>
                                                <input type="text" class="form-control" id="state" name="state">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="localGovernmentArea">Local Government Area</label>
                                                <input type="text" class="form-control" id="localGovernmentArea" name="local_government_area">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="maritalStatus">Marital Status</label>
                                                <select class="form-control" id="maritalStatus" name="marital_status">
                                                    <option selected disabled>Please select one</option>
                                                    <option value="single">Single</option>
                                                    <option value="married">Married</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
