@extends('frontend.layouts.app')

@section('title', 'HMO | Family Member Data Update' )

@push('after-styles')

@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card arik-card">
                        <div class="card-header">Employee Family Member Data Update</div>

                        <div class="card-body">
                            <form action="{{ route('frontend.hmo.staff_member.familyMember', ['ara_number' => $staff_member->staff_ara_id, 'family_member' => $family_member->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="staff_member_id" value="{{ $staff_member->id }}">
                                <input type="hidden" name="user_id" value="{{ $staff_member->user->id }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="surname">Surname</label>
                                            <input id="surname" name="surname" value="{{ $family_member->surname }}" type="text" class="form-control" required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="first_name">First Name</label>
                                            <input id="first_name" name="first_name" value="{{ $family_member->first_name }}" type="text" class="form-control" required="required">
                                        </div>
                                        <div class="form-group">
                                            <label for="other_name">Other name</label>
                                            <input id="other_name" name="other_name" value="{{ $family_member->other_name }}" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input name="gender" id="gender_0" type="radio" class="custom-control-input" value="male" required="required" @if($family_member->gender == 'male') checked @endif>
                                            <label for="gender_0" class="custom-control-label">Male</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input name="gender" id="gender_1" type="radio" class="custom-control-input" value="female" required="required" @if($family_member->gender == 'female') checked @endif>
                                            <label for="gender_1" class="custom-control-label">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <input id="dob" name="dob" type="date" value="{{ $family_member->dob }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="relationship">Relationship</label>
                                    <div>
                                        <select id="relationship" name="relationship" class="custom-select" required="required">
                                            <option selected value="{{ $family_member->relationship }}">{{ $family_member->relationship }}</option>
                                            <option value="spouse">spouse</option>
                                            <option value="child">child</option>
                                            <option value="ward">ward</option>
                                            <option value="parent">parent</option>
                                            <option value="brother">brother</option>
                                            <option value="sister">sister</option>
                                            <option value="other">other</option>
                                        </select>
                                    </div>
                                </div>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button class="btn bg-navy btn-lg btn-block" type="submit">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
