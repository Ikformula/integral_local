@extends('frontend.layouts.app')

@section('title', $pdf_file->name )

@section('content')

    <section class="content">
        <div class="container-fluid">
        <div class="row">
            @can('manage pilot elibrary')
        <div class="col-md-7">
            @else
                <div class="col-md-12">
            @endcan
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@yield('title') | {{ $pdf_file->year }} @include('frontend.includes._pdf-category-badge', ['pdf_file' => $pdf_file]) - Uploaded {{ $pdf_file->created_at->diffForHumans() }}</h5>
                </div>
                <div class="card-body">
                    <object data="{{ asset('fq_pdfs/'.$pdf_file->filename) }}" type="application/pdf" width="100%" height="700"> <a href="{{ asset('fq_pdfs/'.$pdf_file->filename) }}">{{ $pdf_file->filename }}</a></object>
                </div>
                @canany(['view Q400 PDFs', 'view 737 PDFs'])
                <div class="card-footer">

                    @if(!$read_count)
                    <form method="POST" action="{{ route('frontend.pilotLibrary.mark.as.read') }}" onsubmit="return confirm('Are you sure you want to mark this as read?');">
                        @csrf
                        <input type="hidden" name="pdf_file_id" value="{{ $pdf_file->id }}">
                        <input type="hidden" name="opened_at" value="{{ \Carbon\Carbon::now() }}">
                        <button type="submit" class="btn btn-warning">Mark as Read</button>
                    </form>
                    @else
                        <span class="text-muted">You read this document {{ $read_count->created_at->diffForHumans() }} on {{ $read_count->created_at }}</span>
                    @endif
                </div>

                @endcanany
            </div>
        </div>
                @can('manage pilot elibrary')
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Read Logs</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Crew Member</th>
                                        <th>ARA ID</th>
                                        <th>First Opened At</th>
                                        <th>Marked Read at</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pdf_file->readLogs as $log)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $log->staff()->name ?? '' }}</td>
                                            <td>{{ $log->staff_ara_id ?? '' }}</td>
                                            <td>{{ $log->opened_at->toDayDateTimeString() }}</td>
                                            <td>{{ $log->read_at->toDayDateTimeString() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
    </div>
        </div>
        </div>
    </section>

@endsection
