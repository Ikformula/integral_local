<!-- resources/views/frontend/legal_team_external_lawyers/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'External Lawyer Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">External Lawyer Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>First Name:</strong> {{ $item->user->first_name }}</p>
<p><strong>Last Name:</strong> {{ $item->user->last_name }}</p>
<p><strong>Email:</strong> {{ $item->user->email }}</p>
<p><strong>Firm:</strong> {{ $item->firm }}</p>
<p><strong>Notes:</strong> {!! $item->notes !!}</p>

                    <a href="{{ route('frontend.legal_team_cases.index', ['l_id' => $item->id]) }}" class="btn btn-primary">Cases</a>
                    <a href="{{ route('frontend.legal_team_external_lawyers.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

{{--    <div class="row">--}}
{{--        <div class="col-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">--}}
{{--                    <h3 class="card-title">Document List</h3>--}}
{{--                </div>--}}
{{--                <div class="card-body p-0">--}}
{{--                    <table class="table table-bordered w-100">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>#</th>--}}
{{--                            <th>Title</th>--}}
{{--                            <th>Description</th>--}}
{{--                            <th>Remarks</th>--}}
{{--                            <th>Uploader</th>--}}
{{--                            <th>File</th>--}}
{{--                            <th>Updated At</th>--}}
{{--                            --}}{{--                                <th>Folder</th>--}}
{{--                            <th>Actions</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($documents as $key => $document)--}}
{{--                            <tr>--}}
{{--                                <td>{{ $key + 1 }}</td>--}}
{{--                                <td>{{ $document->title }}</td>--}}
{{--                                <td>{!! $document->description !!}</td>--}}
{{--                                <td>{!! $document->remarks !!}</td>--}}
{{--                                <td>{{ $document->user_idRelation->full_name }}</td>--}}
{{--                                <td><a href="{{ asset('storage/' . $document->file_name) }}" target="_blank">View File</a></td>--}}
{{--                                <td>{{ $document->updated_at->toDateString() }}</td>--}}

{{--                                --}}{{--<td>{{ $document->folder_id }}</td>--}}

{{--                                <td class="text-nowrap">--}}
{{--                                    <a href="{{ route('frontend.legal_team_documents.show', $document->id) }}" class="btn btn-sm btn-info">View</a>--}}
{{--                                    <a href="{{ route('frontend.legal_team_documents.edit', $document->id) }}" class="btn btn-sm btn-primary">Edit</a>--}}
{{--                                    <form action="{{ route('frontend.legal_team_documents.destroy', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">--}}
{{--                                        @csrf--}}
{{--                                        @method('DELETE')--}}
{{--                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>--}}
{{--                                    </form>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
@endsection
