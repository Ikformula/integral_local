<!-- resources/views/frontend/legal_team_folders/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', $folder->name.' Folder')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span class="text-muted"><strong>FOLDER:</strong> </span>{{ $folder->parentPath() }}<strong>{{ $folder->name }}</strong>
                <div class="card-tools">


                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-dark" data-toggle="modal"
                            data-target="#uploadLegalDocModalId">
                       <i class="fa fa-upload"></i> Upload Document
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="uploadLegalDocModalId" tabindex="-1" role="dialog" aria-labelledby="uploadLegalDocModalTitleId"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="uploadLegalDocModalTitleId"></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('frontend.legal_team_folders.fileUpload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="folder_id" value="{{ $folder->id }}">

                                        <div class="form-group">
                                            <label for="title">Title/Name of file <span class="text-danger">*</span> </label>
                                            <input type="text" name="title" id="title" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description" id="description" rows="5"></textarea>
                                            <small class="form-text text-muted">Kindly explain in detail</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control" name="remarks" id="remarks" rows="5"></textarea>
                                        </div>

                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="file_uploaded" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" required>
                                                <label class="custom-file-label" for="inputGroupFile04">Add file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-dark" id="inputGroupFileAddon04">Upload</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-body p-0">

                    <table id="legal_team_folders-tbl" class="table table-hover text-nowrap w-100">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Size</th>
{{--                            <th>Actions</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if($folder->parent_idRelation)
                            <tr>
                                <td>-</td>
                                <td><a href="{{ route('frontend.legal_team_folders.show', $folder->parent_id) }}"><i class="fa fa-folder-closed text-warning"></i> .. </a></td>
                                <td></td>
{{--                                <td></td>--}}
                            </tr>
                        @endif
                        @php $count = 1; @endphp
                    @foreach($folder->childrenFolders as $childFolder)
                        <tr>
                            <td>{{ $count }}</td>
                            <td><a href="{{ route('frontend.legal_team_folders.show', $childFolder->id) }}"><i class="fa fa-folder-closed"></i> {{ $childFolder->name }}</a></td>
                            <td>{{ $childFolder->childrenFolders->count() + $childFolder->documents->count()  }}</td>
{{--                            <td></td>--}}
                        </tr>
                        @php $count++; @endphp
                    @endforeach
                    @foreach($folder->documents as $document)
                        <tr>
                            <td>{{ $count }}</td>
                            <td><a href="{{ $document->url() }}"><i class="fa fa-file-alt text-primary"></i> {{ $document->title }}</a></td>
                            <td>{{ $document->file_size }}</td>
{{--                            <td></td>--}}
                        </tr>
                        @php $count++; @endphp
                    @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            @if($logged_in_user->can('manage legal team'))
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Folder Access List</h3>
                    <div class="card-tools">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreate-legal_team_folder_accesses">Grant Access to this Folder</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="legal_team_folder_accesses-tbl" class="table table-bordered text-nowrap w-100">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Lawyer</th>
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
                                    <form action="{{ route('frontend.legal_team_folder_accesses.destroy', $legal_team_folder_accessesItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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

            <div class="modal fade" id="modalCreate-legal_team_folder_accesses" tabindex="-1">
                <div class="modal-dialog modal-lg"><div class="modal-content">
                        <form action="{{ route('frontend.legal_team_folder_accesses.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Grant Upload Access to {{ $folder->name }}</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group"><label>Lawyer</label>
                                    <select class="form-control" name="user_id">
                                        <option value="">-- Select --</option>
                                        @foreach($external_lawyers_users as $opt)
                                            <option value="{{ $opt->user->id }}">{{ $opt->user->full_name }}</option>
                                        @endforeach
                                    </select></div>
                                <div class="form-group"><label>Folder</label>
                                    <select class="form-control" name="folder_id">
                                        <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                    </select></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div></div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
