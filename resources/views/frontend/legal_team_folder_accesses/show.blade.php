<!-- resources/views/frontend/legal_team_folder_accesses/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'LegalTeamFolderAccess Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">LegalTeamFolderAccess Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>User Id:</strong> {{ $item->user_idRelation ? $item->user_idRelation->full_name : '' }}</p>
<p><strong>Folder:</strong> {{ $item->folder_idRelation ? $item->folder_idRelation->name : '' }}</p>

                    <a href="{{ route('frontend.legal_team_folder_accesses.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
