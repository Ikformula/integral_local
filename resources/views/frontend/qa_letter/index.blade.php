@extends('frontend.layouts.app')

@push('after-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('title', 'Manage QA Letters')

@section('content')
<div class="container-fluid">

  <div class="row mb-3">
{{--    <div class="col-6">--}}
{{--      <form method="get" class="form-inline-flex-wrap mb-2">--}}
{{--        <div class="row">--}}
{{--          <div class="col">--}}
{{--            <div class="form-group">--}}
{{--              <input type="date" name="created_from" value="{{ $created_from }}" class="form-control">--}}
{{--              <label>Created From</label>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <div class="col">--}}
{{--            <div class="form-group">--}}
{{--              <input type="date" name="created_to" value="{{ $created_to }}" class="form-control">--}}
{{--              <label>Created To</label>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <div class="col">--}}
{{--            <div class="form-group">--}}
{{--              <button type="submit" class="btn btn-primary">Filter</button>--}}
{{--              <a href="{{ route('frontend.qa_letter.index') }}" class="btn bg-maroon">Reset</a>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </form>--}}
{{--    </div>--}}
      <div class="col-6">
      <form method="get" class="form-inline-flex-wrap mb-2">
        <div class="row">
          <div class="col">
            <div class="form-group">
              <input type="date" name="for_date_from" value="{{ $for_date_from }}" class="form-control">
              <label>Date From</label>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <input type="date" name="for_date_to" value="{{ $for_date_to }}" class="form-control">
              <label>To Date</label>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="{{ route('frontend.qa_letter.index') }}" class="btn bg-maroon">Reset</a>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>

    <div class="row mb-2">
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Total Letters', 'icon' => 'envelope', 'colour' => 'primary'])
                {{ $total_count }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'In Letters', 'icon' => 'arrow-down', 'colour' => 'secondary'])
                {{ $in_count }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Out Letters', 'icon' => 'arrow-up', 'colour' => 'warning'])
                {{ $out_count }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Pending', 'icon' => 'clock', 'colour' => 'primary'])
                {{ $pending_count }}
            @endcomponent
        </div>
        <div class="col-md-2">
            @component('frontend.components.dashboard_stat_widget-small-box', ['title' => 'Attended', 'icon' => 'check', 'colour' => 'secondary'])
                {{ $attended_count }}
            @endcomponent
        </div>
    </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">QA Letters List</h3>
          <div class="card-tools">
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreate-qa_letter">Add New Letter</button>
          </div>
        </div>
        <div class="card-body">
          <table id="qa_letter-tbl" class="table table-bordered text-nowrap w-100">
            <thead>
              <tr>
                <th>#</th>
                <th>Organization</th>
                  <th>File</th>
                <th>External Reference</th>
                <th>Internal Reference</th>
                <th>Department</th>
                <th>Description</th>
                <th>Administrator ARA ID</th>
                <th>Direction</th>
                <th>Category</th>
                <th>For Date</th>
                <th>Status</th>
                <th>Status Last Changed</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($qa_letter as $key => $qa_letterItem)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $qa_letterItem->Organization }}</td>
                  <td>
                      @if($qa_letterItem->file_path)
                          <a href="{{ $files_base_url.'/storage/app/public/' . $qa_letterItem->file_path }}" target="_blank" class="btn btn-primary btn-xs">View File</a>
                      @else
                          No file
                      @endif
                  </td>
                <td>{{ $qa_letterItem->external_reference }}</td>
                <td>{{ $qa_letterItem->internal_reference }}</td>
                <td>{{ $qa_letterItem->department }}</td>
                <td>{!! $qa_letterItem->description !!}</td>
                <td>{{ $qa_letterItem->administrator_ara_id }}</td>
                <td style="background-color: {{ $qa_letterItem->direction == 'in' ? '#e6f7ff' : ($qa_letterItem->direction == 'out' ? '#fff7e6' : 'transparent') }};">
                  {{ $qa_letterItem->direction ?? '' }}
                </td>
                <td>{{ $qa_letterItem->category_idRelation ? $qa_letterItem->category_idRelation->name : '' }}</td>
                <td>{{ $qa_letterItem->for_date }}</td>
                <td>{{ $qa_letterItem->status }}</td>
                <td>{{ $qa_letterItem->status_last_changed_at ? $qa_letterItem->status_last_changed_at->toDayDateTimeString() : '' }}</td>
                <td>
                  <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit-qa_letter-{{ $qa_letterItem->id }}">Edit</button>
                  <form action="{{ route('frontend.qa_letter.destroy', $qa_letterItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
              <div class="modal fade" id="modalEdit-qa_letter-{{ $qa_letterItem->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <form action="{{ route('frontend.qa_letter.update', $qa_letterItem->id) }}" method="POST" enctype="multipart/form-data">
                      @csrf @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit QA Letters</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group"><label>Organization</label>
                          <input type="text" class="form-control" name="Organization" value="{{ $qa_letterItem->Organization }}">
                        </div>
                        <div class="form-group"><label>External Reference</label>
                          <input type="text" class="form-control" name="external_reference" value="{{ $qa_letterItem->external_reference }}">
                        </div>
                        <div class="form-group"><label>Internal Reference</label>
                          <input type="text" class="form-control" name="internal_reference" value="{{ $qa_letterItem->internal_reference }}">
                        </div>
                        <div class="form-group"><label>Department</label>
                          <select class="form-control" name="department">
                            <option value="">-- Select --</option>
                            <option value="{{ $qa_letterItem->department }}" selected>{{ $qa_letterItem->department }}</option>
                            @include('includes.partials._departments-option-list')
                          </select>
                        </div>
                        <div class="form-group"><label>Description</label>
                          <textarea class="form-control" name="description" rows="4">{{ $qa_letterItem->description }}</textarea>
                        </div>
                        <div class="form-group"><label>Administrator ARA ID</label>
                          <input type="text" class="form-control" name="administrator_ara_id" value="{{ $qa_letterItem->administrator_ara_id }}">
                        </div>
                        <div class="form-group"><label>File</label>
                          <input type="file" class="form-control" name="file_path">
                          @if($qa_letterItem->file_path)
                          <small>Current: <a href="{{ $files_base_url.'/storage/app/public/' . $qa_letterItem->file_path }}" target="_blank">View File</a></small>
                          @endif
                        </div>
                        <div class="form-group"><label>Direction</label><br>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="direction" value="in" {{ $qa_letterItem->direction == 'in' ? 'checked' : '' }}> <label class="form-check-label">In</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="direction" value="out" {{ $qa_letterItem->direction == 'out' ? 'checked' : '' }}> <label class="form-check-label">Out</label>
                          </div>
                        </div>
                        <div class="form-group"><label>Category</label>
                          <select class="form-control" name="category_id">
                            <option value="">-- Select --</option>
                            @foreach($qa_categories as $opt)
                            <option value="{{ $opt->id }}" {{ $opt->id==$qa_letterItem->category_id?'selected':'' }}>{{ $opt->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group"><label>For Date</label>
                          <input type="date" class="form-control" name="for_date" value="{{ $qa_letterItem->for_date }}">
                        </div>
                        <div class="form-group"><label>Status</label>
                          <select class="form-control" name="status" required>
                            <option value="">-- Select --</option>
                            <option value="pending" {{ $qa_letterItem->status=='pending'?'selected':'' }}>Pending</option>
                            <option value="attended" {{ $qa_letterItem->status=='attended'?'selected':'' }}>Attended</option>
                          </select>
                        </div>
                        <input type="hidden" name="updater_user_id" value="{{ $logged_in_user->id }}">
                        <input type="hidden" name="status_last_changed_at" value="{{ now() }}">

                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modalCreate-qa_letter" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ route('frontend.qa_letter.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Add New QA Letters</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group"><label>Organization</label>
              <input type="text" class="form-control" name="Organization">
            </div>
            <div class="form-group"><label>External Reference</label>
              <input type="text" class="form-control" name="external_reference">
            </div>
            <div class="form-group"><label>Internal Reference</label>
              <input type="text" class="form-control" name="internal_reference">
            </div>
            <div class="form-group"><label>Department</label>
              <select class="form-control" name="department">
                <option value="">-- Select --</option>
                @include('includes.partials._departments-option-list')
              </select>
            </div>
            <div class="form-group"><label>Description</label>
              <textarea class="form-control" name="description" rows="4"></textarea>
            </div>
            <div class="form-group"><label>Administrator ARA ID</label>
              <input type="text" class="form-control" name="administrator_ara_id" value="{{ $logged_in_user->staff_member ? $logged_in_user->staff_member->staff_ara_id : '' }}">
            </div>
            <div class="form-group"><label>File</label>
              <input type="file" class="form-control" name="file_path">
            </div>
            <div class="form-group"><label>Direction</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="direction" value="in"> <label class="form-check-label">In</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="direction" value="out"> <label class="form-check-label">Out</label>
              </div>
            </div>
            <div class="form-group"><label>Category</label>
              <select class="form-control" name="category_id">
                <option value="">-- Select --</option>
                @foreach($qa_categories as $opt)
                <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group"><label>For Date</label>
              <input type="date" class="form-control" name="for_date">
            </div>
            <div class="form-group"><label>Status</label>
              <select class="form-control" name="status" required>
                <option value="">-- Select --</option>
                <option value="pending">Pending</option>
                <option value="attended">Attended</option>
              </select>
            </div>
            <input type="hidden" name="updater_user_id" value="{{ $logged_in_user->id }}">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('after-scripts')
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
<script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
<script>
  $(document).ready(function() {
    var table = new DataTable('.table', {
      'paging': false,
      scrollY: 465,
      layout: {
        top: {
          searchBuilder: {}
        },
        topStart: {
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        }
      }
    });
  });
</script>
@endpush
