
<div class="mb-3 row">
    <label for="question" class="col-form-label text-lg-end col-lg-2 col-xl-3">Question</label>
    <div class="col-lg-10 col-xl-9">
        <textarea class="form-control{{ $errors->has('question') ? ' is-invalid' : '' }}" name="question" id="question" required="true" placeholder="Enter question here...">{{ old('question', optional($cbtQuestion)->question) }}</textarea>
        {!! $errors->first('question', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="cbt_subject_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">CBT Subject</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('cbt_subject_id') ? ' is-invalid' : '' }}" id="cbt_subject_id" name="cbt_subject_id" required="true">
        	    <option value="" style="display: none;" {{ old('cbt_subject_id', optional($cbtQuestion)->cbt_subject_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select cbt subject</option>
        	@foreach ($CbtSubjects as $CbtSubject => $key)
			    <option value="{{ $key }}" {{ old('cbt_subject_id', optional($cbtQuestion)->cbt_subject_id) == $key ? 'selected' : '' }}>
			    	{{ $CbtSubject }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('cbt_subject_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>



