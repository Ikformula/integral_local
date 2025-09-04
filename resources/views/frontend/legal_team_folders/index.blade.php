@extends('frontend.layouts.app')

@push('after-styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@endpush

@section('title', 'Manage Folder')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Folder List</h3>

            <div class="card-tools">
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreate-legal_team_folders"><i class="fa fa-plus"></i> Add New Folder</button>
{{--            <form action="{{ route('frontend.legal_team_folders.fileManagerLink') }}" method="post">--}}
{{--                @csrf--}}
{{--                <button type="submit" class="btn btn-sm btn-outline-dark">View Folder Files</button>--}}
{{--            </form>--}}
            </div>
        </div>
        <div class="card-body table-responsive">
          <table id="tbl" class="table table-bordered table-sm text-nowrap w-100">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Parent Folder</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($legal_team_folders as $key => $legal_team_foldersItem)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td><a href="{{ route('frontend.legal_team_folders.show', $legal_team_foldersItem->id) }}">{{ $legal_team_foldersItem->name }}</a></td>
                  <td>{{ $legal_team_foldersItem->parent_idRelation ? $legal_team_foldersItem->parent_idRelation->name : '' }}</td>
                  <td>
                    @include('frontend.legal_team_folders._partials._folder_action_buttons', ['legal_team_foldersItem' => $legal_team_foldersItem])
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  @include('frontend.legal_team_folders._partials._add_new_folder')
</div>
@endsection

@push('after-scripts')
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
  <script>
      $(document).ready(function () {
          var table = new DataTable('.table', {
              "paging": false,
              scrollY: 465,
          });
      });
  </script>
@endpush
