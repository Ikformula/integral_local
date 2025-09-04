<select class="form-control" name="{{ $field_name }}">
@foreach($sel_options[$option_list] as $option)
    <option @if(isset($value) && $value == $option) selected @endif value="{{ $option }}">{{ $option }}</option>
@endforeach
</select>
