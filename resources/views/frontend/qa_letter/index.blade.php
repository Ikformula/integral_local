@extends('frontend.layouts.app')

@push('after-styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@endpush

@section('title', 'Manage QA Letters')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">QA Letters List</h3>
          <div class="card-tools">
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreate-qa_letter">Add New QA Letters</button>
          </div>
        </div>
        <div class="card-body">
          <table id="qa_letter-tbl" class="table table-bordered text-nowrap w-100">
            <thead>
              <tr>
                <th>#</th>
                <th>Organization</th>
                <th>External Reference</th>
                <th>Internal Reference</th>
                <th>Department</th>
                <th>Description</th>
                <th>Administrator ARA ID</th>
                <th>File</th>
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
                  <td>{{ $qa_letterItem->external_reference }}</td>
                  <td>{{ $qa_letterItem->internal_reference }}</td>
                  <td>{{ $qa_letterItem->department }}</td>
                  <td>{!! $qa_letterItem->description !!}</td>
                  <td>{{ $qa_letterItem->administrator_ara_id }}</td>
                  <td>{{ $qa_letterItem->file_path }}</td>
                  <td>{{ $qa_letterItem->category_idRelation ? $qa_letterItem->category_idRelation->name : '' }}</td>
                  <td>{{ $qa_letterItem->for_date }}</td>
                  <td>{{ $qa_letterItem->status }}</td>
                  <td>{{ $qa_letterItem->status_last_changed }}</td>
                  <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit-qa_letter-{{ $qa_letterItem->id }}">Edit</button>
                    <form action="{{ route('frontend.qa_letter.destroy', $qa_letterItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                <div class="modal fade" id="modalEdit-qa_letter-{{ $qa_letterItem->id }}" tabindex="-1">
                  <div class="modal-dialog modal-lg"><div class="modal-content">
                    <form action="{{ route('frontend.qa_letter.update', $qa_letterItem->id) }}" method="POST">
                      @csrf @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit QA Letters</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group"><label>Organization</label>
                          <input type="text" class="form-control" name="Organization" value="{{ $qa_letterItem->Organization }}"></div>
                        <div class="form-group"><label>External Reference</label>
                          <input type="text" class="form-control" name="external_reference" value="{{ $qa_letterItem->external_reference }}"></div>
                        <div class="form-group"><label>Internal Reference</label>
                          <input type="text" class="form-control" name="internal_reference" value="{{ $qa_letterItem->internal_reference }}"></div>
                        <div class="form-group"><label>Department</label>
                          <select class="form-control" name="department">
                            <option value="">-- Select --</option>
                            <option value="Option1" {{ $qa_letterItem->department=='Option1'?'selected':'' }}>Option1</option>
                          </select></div>
                        <div class="form-group"><label>Description</label>
                          <textarea class="form-control" name="description" rows="4">{{ $qa_letterItem->description }}</textarea></div>
                        <div class="form-group"><label>Administrator ARA ID</label>
                          <input type="text" class="form-control" name="administrator_ara_id" value="{{ $qa_letterItem->administrator_ara_id }}"></div>
                        <div class="form-group"><label>File</label>
                          <input type="text" class="form-control" name="file_path" value="{{ $qa_letterItem->file_path }}"></div>
                        <div class="form-group"><label>Category</label>
                          <select class="form-control" name="category_id">
                            <option value="">-- Select --</option>
                            @foreach(QaCategory::all() as $opt)
                              <option value="{{ $opt->id }}" {{ $opt->id==$qa_letterItem->category_id?'selected':'' }}>{{ $opt->name }}</option>
                            @endforeach
                          </select></div>
                        <div class="form-group"><label>For Date</label>
                          <input type="date" class="form-control" name="for_date" value="{{ $qa_letterItem->for_date }}"></div>
                        <div class="form-group"><label>Status</label>
                          <select class="form-control" name="status">
                            <option value="">-- Select --</option>
                            <option value="Option1" {{ $qa_letterItem->status=='Option1'?'selected':'' }}>Option1</option>
                          </select></div>
                        <div class="form-group"><label>Status Last Changed</label>
                          <input type="datetime-local" class="form-control" name="status_last_changed" value="{{ $qa_letterItem->status_last_changed }}"></div>
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
  <div class="modal fade" id="modalCreate-qa_letter" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
      <form action="{{ route('frontend.qa_letter.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New QA Letters</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label>Organization</label>
            <input type="text" class="form-control" name="Organization"></div>
          <div class="form-group"><label>External Reference</label>
            <input type="text" class="form-control" name="external_reference"></div>
          <div class="form-group"><label>Internal Reference</label>
            <input type="text" class="form-control" name="internal_reference"></div>
          <div class="form-group"><label>Department</label>
            <select class="form-control" name="department">
              <option value="">-- Select --</option>
              <option>Option1</option>
              <option>Option2</option>
            </select></div>
          <div class="form-group"><label>Description</label>
            <textarea class="form-control" name="description" rows="4"></textarea></div>
          <div class="form-group"><label>Administrator ARA ID</label>
            <input type="text" class="form-control" name="administrator_ara_id"></div>
          <div class="form-group"><label>File</label>
            <input type="text" class="form-control" name="file_path"></div>
          <div class="form-group"><label>Category</label>
            <select class="form-control" name="category_id">
              <option value="">-- Select --</option>
              @foreach(QaCategory::all() as $opt)
                <option value="{{ $opt->id }}">{{ $opt->name }}</option>
              @endforeach
            </select></div>
          <div class="form-group"><label>For Date</label>
            <input type="date" class="form-control" name="for_date"></div>
          <div class="form-group"><label>Status</label>
            <select class="form-control" name="status">
              <option value="">-- Select --</option>
              <option>Option1</option>
              <option>Option2</option>
            </select></div>
          <div class="form-group"><label>Status Last Changed</label>
            <input type="datetime-local" class="form-control" name="status_last_changed"></div>
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
