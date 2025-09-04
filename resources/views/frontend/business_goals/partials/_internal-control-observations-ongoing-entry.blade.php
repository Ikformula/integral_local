

@isset($data_points)
    @php
            $observation_ongoing_field_values = $data_points->where('business_area_id', $business_area->id)
    ->where('name', 'Observations Ongoing JSON')
    ->first();
    @endphp

    @if(json_decode($observation_ongoing_field_values))
        @php $counter = 0; @endphp
        @foreach(json_decode($observation_ongoing_field_values->data_value) as $field_value)
            @php
                $counter++;
                $field_value = get_object_vars($field_value);
                $this_observation_ongoing_department = array_key_first($field_value);
            @endphp
            @if($this_observation_ongoing_department)
        <div class="row" id="observation-ongoing-department-{{ $counter }}">
            <div class="col-9">
                <div class="form-group">
                    <select class="form-control select2" name="observation_ongoings[]" required>
                        <option selected value="{{ $this_observation_ongoing_department }}">{{ $this_observation_ongoing_department }}</option>
                        @foreach($departments as $department)
                            <option value="{{ $department }}">{{ $department }}</option>
                        @endforeach
                    </select>
                    <label>
                        Department
                    </label>
                </div>
            </div>
            <div class="col-2">
                <input type="number" step="1" min="0" class="form-control" name="observation_ongoing_amounts[]"
                       value="{{ $field_value[array_key_first($field_value)] }}" required>
                <label>Number</label>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger remove-observation-ongoing-department-btn" id="remove-observation-ongoing-department-btn-{{ $counter }}"><i class="fa fa-times"></i></button>
            </div>
        </div>
        @endif
        @endforeach
    @endif

    @else

<div class="row" id="observation-ongoing-department-1">
    <div class="col-9">
        <div class="form-group">
            <select class="form-control select2" name="observation_ongoings[]" required>
                <option selected disabled>Select One</option>
                @foreach($departments as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
            </select>
            <label>
                Department
            </label>
        </div>
    </div>
    <div class="col-2">
        <input type="number" step="1" min="0" class="form-control" name="observation_ongoing_amounts[]" required>
        <label>Number</label>
    </div>
    <div class="col-1">
        <button type="button" class="btn btn-danger remove-observation-ongoing-department-btn" id="remove-observation-ongoing-department-btn-1"><i class="fa fa-times"></i></button>
    </div>
</div>
@endisset

<div class="form-group my-2">
    <button type="button" class="btn bg-maroon btn-block" id="add-observation-ongoing-department-btn">Add New Department</button>
</div>


