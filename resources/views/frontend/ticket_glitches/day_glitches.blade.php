@extends('frontend.layouts.app')

@section('title', 'Glitches for '.$day )

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    @php($ticketed_row_bg_class = 'bg-gradient-lightblue')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
                            @yield('title')
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
                    <option {{ $booking->ticket_status == 'Ticketed' ? 'selected' : ''}}>Ticketed</option>
                    <option {{ $booking->ticket_status == 'Non Ticketed' ? 'selected' : ''}}>Non Ticketed</option>
                </select>
            </td>
            <td><input type="datetime-local" class="form-control booking-{{ $booking->pnr }} booking-data" name="ticketed_date" value="{{ $booking->ticketed_date }}" id="ticketed_date-{{ $booking->pnr }}"></td>
            @if(!is_null($booking->agent()))
                @php($agent = $booking->agent())
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
            "responsive": false, "lengthChange": false, "autoWidth": true, "scrollX": true, paging: false, scrollY: 665,
            "buttons": ["colvis"]
        }).buttons().container().appendTo('#bookings_wrapper .col-md-6:eq(0)');
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
                            if(ticketStatus === 'Ticketed') {
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

