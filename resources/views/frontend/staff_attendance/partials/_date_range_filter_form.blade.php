<form action="" method="GET">
{{--    @csrf--}}
    <input type="hidden" name="staff_ara_id" value="{{ $staff_ara_id ?? '' }}">
    <span>Showing attendance</span>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="from_date">From</label>
                <input type="date" name="from_date" id="from_date" value="{{ $from_date->toDateString() ?? '' }}" class="form-control">
            </div>
        </div>

        <div class="col">
            <div class="form-group">
                <label for="to_date">To</label>
                <input type="date" name="to_date" id="to_date" value="{{ $to_date->toDateString() ?? '' }}" class="form-control">
            </div>
        </div>

{{--        <div class="col-md-4">--}}
{{--            <div class="form-group">--}}
{{--                <div class="form-check">--}}
{{--                <input type="checkbox" name="filter_late" id="filter_late" value="{{ $_GET['filter_late'] ?? 1 }}" class="form-check-input" {{ isset($_GET['filter_late']) ? 'checked' : '' }}>--}}
{{--                    <label class="form-check-label" for="filter_late">Filter Latecoming</label>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="col-md-4">--}}
{{--            <div class="form-group">--}}
{{--                <label for="arrival_time">Arrived From</label>--}}
{{--                <input type="time" name="arrival_time" id="arrival_time" value="" class="form-control">--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="col-md-4">--}}
{{--            <div class="form-group">--}}
{{--                <label for="departure_time">Left Before</label>--}}
{{--                <input type="time" name="departure_time" id="departure_time" value="" class="form-control">--}}
{{--            </div>--}}
{{--        </div>--}}


    </div>

    @if($auth_perm)
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="staff_ara_id">Staff ARA ID</label>
                <input type="text" name="staff_ara_id" id="staff_ara_id" value="{{ $_GET['staff_ara_id'] ?? '' }}" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="department">Department</label>
                <input type="search" name="department" id="department" value="{{ $_GET['department'] ?? ''  }}" class="form-control" list="departments-list">
                <datalist id="departments-list">
                    @include('includes.partials._departments-option-list')
                </datalist>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="staff_name">Staff Name</label>
                <input type="text" name="staff_name" id="staff_name" value="{{ $_GET['staff_name'] ?? ''  }}" class="form-control">
            </div>
        </div>
    </div>
    @endif

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>

</form>
