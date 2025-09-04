@extends('frontend.layouts.app')

@push('after-styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
  <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet"
        href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('title', 'Manage ECS User')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">ECS User List</h3>
        </div>
          <div class="card-footer">
              <form action="" method="POST">
                  @csrf
                      <strong>Add User</strong>
              <div class="row">
                      <div class="col">
                          <select class="form-control select2" name="user_id" required>
                              <option value="">-- Select User --</option>
                              @foreach($users as $user)
                                  @if($user->staff_member)
                                  <option value="{{ $user->id }}">{{ $user->full_name }} {{ isset($user->staff_member) ? '(ARA'.$user->staff_member->staff_ara_id.')' : '' }}</option>
                                  @endif
                              @endforeach
                          </select>
                          <label>User</label>
                      </div>
                      <div class="col">
                          <select name="role_id" class="form-control" required>
                              <option value="">-- Select role --</option>
                              @foreach($roles as $id => $role)
                                  <option value="{{ $id }}">{{ $role }}</option>
                              @endforeach
                          </select>
                          <label>Role</label>
                      </div>
                      <div class="col-2">
                          <button type="submit" class="btn bg-maroon btn-block">Add</button>
                      </div>
              </div>
              </form>
          </div>
        <div class="card-body">
          <table id="ecs_portal_users-tbl" class="table dt-table table-bordered text-nowrap w-100">
            <thead>
              <tr>
                <th>#</th>
                <th>User</th>
                <th>Staff ARA ID</th>
                <th>Role</th>
                <th>Added By</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ecs_portal_users as $key => $ecs_portal_usersItem)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $ecs_portal_usersItem->user_idRelation ? $ecs_portal_usersItem->user_idRelation->full_name : '' }}</td>
                  <td>{{ $ecs_portal_usersItem->staff_ara_id }}</td>
                  <td>{{ ucfirst($ecs_portal_usersItem->role) }}</td>
                  <td>{{ $ecs_portal_usersItem->added_byRelation ? $ecs_portal_usersItem->added_byRelation->full_name : '' }}</td>
                  <td>
                    <form action="{{ route('frontend.ecs_portal_users.destroy', $ecs_portal_usersItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('after-scripts')
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
  <script>
    $(document).ready(function() {
      var table = new DataTable('.dt-table', {
       'paging': false,        scrollY: 465,       });
    });
  </script>

        <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
        <script>
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        </script>
@endpush
