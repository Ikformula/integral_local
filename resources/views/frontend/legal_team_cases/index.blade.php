@extends('frontend.layouts.app')

@push('after-styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
@endpush

@section('title', 'Manage Cases')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Cases {{ isset($firm) ? ' from '.$firm : '' }}</h3>
          <div class="card-tools">
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreate-legal_team_cases">Add New Case</button>
          </div>
        </div>
        <div class="card-body">
          <table id="legal_team_cases-tbl" class="table table-bordered text-nowrap w-100">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
{{--                <th>Creator</th>--}}
{{--                <th>External Lawyer</th>--}}
                  <th>Processes</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($legal_team_cases as $key => $legal_team_casesItem)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $legal_team_casesItem->title }}</td>
                  <td>{{ $legal_team_casesItem->description }}</td>
{{--                  <td>{{ $legal_team_casesItem->user_idRelation ? $legal_team_casesItem->user_idRelation->full_name : '' }}</td>--}}
{{--                  <td>{{ $legal_team_casesItem->lawyer ? $legal_team_casesItem->lawyer->user->full_name : '' }}</td>--}}
                    <td>
                        <a href="{{ route('frontend.legal_team_documents.index', ['pr' => 'claimant', 'case_id' => $legal_team_casesItem->id]) }}" class="btn btn-sm bg-navy"><i class="fa fa-folder-closed"></i>  Claimants</a>
                        <a href="{{ route('frontend.legal_team_documents.index', ['pr' => 'defendant', 'case_id' => $legal_team_casesItem->id]) }}" class="btn btn-sm bg-maroon"><i class="fa fa-folder-closed"></i>  Defendants</a>
                    </td>
                  <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit-legal_team_cases-{{ $legal_team_casesItem->id }}">Edit</button>
                    <form action="{{ route('frontend.legal_team_cases.destroy', $legal_team_casesItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                <div class="modal fade" id="modalEdit-legal_team_cases-{{ $legal_team_casesItem->id }}" tabindex="-1">
                  <div class="modal-dialog modal-lg"><div class="modal-content">
                    <form action="{{ route('frontend.legal_team_cases.update', $legal_team_casesItem->id) }}" method="POST" enctype="multipart/form-data">
                      @csrf @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Case</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group"><label>Title</label>
                          <input type="text" class="form-control" name="title" value="{{ $legal_team_casesItem->title }}"></div>
                        <div class="form-group"><label>Description</label>
                          <textarea class="form-control" name="description" rows="4">{{ $legal_team_casesItem->description }}</textarea></div>
{{--                        <div class="form-group"><label>Creator</label>--}}
{{--                          <select class="form-control" name="user_id">--}}
{{--                            <option value="">-- Select --</option>--}}
{{--                            @foreach(Auth\User::all() as $opt)--}}
{{--                              <option value="{{ $opt->id }}" {{ $opt->id==$legal_team_casesItem->user_id?'selected':'' }}>{{ $opt->full_name }}</option>--}}
{{--                            @endforeach--}}
{{--                          </select></div>--}}
{{--                        <div class="form-group"><label>External Lawyer</label>--}}
{{--                          <select class="form-control" name="external_lawyer_id">--}}
{{--                            <option value="">-- Select --</option>--}}
{{--                            @foreach(LegalTeamExternalLawyer::all() as $opt)--}}
{{--                              <option value="{{ $opt->id }}" {{ $opt->id==$legal_team_casesItem->external_lawyer_id?'selected':'' }}>{{ $opt->user->full_name }}</option>--}}
{{--                            @endforeach--}}
{{--                          </select></div>--}}
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
          <div class="card-footer">
              {{ $legal_team_cases->links() }}
          </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modalCreate-legal_team_cases" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
      <form action="{{ route('frontend.legal_team_cases.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Case</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label>Title</label>
            <input type="text" class="form-control" name="title"></div>
          <div class="form-group"><label>Description</label>
            <textarea class="form-control" name="description" rows="4"></textarea></div>

            <input type="hidden" name="user_id" value="{{ $logged_in_user->id }}">
            @if($lawyer)
            <input type="hidden" name="external_lawyer_id" value="{{ $lawyer->id }}">
            <input type="hidden" name="firm" value="{{ $firm }}">
            @endif
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
