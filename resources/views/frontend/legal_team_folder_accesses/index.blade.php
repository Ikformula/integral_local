@extends('frontend.layouts.app')

@push('after-styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@endpush

@section('title', 'Manage Folder Access')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">Folder Access List</h3>
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreate-legal_team_folder_accesses">Add New Folder Access</button>
        </div>
        <div class="card-body">
          <table id="legal_team_folder_accesses-tbl" class="table table-bordered text-nowrap w-100">
            <thead>
              <tr>
                <th>#</th>
                <th>User Id</th>
                <th>Folder</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($legal_team_folder_accesses as $key => $legal_team_folder_accessesItem)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $legal_team_folder_accessesItem->user_idRelation ? $legal_team_folder_accessesItem->user_idRelation->full_name : '' }}</td>
                  <td>{{ $legal_team_folder_accessesItem->folder_idRelation ? $legal_team_folder_accessesItem->folder_idRelation->name : '' }}</td>
                  <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit-legal_team_folder_accesses-{{ $legal_team_folder_accessesItem->id }}">Edit</button>
                    <form action="{{ route('legal_team_folder_accesses.destroy', $legal_team_folder_accessesItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                <div class="modal fade" id="modalEdit-legal_team_folder_accesses-{{ $legal_team_folder_accessesItem->id }}" tabindex="-1">
                  <div class="modal-dialog modal-lg"><div class="modal-content">
                    <form action="{{ route('frontend.legal_team_folder_accesses.update', $legal_team_folder_accessesItem->id) }}" method="POST">
                      @csrf @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Folder Access</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group"><label>User Id</label>
                          <select class="form-control" name="user_id">
                            <option value="">-- Select --</option>
                            @foreach(Auth\User::all() as $opt)
                              <option value="{{ $opt->id }}" {{ $opt->id==$legal_team_folder_accessesItem->user_id?'selected':'' }}>{{ $opt->full_name }}</option>
                            @endforeach
                          </select></div>
                        <div class="form-group"><label>Folder</label>
                          <select class="form-control" name="folder_id">
                            <option value="">-- Select --</option>
                            @foreach(LegalTeamFolder::all() as $opt)
                              <option value="{{ $opt->id }}" {{ $opt->id==$legal_team_folder_accessesItem->folder_id?'selected':'' }}>{{ $opt->name }}</option>
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
  <div class="modal fade" id="modalCreate-legal_team_folder_accesses" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
      <form action="{{ route('frontend.legal_team_folder_accesses.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Folder Access</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label>User Id</label>
            <select class="form-control" name="user_id">
              <option value="">-- Select --</option>
              @foreach(Auth\User::all() as $opt)
                <option value="{{ $opt->id }}">{{ $opt->full_name }}</option>
              @endforeach
            </select></div>
          <div class="form-group"><label>Folder</label>
            <select class="form-control" name="folder_id">
              <option value="">-- Select --</option>
              @foreach(LegalTeamFolder::all() as $opt)
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
