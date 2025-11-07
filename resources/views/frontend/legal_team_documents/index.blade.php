<!-- resources/views/frontend/legal_team_documents/index.blade.php -->
@extends('frontend.layouts.app')

@push('after-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('title', ucfirst($process_type).' Processes - '.$lawyer->firm.' | Arik Legal')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex gap-2">
            <a href="{{ route('frontend.legal_team_documents.create', ['pr' => $process_type, 'case_id' => $case_id]) }}" class="btn btn-primary">Add New Document</a>
            <form action="{{ route('frontend.legal_team_documents.download_zip', ['pr' => $process_type, 'case_id' => $case_id]) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success ml-2 @if(!$items || !$items->count()) disabled @endif"><i class="fa fa-file-zipper"></i> Download All as ZIP</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ucfirst($process_type).' Processes - '.$lawyer->firm }}</h3>
                    <div class="card-tools">
                        @if($process_type == 'defendant')
                        <a href="{{ route('frontend.legal_team_documents.index', ['pr' => 'claimant', 'case_id' => $case_id]) }}" class="btn btn-sm bg-navy"><i class="fa fa-folder-closed"></i> Claimants' Processes</a>
                        @else
                        <a href="{{ route('frontend.legal_team_documents.index', ['pr' => 'defendant', 'case_id' => $case_id]) }}" class="btn btn-sm bg-maroon">Defendant Processes</a>
                        @endif


                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Remarks</th>
                                <th>Uploader</th>
                                <th>File</th>
                                <th>Updated At</th>
                                {{-- <th>Folder</th>--}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{!! $item->description !!}</td>
                                <td>{!! $item->remarks !!}</td>
                                <td>{{ $item->user_idRelation->full_name }}</td>
                                <td><a href="{{ asset('storage/' . $item->file_name) }}" target="_blank">View File</a></td>
                                <td>{{ $item->updated_at->toDateString() }}</td>

                                {{--<td>{{ $item->folder_id }}</td>--}}

                                <td class="text-nowrap">
                                    <a href="{{ route('frontend.legal_team_documents.show', $item->id) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('frontend.legal_team_documents.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('frontend.legal_team_documents.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
<script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function() {
        var table = new DataTable('.table', {
            "paging": false,
            scrollY: 465,
            layout: {
                top: {
                    searchBuilder: {}
                },
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            }
        });
    });
</script>
@endpush
