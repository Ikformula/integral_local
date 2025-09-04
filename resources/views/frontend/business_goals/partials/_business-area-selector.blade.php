
<div class="card my-2">
    @if(count($accessible_business_areas) > 1)
  <div class="card-header">
      <h4 class="card-title">Select Business Area</h4>
  </div>
    @endif
  <div class="card-body">
      <form action="" method="GET">
          <div class="row">
              <div class="col-5">
                  <div class="form-group mb-0">
                      <select name="business_area_id" class="form-control">
                          @foreach($accessible_business_areas as $biz_area)
                              <option value="{{ $biz_area->id }}"
                                      @if(isset($_GET['business_area_id']) && $_GET['business_area_id'] == $biz_area->id) selected @endif>{{ $biz_area->name }}</option>
                          @endforeach
                      </select>
                      <label>Business Area</label>
                  </div>
              </div>

              @isset($weeks)
              <div class="col-5">
                  <div class="form-group mb-0">
                      <select class="form-control" name="week_range_id" id="week_range_id">
                          @php $week_id = isset($week_range_id) ? $week_range_id : null; @endphp
                          @include('frontend.business_goals.partials._week_range_options', ['selected_week_id' => $week_id])
                      </select>
                      <label>Week Number</label>
                  </div>
              </div>

{{--                  <div class="col-3">--}}
{{--                      <div class="form-group mb-3">--}}
{{--                          <input type="date" name="for_date" min="{{ $weeks->last()->to_date }}" max="{{ $weeks->first()->to_date }}" id="for_date" @if(isset($_GET['for_date'])) value="{{ $_GET['for_date'] }}" @endif class="form-control" placeholder=""--}}
{{--                                 aria-describedby="for_date_helpId">--}}
{{--                          <span class="text-muted"></span>--}}
{{--                          <label for="">For Date</label>--}}
{{--                      </div>--}}
{{--                  </div>--}}
              @endisset

              <div class="col-2">
                  <button type="submit" class="btn bg-navy btn-block">Filter</button>
              </div>
          </div>
      </form>
  </div>
</div>
