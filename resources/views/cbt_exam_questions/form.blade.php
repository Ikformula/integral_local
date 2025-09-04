
<div class="mb-3 row">
    <label for="cbt_exam_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">CBT Exam</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('cbt_exam_id') ? ' is-invalid' : '' }}" id="cbt_exam_id" name="cbt_exam_id" required="true">
        	    <option value="" style="display: none;" {{ old('cbt_exam_id', optional($cbtExamQuestion)->cbt_exam_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select cbt exam</option>
        	@foreach ($CbtExams as $key => $CbtExam)
			    <option value="{{ $key }}" {{ old('cbt_exam_id', optional($cbtExamQuestion)->cbt_exam_id) == $key ? 'selected' : '' }}>
			    	{{ $CbtExam }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('cbt_exam_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="cbt_question_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">CBT Question</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('cbt_question_id') ? ' is-invalid' : '' }}" id="cbt_question_id" name="cbt_question_id" required="true">
        	    <option value="" style="display: none;" {{ old('cbt_question_id', optional($cbtExamQuestion)->cbt_question_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select cbt question</option>
        	@foreach ($CbtQuestions as $key => $CbtQuestion)
			    <option value="{{ $key }}" {{ old('cbt_question_id', optional($cbtExamQuestion)->cbt_question_id) == $key ? 'selected' : '' }}>
			    	{{ $CbtQuestion }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('cbt_question_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

