@extends('frontend.layouts.app')

@section('title', 'Flight Crew Documents Read Logs' )

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
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm" id="flight-crew-documents">
                                    <thead>
                                    <tr>
                                        <td>S/N</td>
                                        <td>Crew Member Name</td>
                                        <td>Year</td>
                                        <td>Fleet</td>
                                        <td>Category</td>
                                        <td>Read By</td>
                                        <td>Actions</td>
                                    </tr>

                                    <tbody>
                                    @foreach($pdf_files as $pdf_file)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pdf_file->name }}</td>
                                            <td>{{ $pdf_file->year }}</td>
                                            <td>{{ $pdf_file->fleet }}</td>
                                            <td>{{ $pdf_file->category }}</td>
                                            <td>0</td>
                                            <td>
                                                <a href="{{ route('frontend.pilotLibrary.show', $pdf_file) }}" class="btn btn-sm btn-success">View</a>
                                                <a href="" class="btn btn-sm btn-info">Edit</a>
                                                <form action="" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="pdf_id" value="{{ $pdf_file->id}}">
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
