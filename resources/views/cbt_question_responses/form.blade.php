
<div class="mb-3 row">
    <label for="cbt_exam_question_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">CBT Exam Question</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('cbt_exam_question_id') ? ' is-invalid' : '' }}" id="cbt_exam_question_id" name="cbt_exam_question_id" required="true">
        	    <option value="" style="display: none;" {{ old('cbt_exam_question_id', optional($cbtQuestionResponse)->cbt_exam_question_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select cbt exam question</option>
        	@foreach ($CbtExamQuestions as $key => $CbtExamQuestion)
			    <option value="{{ $key }}" {{ old('cbt_exam_question_id', optional($cbtQuestionResponse)->cbt_exam_question_id) == $key ? 'selected' : '' }}>
			    	{{ $CbtExamQuestion }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('cbt_exam_question_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="cbt_option_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">CBT Option</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('cbt_option_id') ? ' is-invalid' : '' }}" id="cbt_option_id" name="cbt_option_id" required="true">
        	    <option value="" style="display: none;" {{ old('cbt_option_id', optional($cbtQuestionResponse)->cbt_option_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select cbt option</option>
        	@foreach ($CbtOptions as $key => $CbtOption)
			    <option value="{{ $key }}" {{ old('cbt_option_id', optional($cbtQuestionResponse)->cbt_option_id) == $key ? 'selected' : '' }}>
			    	{{ $CbtOption }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('cbt_option_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="cbt_exam_candidate_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">CBT Exam Candidate</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('cbt_exam_candidate_id') ? ' is-invalid' : '' }}" id="cbt_exam_candidate_id" name="cbt_exam_candidate_id" required="true">
        	    <option value="" style="display: none;" {{ old('cbt_exam_candidate_id', optional($cbtQuestionResponse)->cbt_exam_candidate_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select cbt exam candidate</option>
        	@foreach ($CbtExamCandidates as $key => $CbtExamCandidate)
			    <option value="{{ $key }}" {{ old('cbt_exam_candidate_id', optional($cbtQuestionResponse)->cbt_exam_candidate_id) == $key ? 'selected' : '' }}>
			    	{{ $CbtExamCandidate }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('cbt_exam_candidate_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="is_history" class="col-form-label text-lg-end col-lg-2 col-xl-3">Is History</label>
    <div class="col-lg-10 col-xl-9">
        <div class="form-check checkbox">
            <input id="is_history_1" class="form-check-input" name="is_history" type="checkbox" value="1" {{ old('is_history', optional($cbtQuestionResponse)->is_history) == '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="is_history_1">
                Yes
            </label>
        </div>


        {!! $errors->first('is_history', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

