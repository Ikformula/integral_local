@foreach($weeks as $week)
    <option {{ isset($selected_week_id) && $selected_week_id == $week->id ? 'selected' : ''}} value="{{ $week->id }}">Wk {{ $week->week_number }}: {{ $week->from_day }} - {{ $week->to_day }}</option>
@endforeach
