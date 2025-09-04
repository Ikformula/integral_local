
<div class="mb-3 row">
    <label for="cbt_question_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">CBT Question</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('cbt_question_id') ? ' is-invalid' : '' }}" id="cbt_question_id" name="cbt_question_id" required="true">
        	    <option value="" style="display: none;" {{ old('cbt_question_id', optional($cbtOption)->cbt_question_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select cbt question</option>
        	@foreach ($CbtQuestions as $key => $CbtQuestion)
			    <option value="{{ $key }}" {{ old('cbt_question_id', optional($cbtOption)->cbt_question_id) == $key ? 'selected' : '' }}>
			    	{{ $CbtQuestion }}
			    </option>
			@endforeach
        </select>

        {!! $errors->first('cbt_question_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="body" class="col-form-label text-lg-end col-lg-2 col-xl-3">Body</label>
    <div class="col-lg-10 col-xl-9">
        <textarea class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" name="body" id="body" required="true" placeholder="Enter body here...">{{ old('body', optional($cbtOption)->body) }}</textarea>
        {!! $errors->first('body', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="is_correct" class="col-form-label text-lg-end col-lg-2 col-xl-3">Is Correct</label>
    <div class="col-lg-10 col-xl-9">
        <div class="form-check checkbox">
            <input id="is_correct_1" class="form-check-input" name="is_correct" type="checkbox" value="1" {{ old('is_correct', optional($cbtOption)->is_correct) == '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="is_correct_1">
                Yes
            </label>
        </div>


        {!! $errors->first('is_correct', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

