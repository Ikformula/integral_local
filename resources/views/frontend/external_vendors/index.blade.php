@extends('frontend.layouts.app')

@push('after-styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@endpush

@section('title', 'Manage Vendors')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Vendors List</h3>
          <div class="card-tools">
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreate-external_vendors">Add New Vendors</button>
          </div>
        </div>
        <div class="card-body">
          <table id="external_vendors-tbl" class="table table-bordered text-nowrap w-100">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($external_vendors as $key => $external_vendorsItem)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $external_vendorsItem->name }}</td>
                  <td>{!! $external_vendorsItem->description !!}</td>
                  <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit-external_vendors-{{ $external_vendorsItem->id }}">Edit</button>
                    <form action="{{ route('external_vendors.destroy', $external_vendorsItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                <div class="modal fade" id="modalEdit-external_vendors-{{ $external_vendorsItem->id }}" tabindex="-1">
                  <div class="modal-dialog modal-lg"><div class="modal-content">
                    <form action="{{ route('frontend.external_vendors.update', $external_vendorsItem->id) }}" method="POST">
                      @csrf @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Vendors</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group"><label>Name</label>
                          <input type="text" class="form-control" name="name" value="{{ $external_vendorsItem->name }}"></div>
                        <div class="form-group"><label>Description</label>
                          <textarea class="form-control" name="description" rows="4">{{ $external_vendorsItem->description }}</textarea></div>
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
  <div class="modal fade" id="modalCreate-external_vendors" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
      <form action="{{ route('frontend.external_vendors.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Vendors</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label>Name</label>
            <input type="text" class="form-control" name="name"></div>
          <div class="form-group"><label>Description</label>
            <textarea class="form-control" name="description" rows="4"></textarea></div>
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
