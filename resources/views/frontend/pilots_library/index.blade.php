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
                                        <td>Read At</td>
                                        <td>Actions</td>
                                    </tr>
                                    <tbody>
                                    @php($shown = [])
                                    @foreach($fleet_categories as $category)
                                        @php($pdf_files = $category->pdfFiles())
                                        @if(is_object($pdf_files))
                                            @foreach($pdf_files as $pdf_file)
                                                @if(!in_array($pdf_file->id, $shown))
                                                    @php($shown[] = $pdf_file->id)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $pdf_file->name }}</td>
                                                        <td>{{ $pdf_file->year }}</td>
                                                        <td>
                                                            @include('frontend.includes._pdf-category-badge', ['pdf_file' => $pdf_file])
                                                        </td>

                                                        <td>
                                                            @php($staff_read_pdf[$staff_ara_id][$pdf_file->id] = staff_read_pdf($staff_ara_id, $pdf_file->id))
                                                            @if($staff_read_pdf[$staff_ara_id][$pdf_file->id])
                                                                {{ $staff_read_pdf[$staff_ara_id][$pdf_file->id]->created_at->toDayDateTimeString() }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <a href="{{ route('frontend.pilotLibrary.show', $pdf_file) }}"
                                                               class="btn btn-success btn-sm">View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
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
            "buttons": ["colvis"]
        }).buttons().container().appendTo('#flight-crew-documents_wrapper .col-md-6:eq(0)');
    </script>
@endpush
