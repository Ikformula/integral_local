{{--@php--}}
{{--    $staff_members = \App\Models\StaffMember::select(['staff_ara_id', 'surname', 'email', 'other_names', 'department_name', 'employment_category'])->get();--}}
{{--@endphp--}}
<div class="mb-3 row">
    <label for="staff_ara_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">Staff</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-control select2 form-select{{ $errors->has('staff_ara_id') ? ' is-invalid' : '' }}" id="staff_ara_id" name="staff_ara_id">
            <option value="" style="display: none;" {{ old('staff_ara_id', optional($cbtExamCandidate)->staff_ara_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select if candidate is currently a staff member</option>
            @foreach ($StaffMembers as $key => $staff_member)
                <option value="{{ $staff_member->staff_ara_id }}" {{ old('staff_ara_id', optional($cbtExamCandidate)->staff_ara_id) == $key ? 'selected' : '' }}>
                    ARA{{ $staff_member->staff_ara_id }}, {{ $staff_member->surname }} {{ $staff_member->other_names }}, {{ $staff_member->department_name }}, {{ $staff_member->employment_category }},
                </option>
            @endforeach
        </select>

        {!! $errors->first('staff_ara_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="email" class="col-form-label text-lg-end col-lg-2 col-xl-3">Email</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" type="text" id="email" value="{{ old('email', optional($cbtExamCandidate)->email) }}" minlength="1" maxlength="255" required="true" placeholder="Enter email here...">
        {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="cbt_exam_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">CBT Exam</label>
    <div class="col-lg-10 col-xl-9">
        <select class=" select2 form-select{{ $errors->has('cbt_exam_id') ? ' is-invalid' : '' }}" id="cbt_exam_id" name="cbt_exam_id" required="true">
        	    <option value="" style="display: none;" {{ old('cbt_exam_id', optional($cbtExamCandidate)->cbt_exam_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select cbt exam</option>
        	@foreach ($CbtExams as $key => $CbtExam)
			    <option value="{{ $key }}" {{ old('cbt_exam_id', optional($cbtExamCandidate)->cbt_exam_id) == $key ? 'selected' : '' }}>
			    	{{ $CbtExam }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('cbt_exam_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="surname" class="col-form-label text-lg-end col-lg-2 col-xl-3">Surname</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" type="text" id="surname" value="{{ old('surname', optional($cbtExamCandidate)->surname) }}" minlength="1" maxlength="255" required="true" placeholder="Enter surname here...">
        {!! $errors->first('surname', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="first_name" class="col-form-label text-lg-end col-lg-2 col-xl-3">First Name</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" type="text" id="first_name" value="{{ old('first_name', optional($cbtExamCandidate)->first_name) }}" minlength="1" maxlength="255"  placeholder="Enter first name here...">
        {!! $errors->first('first_name', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="other_names" class="col-form-label text-lg-end col-lg-2 col-xl-3">Other Names</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('other_names') ? ' is-invalid' : '' }}" name="other_names" type="text" id="other_names" value="{{ old('other_names', optional($cbtExamCandidate)->other_names) }}" maxlength="255" placeholder="Enter other names here...">
        {!! $errors->first('other_names', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="age" class="col-form-label text-lg-end col-lg-2 col-xl-3">Age</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('age') ? ' is-invalid' : '' }}" name="age" type="number" id="age" value="{{ old('age', optional($cbtExamCandidate)->age) }}" min="12" max="70" placeholder="Enter age here...">
        {!! $errors->first('age', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="gender" class="col-form-label text-lg-end col-lg-2 col-xl-3">Gender</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-control form-select{{ $errors->has('gender') ? ' is-invalid' : '' }}" id="gender" name="gender" required="true">
        	    <option value="" style="display: none;" {{ old('gender', optional($cbtExamCandidate)->gender ?: '') == '' ? 'selected' : '' }} disabled selected>Select one...</option>
        	@foreach (['male' => 'Male',
'female' => 'Female'] as $key => $text)
			    <option value="{{ $key }}" {{ old('gender', optional($cbtExamCandidate)->gender) == $key ? 'selected' : '' }}>
			    	{{ $text }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('gender', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="state" class="col-form-label text-lg-end col-lg-2 col-xl-3">State</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" type="text" id="state" value="{{ old('state', optional($cbtExamCandidate)->state) }}" minlength="1" maxlength="255" required="true" placeholder="Enter state here...">
        {!! $errors->first('state', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="address" class="col-form-label text-lg-end col-lg-2 col-xl-3">Address</label>
    <div class="col-lg-10 col-xl-9">
        <textarea class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" id="address" required="true" placeholder="Enter address here...">{{ old('address', optional($cbtExamCandidate)->address) }}</textarea>
        {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="phone_number" class="col-form-label text-lg-end col-lg-2 col-xl-3">Phone Number</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" type="text" id="phone_number" value="{{ old('phone_number', optional($cbtExamCandidate)->phone_number) }}" min="1" max="20" required="true" placeholder="Enter phone number here...">
        {!! $errors->first('phone_number', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

