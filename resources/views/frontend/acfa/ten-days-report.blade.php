@extends('frontend.layouts.app')

@push('after-styles')
    {{--    @include('includes.partials._datatables-css')--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('title', 'ACFA Data Export')

@section('content')

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                  <form method="GET" class="form-inline">
                      <div class="input-group input-group-sm">
                          <input type="date" value="{{ isset($_GET['checked_at']) ? substr($_GET['checked_at'], 0, 10) : now()->toDateString() }}" class="form-control">
                          <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">Set Observation Date</button>
                  </span>
                      </div>
                  </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
<div class="table-responsive">
    <table class="table table-bordered table-sm table-striped table-striped-columns">
       <thead>
       <tr>
           <td>ObservationDate</td>
           <td>ObservationTime</td>
           <td>Oneway</td>
           <td>Carrier</td>
           <td>OutboundFlightNumber</td>
           <td>OutboundDepartureDate</td>
           <td>OutboundDepartureTime</td>
           <td>Class</td>
           <td>FareExclTax</td>
           <td>FareInclTax</td>
           <td>TaxAndSurcharge</td>
           <td>Currency</td>
       </tr>
       </thead>

        <tbody>
        @forelse($fares as $fare)
            <tr>
                <td>{{ $fare->checked_at->toDateString() }}</td>
                <td>{{ $fare->checked_at->totimeString() }}</td>
                <td>{{ $fare->depart_from_port }}{{ $fare->arrive_at_port }}</td>
                <td>{{ $fare->airline->airline_code }}</td>
                <td>{{ substr($fare->flight_number, 3) }}</td>
                <td>{{ $fare->departure_date->toDateString() }}</td>
                <td>{{ $fare->departure_time }}</td>
                <td>{{ $fare->class_name }}</td>
                <td>{{ $fare->amount }}</td>
                <td>{{ $fare->amount }}</td>
                <td>0</td>
                <td>{{ $fare->currency }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="12">No results found for this date</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    <script src="{{ asset('js/html-table-xlsx.js') }}"></script>

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
        $(document).ready(function () {
            var table = new DataTable('.table', {
                "paging": true,
                pageLength: 50,
                layout: {
                    top: {
                        searchBuilder: {
                            // columns: [6],
                            @if(isset($_GET['days_left']))
                            preDefined: {
                                {{--criteria: [--}}
                                {{--    {--}}
                                {{--        data: 'Days Left to End',--}}
                                {{--        condition: '=',--}}
                                {{--        value: [{{ $_GET['days_left'] }}]--}}
                                {{--    }--}}
                                {{--]--}}
                            }
                            @endif
                        }
                    },
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });
    </script>
@endpush
