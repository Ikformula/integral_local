<!-- resources/views/frontend/staff_travel_beneficiaries/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New Staff Travel Beneficiary')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Staff Travel Beneficiary</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.staff_travel_beneficiaries.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="staff_ara_id">Staff ARA ID</label>
                            <select name="staff_ara_id" id="staff_ara_id" class="form-control" required>
                                @if($staffMembers->count() > 1)
                                <option value="">-- Select --</option>
                                @endif
                                @foreach($staffMembers as $staffMember)
                                    {{$staffMember}}
                                    <option value="{{ $staffMember->staff_ara_id }}">{{ $staffMember->name }}, {{ $staffMember->staff_ara_id }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="firstname">Firstname</label>
                            <input type="text" name="firstname" id="firstname" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="surname">Surname</label>
                            <input type="text" name="surname" id="surname" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="other_name">Other Name</label>
                            <input type="text" name="other_name" id="other_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" name="dob" id="dob" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Gender</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="Male" value="Male" required>
                                <label class="form-check-label" for="Male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="Female" value="Female">
                                <label class="form-check-label" for="Female">Female</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="relationship">Relationship</label>
                            <select name="relationship" id="relationship" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option>Wife</option>
                                <option>Husband</option>
                                <option>Child</option>
                                <option>Father</option>
                                <option>Mother</option>
                                <option>Brother</option>
                                <option>Sister</option>
                                <option>Extended Family</option>
                                <option>Friend</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" name="photo" id="photo" class="form-control" required>
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="posted_by">Posted By</label>--}}
{{--                            <select name="posted_by" id="posted_by" class="form-control" required>--}}
{{--                                <option value="">-- Select --</option>--}}
{{--                                @foreach(\App\Models\Auth\User::all() as $option)--}}
{{--                                    <option value="{{ $option->id }}">{{ $option->full_name }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <label for="status">Status</label>--}}
{{--                            <input type="text" name="status" id="status" class="form-control" required>--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <label for="actioned_by">Actioned By</label>--}}
{{--                            <select name="actioned_by" id="actioned_by" class="form-control" required>--}}
{{--                                <option value="">-- Select --</option>--}}
{{--                                @foreach(\App\Models\Auth\User::all() as $option)--}}
{{--                                    <option value="{{ $option->id }}">{{ $option->full_name }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <label for="actioned_time">Actioned Time</label>--}}
{{--                            <input type="datetime-local" name="actioned_time" id="actioned_time" class="form-control" required>--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <label for="actioned_comment">Actioned Comment</label>--}}
{{--                            <textarea class="form-control" name="actioned_comment" id="actioned_comment" rows="5"></textarea>--}}
{{--                        </div>--}}

                        <button type="submit" class="btn btn-primary">Save</button>
                        @if(isset($_GET['personal']) && $_GET['personal'] == 1)
                            <input type="hidden" name="personal" value="1">
                        <a href="{{ route('frontend.staff_travel_beneficiaries.index.mine') }}" class="btn btn-secondary">Cancel</a>
                        @else
                        <a href="{{ route('frontend.staff_travel_beneficiaries.index') }}" class="btn btn-secondary">Cancel</a>
                            @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('select').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
