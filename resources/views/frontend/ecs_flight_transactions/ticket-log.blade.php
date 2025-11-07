<!-- resources/views/frontend/ecs_bookings/index.blade.php -->
@extends('frontend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('title', 'Tickets Log for '. $client->name_and_balance)

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">@yield('title')</h3>
                    </div>
                    <div class="card-footer">
                        <form method="get">
                            <div class="row ">
                            @if($logged_in_user->isEcsClient)
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            @else
                                <div class="col-md-4">
                                    <div class="mb-0">
                                        <select class="form-control" id="client_id" name="client_id" required>
                                            <option value="" disabled selected>Select a client</option>
                                            @foreach(\App\Models\EcsClient::all() as $client_item)
                                                <option value="{{ $client_item->id }}" {{ $client_item->id == $client->id ? 'selected' : '' }}>{{ $client_item->name_and_balance }}</option>
                                            @endforeach
                                        </select>
                                        <label for="client_id">Select Client:</label>
                                    </div>
                                </div>
                            @endif
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="date" min="{{ substr($earliest_date, 0, 10) }}" name="from_date"
                                               value="{{ substr($from_date, 0, 10) }}" class="form-control">
                                        <label>From Date (Earliest: {{ substr($earliest_date, 0, 10) }})</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="date" name="to_date" max="{{ now()->toDateString() }}"
                                               value="{{ substr($to_date, 0, 10) }}" class="form-control">
                                        <label>To Date</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-block bg-primary">FILTER</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="card-body">
                        <table id="ticket-log-table" class="table table-hover table-bordered text-nowrap w-100">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>DATE</th>
                                <th>DETAILS</th>
                                <th>PNR</th>
                                <th>CLIENT</th>
                                @php
                                    $show_category = $client->select_category;
                                @endphp
{{--                                @if($show_category)--}}
{{--                                    <th>CATEGORY</th>--}}
{{--                                @endif--}}
                                @php
                                    $client_fees = $client->fees() ?? [];

                                @endphp
                                @if($client->taxes() && count($client->taxes()))
                                    @php
                                        $client_taxes = $client->taxes();
                                    @endphp

                                    @foreach($client_taxes as $tax_name)
                                        <th>{{ $tax_name }}</th>
                                    @endforeach
                                @endif
                                @if($client_fees && count($client_fees))
                                    @foreach($client_fees as $fee)
                                        <th>{{ strtoupper(unSlug($fee)) }}</th>
                                    @endforeach
                                @endif


                                <th>PENALTIES</th>
                                <th>SERVICE FEE</th>
                                <th>TICKET FARE</th>
                                <th>DEBIT</th>
                                <th>CREDIT</th>
                                <th>BALANCE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $sum_credit = $sum_debit = $current_balance = 0;
                            @endphp
                            @foreach($items as $key => $item)
                                @php
                                    $ticket = $item->ticket();
                                @endphp

                                @php
                                    $bg_colours = [
                                    'REFUND' => '#ACE1AF',
                                    'summary' => '#99FF66'
                                    ];

                                    $bg_colour = array_key_exists($item->source, $bg_colours) ? $bg_colours[$item->source] : '';
                                    $bg_colour = isset($item->debit_amount) && $item->debit_amount != 0 && $item->source == 'summary' ? '#C0C0C0' : $bg_colour;
                                    $rowStyle = '';
                                    if (!empty($bg_colour)) {
                                    $rowStyle = 'style="background-color: ' . $bg_colour . '"';
                                    }
                                    @endphp
                                <tr {!! $rowStyle !!}>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->for_date->toDateString() }}</td>
                                    <td>{{ $item->details }}</td>
                                    <td>{{ optional($ticket)->booking_reference }}</td>
                                    <td>{{ $item->client_idRelation->name_and_balance }}</td>
{{--                                    @if($show_category)--}}
{{--                                        <td>{{ $ticket ? $ticket->category : '' }}</td>--}}
{{--                                    @endif--}}
                                    @if(isset($client_taxes))
                                        @if($ticket && $ticket->taxes)
                                        @php
                                            $taxes = $ticket->taxes;
                                        @endphp

                                        @foreach($client_taxes as $tax_name)
                                            <td>{{ optional($taxes->where('tax_name', $tax_name)->first())->amount }}</td>
                                        @endforeach
                                            @else
                                            @foreach($client_taxes as $tax_name)
                                                <td>-</td>
                                            @endforeach
                                        @endif
                                    @endif

                                    @if(isset($client_fees) && count($client_fees))
                                        @foreach($client_fees as $fee)
                                            @php $field = unSlug($fee); @endphp
                                            <td>{{ isset($ticket) && isset($ticket->$field) ? number_format($ticket->$field) : '-' }}</td>
                                        @endforeach
                                    @endif

                                    <td>{{ isset($ticket) ? number_format($ticket->penalties) : '-' }}</td>
                                    <td>{{ isset($ticket) ? number_format($ticket->service_fee) : '-' }}</td>
                                    <td>{{ isset($ticket) ? number_format($ticket->ticket_fare) : '-' }}</td>
                                    <td class="{{ $item->debit_amount != 0 ? 'text-danger' : '' }}">{{ number_format($item->debit_amount) }}</td>
                                    <td class="{{ $item->credit_amount != 0 ? 'text-success' : '' }}">{{ number_format($item->credit_amount) }}</td>
                                    <td>{{ number_format($item->balance) }}</td>
                                    @php
                                        $sum_credit += $item->credit_amount;
                                        $sum_debit += $item->debit_amount;
                                        $current_balance = $item->balance;
                                    @endphp
                                </tr>
                            @endforeach

                            @php
                                $num_empty_cols = 8 + (isset($client_taxes) && count($client_taxes) ? count($client_taxes) : 0) + (isset($client_fees) && count($client_fees) ? count($client_fees) : 0);
                            @endphp

                            <tr>
                                @for($i = 1; $i <= $num_empty_cols; $i++)
                                    <th>
                                    </th>
                                @endfor
                                <th class="bg-maroon">{{ number_format($sum_debit) }}</th>
                                <th class="bg-navy">{{ number_format($sum_credit) }}</th>
                                <th class="bg-primary">{{ number_format($current_balance) }}</th>
                            </tr>
                            <tr>
                                @for($i = 1; $i <= $num_empty_cols; $i++)
                                    <th>
                                    </th>
                                @endfor
                                <th class="bg-maroon">TOTAL DEBIT</th>
                                <th class="bg-navy">TOTAL CREDIT</th>
                                <th>BALANCE AS OF {{ $to_date->format('jS F, Y') }}</th>
                            </tr>
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
        $(document).ready(function () {
            var table = new DataTable('#ticket-log-table', {
                "paging": false,
                scrollY: 465,
                sort: false,
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

    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
