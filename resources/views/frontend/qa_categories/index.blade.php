@extends('frontend.layouts.app')

@push('after-styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@endpush

@section('title', 'Manage Category')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Category List</h3>
          <div class="card-tools">
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreate-qa_categories">Add New Category</button>
          </div>
        </div>
        <div class="card-body">
          <table id="qa_categories-tbl" class="table table-bordered text-nowrap w-100">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Parent Category</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($qa_categories as $key => $qa_categoriesItem)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $qa_categoriesItem->name }}</td>
                  <td>{{ $qa_categoriesItem->parent_idRelation ? $qa_categoriesItem->parent_idRelation->name : '' }}</td>
                  <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit-qa_categories-{{ $qa_categoriesItem->id }}">Edit</button>
                    <form action="{{ route('frontend.qa_categories.destroy', $qa_categoriesItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                <div class="modal fade" id="modalEdit-qa_categories-{{ $qa_categoriesItem->id }}" tabindex="-1">
                  <div class="modal-dialog modal-lg"><div class="modal-content">
                    <form action="{{ route('frontend.qa_categories.update', $qa_categoriesItem->id) }}" method="POST">
                      @csrf @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group"><label>Name</label>
                          <input type="text" class="form-control" name="name" value="{{ $qa_categoriesItem->name }}"></div>
                        <div class="form-group"><label>Parent Category</label>
                          <select class="form-control" name="parent_id">
                            <option value="">-- Select --</option>
                            @foreach($qa_categories as $opt)
                              <option value="{{ $opt->id }}" {{ $opt->id==$qa_categoriesItem->parent_id?'selected':'' }}>{{ $opt->name }}</option>
                            @endforeach
                          </select></div>
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
  <div class="modal fade" id="modalCreate-qa_categories" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
      <form action="{{ route('frontend.qa_categories.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Category</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label>Name</label>
            <input type="text" class="form-control" name="name"></div>
          <div class="form-group"><label>Parent Category</label>
            <select class="form-control" name="parent_id">
              <option value="">-- Select --</option>
              @foreach($qa_categories as $opt)
                <option value="{{ $opt->id }}">{{ $opt->name }}</option>
              @endforeach
            </select></div>
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
