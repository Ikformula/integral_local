

@isset($data_points)
    @php
        $observation_completed_field_values = $data_points->where('business_area_id', $business_area->id)
->where('name', 'Observations Completed JSON')
->first();
    @endphp

    @if(json_decode($observation_completed_field_values))
        @php $counter = 0; @endphp
        @foreach(json_decode($observation_completed_field_values->data_value) as $field_value)
            @php
                $counter++;
                $field_value = get_object_vars($field_value);
                $this_observation_completed_department = array_key_first($field_value);
            @endphp
            @if($this_observation_completed_department)
        <div class="row" id="observation-completed-department-{{ $counter }}">
            <div class="col-9">
                <div class="form-group">
                    <select class="form-control select2" name="observation_completeds[]" required>
                        <option selected value="{{ $this_observation_completed_department }}">{{ $this_observation_completed_department }}</option>
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
                <input type="number" step="1" min="0" class="form-control" name="observation_completed_amounts[]"
                       value="{{ $field_value[array_key_first($field_value)] }}" required>
                <label>Number</label>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger remove-observation-completed-department-btn" id="remove-observation-completed-department-btn-{{ $counter }}"><i class="fa fa-times"></i></button>
            </div>
        </div>
        @endif
        @endforeach
    @endif

    @else

<div class="row" id="observation-completed-department-1">
    <div class="col-9">
        <div class="form-group">
            <select class="form-control select2" name="`observation_completeds[]" required>
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
        <input type="number" step="1" min="0" class="form-control" name="observation_completed_amounts[]" required>
        <label>Number</label>
    </div>
    <div class="col-1">
        <button type="button" class="btn btn-danger remove-observation-completed-department-btn" id="remove-observation-completed-department-btn-1"><i class="fa fa-times"></i></button>
    </div>
</div>
@endisset

<div class="form-group my-2">
    <button type="button" class="btn bg-maroon btn-block" id="add-observation-completed-department-btn">Add New Department</button>
</div>

