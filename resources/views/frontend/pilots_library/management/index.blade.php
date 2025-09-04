@extends('frontend.layouts.app')

@section('title', 'Flight Crew Documents' )

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">@yield('title')</h5>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link bg-maroon" href="{{ route('frontend.pilotLibrary.create') }}"><i class="fa fa-plus"></i> Add New</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm" id="flight-crew-documents">
                                    <thead>
                                    <tr>
                                        <td>S/N</td>
                                        <td>Name</td>
                                        <td>Year</td>
                                        <td>Fleet & Category</td>
                                        <td>Read By</td>
                                        <td>Actions</td>
                                    </tr>

                                    <tbody>
                                    @php($shown = [])
                                    @foreach($fleet_categories as $category)
                                        @php($pdf_files = $category->pdfFiles())
                                    @foreach($pdf_files as $pdf_file)
                                        @if(!in_array($pdf_file->id, $shown))
                                            @php($shown[] = $pdf_file->id)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pdf_file->name }}</td>
                                            <td>{{ $pdf_file->year }}</td>
                                            <td>@include('frontend.includes._pdf-category-badge', ['pdf_file' => $pdf_file])</td>
                                            <td>{{ $pdf_file->readLogs->count() }}</td>
                                            <td>
                                                <a href="{{ route('frontend.pilotLibrary.show', $pdf_file) }}" class="btn btn-sm btn-success">View</a>
{{--                                                <a href="" class="btn btn-sm btn-info">Edit</a>--}}
                                                <form action="{{ route('frontend.pilotLibrary.delete', $pdf_file) }}" method="POST" style="display: inline-block;"  onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>

                                            @endif
                                    @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        $("#flight-crew-documents").DataTable({
            "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
            "buttons": ["csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#flight-crew-documents_wrapper .col-md-6:eq(0)');
    </script>
@endpush
