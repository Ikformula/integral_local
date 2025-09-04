@isset($data_points)
    @php
        $delay_field_values = $data_points->where('business_area_id', $business_area->id)
->where('name', 'Delays JSON')
->where('week_range_id', $week_range_id)
->first();
    @endphp

    @if(json_decode($delay_field_values))
        @php $counter = 0; @endphp
        @foreach(json_decode($delay_field_values->data_value) as $field_value)
            @php
                $counter++;
                $field_value = get_object_vars($field_value);
                $this_delay_code = $delay_codes->where('delay_codes', array_key_first($field_value))->first();
            @endphp
            @if($this_delay_code)
        <div class="row" id="delay-code-{{ $counter }}">
            <div class="col-9">
                <div class="form-group">
                    <select class="form-control select2" name="delay_codes[]" required>
                        <option selected value="{{ $this_delay_code->delay_codes }}">{{ $this_delay_code->delay_codes }}, {{ $this_delay_code->delay_reason }} - {{ $this_delay_code->delay_definition }}</option>
                        @foreach($delay_codes as $delay_code)
                            <option value="{{ $delay_code->delay_codes }}">{{ $delay_code->delay_codes }}, {{ $delay_code->delay_reason }} - {{ $delay_code->delay_definition }}</option>
                        @endforeach
                    </select>
                    <label>
                        Delay code
                    </label>
                </div>
            </div>
            <div class="col-2">
                <input type="number" step="1" min="0" class="form-control" name="delay_amounts[]"
                       value="{{ $field_value[array_key_first($field_value)] }}" required>
                <label>Number of times</label>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger remove-delay-code-btn" id="remove-delay-code-btn-{{ $counter }}"><i class="fa fa-times"></i></button>
            </div>
        </div>
        @endif
        @endforeach
    @endif

    @else

<div class="row" id="delay-code-1">
    <div class="col-9">
        <div class="form-group">
            <select class="form-control select2" name="delay_codes[]" required>
                <option selected disabled>Select One</option>
                @foreach($delay_codes as $delay_code)
                    <option value="{{ $delay_code->delay_codes }}">{{ $delay_code->delay_codes }}, {{ $delay_code->delay_reason }} - {{ $delay_code->delay_definition }}</option>
                @endforeach
            </select>
            <label>
                Delay code
            </label>
        </div>
    </div>
    <div class="col-2">
        <input type="number" step="1" min="0" class="form-control" name="delay_amounts[]" required>
        <label>Number of times</label>
    </div>
    <div class="col-1">
        <button type="button" class="btn btn-danger remove-delay-code-btn" id="remove-delay-code-btn-1"><i class="fa fa-times"></i></button>
    </div>
</div>
@endisset

<div class="form-group my-2">
    <button type="button" class="btn bg-maroon btn-block" id="add-delay-code-btn">Add New Delay Code</button>
</div>


