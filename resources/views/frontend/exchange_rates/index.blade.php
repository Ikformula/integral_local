@extends('frontend.layouts.app')

@push('after-styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@endpush

@section('title', 'Manage Exchange Rates')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Exchange Rates List</h3>
          <div class="card-tools">
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreate-exchange_rates">Add New Exchange Rate</button>
          </div>
        </div>
        <div class="card-body">
          <table id="exchange_rates-tbl" class="table table-bordered text-nowrap w-100">
            <thead>
              <tr>
                <th>#</th>
                <th>GBP</th>
                <th>USD</th>
                <th>Euro</th>
                <th>Month</th>
{{--                <th>Month Number</th>--}}
                <th>Year</th>
                <th>Entered By User</th>
{{--                <th>From Date</th>--}}
{{--                <th>To Date</th>--}}
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($exchange_rates as $key => $exchange_ratesItem)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ checkIntNumber($exchange_ratesItem->gbp) }}</td>
                  <td>{{ checkIntNumber($exchange_ratesItem->usd) }}</td>
                  <td>{{ checkIntNumber($exchange_ratesItem->eur) }}</td>
                  <td>{{ $exchange_ratesItem->month_name }}</td>
{{--                  <td>{{ checkIntNumber($exchange_ratesItem->month_number) }}</td>--}}
                  <td>{{ checkIntNumber($exchange_ratesItem->year) }}</td>
                  <td>{{ $exchange_ratesItem->entered_by_user_idRelation ? $exchange_ratesItem->entered_by_user_idRelation->full_name : '' }}</td>
{{--                  <td>{{ $exchange_ratesItem->from_date }}</td>--}}
{{--                  <td>{{ $exchange_ratesItem->to_date }}</td>--}}
                  <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit-exchange_rates-{{ $exchange_ratesItem->id }}">Edit</button>
                    <form action="{{ route('frontend.exchange_rates.destroy', $exchange_ratesItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                <div class="modal fade" id="modalEdit-exchange_rates-{{ $exchange_ratesItem->id }}" tabindex="-1">
                  <div class="modal-dialog modal-lg"><div class="modal-content">
                    <form action="{{ route('frontend.exchange_rates.update', $exchange_ratesItem->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to make this change?')">
                      @csrf @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Exchange Rates</h5>
                          <small class="text-muted">This rates are how much Naira makes the currency in question</small>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group"><label>USD</label>
                          <input type="number" step="0.01" class="form-control" name="usd" value="{{ $exchange_ratesItem->usd }}"></div>
                        <div class="form-group"><label>Euro</label>
                          <input type="number" step="0.01" class="form-control" name="eur" value="{{ $exchange_ratesItem->eur }}"></div>
                          <div class="form-group"><label>GBP</label>
                              <input type="number" step="0.01" class="form-control" name="gbp" value="{{ $exchange_ratesItem->gbp }}"></div>

                          <div class="form-group"><label>Month</label>
                              <select name="month_number" class="form-control" required>
                                  @foreach($months as $key => $month)
                                      <option value="{{ $key }}" {{ $month == $exchange_ratesItem->month_name ? 'selected' : '' }}>{{ $month }}</option>
                                  @endforeach
                              </select>
                          </div>
                        <div class="form-group"><label>Year</label>
                          <input type="number" class="form-control" name="year" value="{{ $exchange_ratesItem->year }}"></div>


                          <input type="hidden" name="entered_by_user_id" value="{{ $logged_in_user->id }}">
{{--                        <div class="form-group"><label>From Date</label>--}}
{{--                          <input type="datetime-local" class="form-control" name="from_date" value="{{ $exchange_ratesItem->from_date }}"></div>--}}
{{--                        <div class="form-group"><label>To Date</label>--}}
{{--                          <input type="date" class="form-control" name="to_date" value="{{ $exchange_ratesItem->to_date }}"></div>--}}
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                      </div>
                    </form>
                  </div></div>
                </div>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modalCreate-exchange_rates" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
      <form action="{{ route('frontend.exchange_rates.store') }}" method="POST" onsubmit="return confirm('Are you sure you want to set this rate?')">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Exchange Rates</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label>GBP</label>
            <input type="number" step="0.01" class="form-control" name="gbp"></div>
          <div class="form-group"><label>USD</label>
            <input type="number" step="0.01" class="form-control" name="usd"></div>
          <div class="form-group"><label>Euro</label>
            <input type="number" step="0.01" class="form-control" name="eur"></div>
          <div class="form-group"><label>Month</label>
              <select name="month_number" class="form-control" required>
                  @foreach($months as $key => $month)
                      <option value="{{ $key }}" {{ $month == $now->monthName ? 'selected' : '' }}>{{ $month }}</option>
                  @endforeach
              </select>
          </div>

          <div class="form-group"><label>Year</label>
            <input type="number" class="form-control" name="year" value="{{ $now->year }}"></div>
            <input type="hidden" name="entered_by_user_id" value="{{ $logged_in_user->id }}">

{{--            <div class="form-group"><label>From Date</label>--}}
{{--            <input type="datetime-local" class="form-control" name="from_date"></div>--}}
{{--          <div class="form-group"><label>To Date</label>--}}
{{--            <input type="date" class="form-control" name="to_date"></div>--}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </form>
    </div></div>
  </div>
</div>
@endsection

@push('after-scripts')
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
  <script>
    $(document).ready(function() {
      var table = new DataTable('.table', {
       'paging': false,        scrollY: 465,       });
    });
  </script>
@endpush
