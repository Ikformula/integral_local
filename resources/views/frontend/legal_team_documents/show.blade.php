<!-- resources/views/frontend/legal_team_documents/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Legal Document Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Document Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>Title:</strong> {{ $item->title }}</p>
<p><strong>Description:</strong> {!! $item->description !!}</p>
<p><strong>Remarks:</strong> {!! $item->remarks !!}</p>
<p><strong>User:</strong> {{ $item->user_idRelation ? $item->user_idRelation->full_name : '' }}</p>
<p><strong>File:</strong> <a href="{{ asset('storage/' . $item->file_name) }}" target="_blank">View File</a>
</p>
{{--<p><strong>Folder:</strong> {{ $item->folder_idRelation ? $item->folder_idRelation->name : '' }}</p>--}}

                    <a href="{{ route('frontend.legal_team_documents.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
