<div class="row mb-2">
    <div class="col">
        <form action="" method="GET">
            <div class="row">
                <div class="col-10">
                    <div class="form-group mb-0">
                        <select class="form-control" name="week_range_id">
                            @php $week_id = isset($week_range_id) ? $week_range_id : null;
                            @endphp
                            <option value="{{ $week_in_focus->id }}">Wk {{ $week_in_focus->week_number }}: {{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }}</option>
                            @include('frontend.business_goals.partials._week_range_options', ['selected_week_id' => $week_id])
                        </select>
                        <label>Week Selection</label>
                    </div>
                </div>
                <div class="col-2">
                    <button type="submit" class="btn bg-navy btn-block">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>
