
<div class="mb-3 row">
    <label for="title" class="col-form-label text-lg-end col-lg-2 col-xl-3">Title</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" type="text" id="title" value="{{ old('title', optional($cbtExam)->title) }}" minlength="1" maxlength="255" required="true" placeholder="Enter title here...">
        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="start_at" class="col-form-label text-lg-end col-lg-2 col-xl-3">Start At</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('start_at') ? ' is-invalid' : '' }}" name="start_at" type="datetime-local" id="start_at" value="{{ old('start_at', optional($cbtExam)->start_at) }}" required="true" placeholder="Enter start at here...">
        {!! $errors->first('start_at', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="duration_in_minutes" class="col-form-label text-lg-end col-lg-2 col-xl-3">Duration In Minutes</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('duration_in_minutes') ? ' is-invalid' : '' }}" name="duration_in_minutes" type="number" id="duration_in_minutes" value="{{ old('duration_in_minutes', optional($cbtExam)->duration_in_minutes) }}" min="5" max="4294967295" step="3" required="true" placeholder="Enter duration in minutes here...">
        {!! $errors->first('duration_in_minutes', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

@php $creator_user_id = isset($cbtExam) && isset($cbtExam->creator_user_id) ? $cbtExam->creator_user_id : $logged_in_user->id; @endphp
<input type="hidden" value="{{ $creator_user_id }}" name="creator_user_id">
{{--<div class="mb-3 row">--}}
{{--    <label for="creator_user_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">Creator User</label>--}}
{{--    <div class="col-lg-10 col-xl-9">--}}
{{--        <select class="form-select{{ $errors->has('creator_user_id') ? ' is-invalid' : '' }}" id="creator_user_id" name="creator_user_id" required="true">--}}
{{--        	    <option value="" style="display: none;" {{ old('creator_user_id', optional($cbtExam)->creator_user_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select creator user</option>--}}
{{--        	@foreach ($creatorUsers as $key => $creatorUser)--}}
{{--			    <option value="{{ $key }}" {{ old('creator_user_id', optional($cbtExam)->creator_user_id) == $key ? 'selected' : '' }}>--}}
{{--			    	{{ $creatorUser }}--}}
{{--			    </option>--}}
{{--			@endforeach--}}
{{--        </select>--}}
{{--        --}}
{{--        {!! $errors->first('creator_user_id', '<div class="invalid-feedback">:message</div>') !!}--}}
{{--    </div>--}}
{{--</div>--}}

