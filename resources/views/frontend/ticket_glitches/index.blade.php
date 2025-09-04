@extends('frontend.layouts.app')

@section('title', 'Online Glitches' )

@push('after-styles')
    @include('includes.partials._datatables-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')

    @php($ticketed_row_bg_class = 'bg-gradient-lightblue')
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" name="from_date" value="{{ substr($from_date, 0, 10) }}" class="form-control">
                                    <label>From Date</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" name="to_date" value="{{ substr($to_date, 0, 10) }}" class="form-control">
                                    <label>To Date</label>
                                </div>
                            </div>
                            <div class="col-md-2 align-baseline">
                                <label>
                                <input type="checkbox" class="form-check-inline" name="exclude_ticketed" value="1" @if(isset($_GET['exclude_ticketed']) && $_GET['exclude_ticketed'] == 1) checked @endif>
                                    Exclude Ticketed
                                </label>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-info btn-block">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                @foreach($stats as $stat)
                    <div class="{{ $stats_col_class }}">
                        @component('frontend.components.dashboard_stat_widget', ['icon' => $stat['icon'], 'title' => $stat['title']])
                            <h3>{{ $stat['value'] }}</h3>
                        @endcomponent
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                          <form action="{{ route('frontend.ticket_glitches_report.store') }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              <label>Add an SQL CSV File From Crane</label>
                              <div class="input-group">
                                  <input type="file" class="form-control" name="csv_file" id="csv_file" accept="text/csv" required>
                                  <button class="btn bg-navy" type="submit" id="">Upload CSV</button>
                              </div>
                          </form>
                      </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
<h5 class="card-title">Successful Sales but Not Ticketed Glitches</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover text-nowrap" id="bookings">
                                    <thead>
                                    <tr>
                                        <td>S/N</td>
                                        <td>PNR</td>
                                        <td>USER</td>
                                        <td>VPOS</td>
                                        <td>OPERATION DATE</td>
                                        <td>ORDER_ID</td>
                                        <td>DEPARTURE DATE</td>
                                        <td>TICKET STATUS</td>
                                        <td>TICKETED DATE</td>
                                        <td>AGENT</td>
                                        {{--        <td></td>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bookings as $booking)
                                        <tr class="{{ $booking->ticket_status == 'Ticketed' ? $ticketed_row_bg_class : '' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $booking->pnr }}</td>
                                            <td>{{ $booking->user }}</td>
                                            <td>{{ $booking->vpos }}</td>
                                            <td>{{ $booking->operation_date }}</td>
                                            <td>{{ $booking->order_id }}</td>
                                            <td><input type="datetime-local" class="form-control booking-{{ $booking->pnr }} booking-data" name="departure_date"  id="departure_date-{{ $booking->pnr }}" value="{{ $booking->departure_date }}"></td>
                                            <td>
                                                <select class="form-control booking-{{ $booking->pnr }} booking-data" name="ticket_status" id="ticket_status-{{ $booking->pnr }}">
                                                    @if(is_null($booking->ticket_status))
                                                        <option selected disabled>Select one option</option>
                                                    @endif
                                                    <option {{ $booking->ticket_status == 'TK' ? 'selected' : ''}}>TK</option>
                                                    <option {{ $booking->ticket_status == 'CX' ? 'selected' : ''}}>CX</option>
                                                </select>
                                            </td>
                                            <td><input type="datetime-local" class="form-control booking-{{ $booking->pnr }} booking-data" name="ticketed_date" value="{{ $booking->ticketed_date }}" id="ticketed_date-{{ $booking->pnr }}"></td>
                                            @if($booking->agent)
                                                @php($agent = $booking->agent)
                                                <td>{{ $agent->full_name }}</td>
                                            @else
                                                <td></td>
                                            @endif
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
        $("#bookings").DataTable({
            "responsive": false, "lengthChange": false, "autoWidth": false, "scrollX": true, paging: false, scrollY: 665,
            "buttons": ["colvis"]
        }).buttons().container().appendTo('#bookings_wrapper .col-md-6:eq(0)');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $("input[type=datetime-local]").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.booking-data').on('change', function () {
                var row = $(this).closest('tr');
                var pnr = row.find('td:eq(1)').text(); // Assuming PNR is in the second column
                var agent_name = row.find('td:eq(9)');
                var departureDate = row.find('input[name="departure_date"]').val();
                var ticketStatus = row.find('select[name="ticket_status"]').val();
                var ticketedDate = row.find('input[name="ticketed_date"]').val();

                // Check if all three fields are filled
                if (departureDate || ticketStatus || ticketedDate) {
                    // Prepare data for the POST request
                    var postData = {
                        pnr: pnr,
                        departure_date: departureDate,
                        ticket_status: ticketStatus,
                        ticketed_date: ticketedDate,
                        staff_ara_id: '{{ $logged_in_user->staff_member()->staff_ara_id ?? '' }}',
                        agent_user_id: {{ $logged_in_user->id }}
                    };

                    // Send data to the route using AJAX
                    $.ajax({
                        url: '{{ route('glitches.updatePNR') }}', // Replace with your actual route URL
                        type: 'POST',
                        data: postData,
                        success: function (response) {
                            // Handle success response if needed
                            console.log(response);
                            showInstantToast('PNR ' + pnr + ' updated');
                            agent_name.text('{{ $logged_in_user->full_name }}');
                            if(ticketStatus === 'TK') {
                                row.attr('class', '{{ $ticketed_row_bg_class }}');
                            }else if(ticketStatus != 'Ticketed'){
                                row.attr('class', '');
                            }
                        },
                        error: function (error) {
                            // Handle error response if needed
                            console.error(error);
                            showInstantToast('PNR not updated');
                        }
                    });
                }
            });
        });
    </script>
@endpush
