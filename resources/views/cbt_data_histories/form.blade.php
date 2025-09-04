
<div class="mb-3 row">
    <label for="model_type" class="col-form-label text-lg-end col-lg-2 col-xl-3">Model Type</label>
    <div class="col-lg-10 col-xl-9">
        <input class="form-control{{ $errors->has('model_type') ? ' is-invalid' : '' }}" name="model_type" type="text" id="model_type" value="{{ old('model_type', optional($cbtDataHistory)->model_type) }}" minlength="1" maxlength="255" required="true" placeholder="Enter model type here...">
        {!! $errors->first('model_type', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="model_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">Model</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('model_id') ? ' is-invalid' : '' }}" id="model_id" name="model_id" required="true">
        	    <option value="" style="display: none;" {{ old('model_id', optional($cbtDataHistory)->model_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select model</option>
        	@foreach ($models as $key => $model)
			    <option value="{{ $key }}" {{ old('model_id', optional($cbtDataHistory)->model_id) == $key ? 'selected' : '' }}>
			    	{{ $model }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('model_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="previous_value" class="col-form-label text-lg-end col-lg-2 col-xl-3">Previous Value</label>
    <div class="col-lg-10 col-xl-9">
        <textarea class="form-control{{ $errors->has('previous_value') ? ' is-invalid' : '' }}" name="previous_value" id="previous_value" required="true" placeholder="Enter previous value here...">{{ old('previous_value', optional($cbtDataHistory)->previous_value) }}</textarea>
        {!! $errors->first('previous_value', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="changed_by_user_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">Changed By User</label>
    <div class="col-lg-10 col-xl-9">
        <select class="form-select{{ $errors->has('changed_by_user_id') ? ' is-invalid' : '' }}" id="changed_by_user_id" name="changed_by_user_id" required="true">
        	    <option value="" style="display: none;" {{ old('changed_by_user_id', optional($cbtDataHistory)->changed_by_user_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select changed by user</option>
        	@foreach ($changedByUsers as $key => $changedByUser)
			    <option value="{{ $key }}" {{ old('changed_by_user_id', optional($cbtDataHistory)->changed_by_user_id) == $key ? 'selected' : '' }}>
			    	{{ $changedByUser }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('changed_by_user_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

