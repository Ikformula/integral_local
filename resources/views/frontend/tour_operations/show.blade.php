@extends('frontend.layouts.app')

@push('after-styles')
    <style>
        td {
            padding-top: 1px !important;
            padding-bottom: 1px !important;
        }
    </style>
@endpush

@section('title', 'PNL Requirements')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card arik-card">
                        <div class="card-header">
                            <h5 class="card-title">Passenger Information</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <tr>
                                    <td>Surname:</td>
                                    <td>{{ $passenger->surname }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->surname }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>First Name:</td>
                                    <td>{{ $passenger->firstname }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->firstname }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Other Name:</td>
                                    <td>{{ $passenger->other_name }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->other_name }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Gender:</td>
                                    <td>{{ $passenger->gender }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->gender }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Nationality:</td>
                                    <td>{{ $passenger->nationality }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->nationality }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Date of Birth:</td>
                                    <td>{{ $passenger->date_of_birth }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->date_of_birth }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Passport Number:</td>
                                    <td>{{ $passenger->passport_number }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->passport_number }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Place of Issue:</td>
                                    <td>{{ $passenger->place_of_issue }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->place_of_issue }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Date of Issue:</td>
                                    <td>{{ $passenger->date_of_issue }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->date_of_issue }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Expiry Date:</td>
                                    <td>{{ $passenger->expiry_date }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->expiry_date }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Destination:</td>
                                    <td>{{ $passenger->destination }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->destination }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Class:</td>
                                    <td>{{ $passenger->class }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->class }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>SSR Group:</td>
                                    <td>{{ $passenger->ssr_group }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->ssr_group }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Port of Origin:</td>
                                    <td>{{ $passenger->port_of_origin }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->port_of_origin }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Visa Date of Issuance:</td>
                                    <td>{{ $passenger->visa_date_of_issuance }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->visa_date_of_issuance }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Visa Date of Expiry:</td>
                                    <td>{{ $passenger->visa_date_of_expiry }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->visa_date_of_expiry }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Proposed Flight Dates:</td>
                                    <td>Outbound: {{ $passenger->proposed_flight_date_outbound }} - Inbound: {{ $passenger->proposed_flight_date_inbound }}</td>
{{--                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->proposed_flight_date_outbound }}')">Copy</button></td>--}}

                                </tr>
                                <tr>
                                    <td>Agency Location:</td>
                                    <td>{{ $passenger->agency_location }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->agency_location }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Agency Name:</td>
                                    <td>{{ $passenger->agency_name ?? '' }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->agency_name ?? '' }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Phone number:</td>
                                    <td>{{ $passenger->phone_number ?? '' }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->phone_number ?? '' }}')">Copy</button></td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>{{ $passenger->email ?? '' }}</td>
                                    <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $passenger->email ?? '' }}')">Copy</button></td>
                                </tr>

                                @if($passenger->infant())
                                    <tr><th colspan="3">Attached Infant</th></tr>
                                @php($infant = $passenger->infant())
                                    <tr>
                                        <td>Surname:</td>
                                        <td>{{ $infant->surname }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->surname }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>First Name:</td>
                                        <td>{{ $infant->firstname }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->firstname }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Other Name:</td>
                                        <td>{{ $infant->other_name }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->other_name }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Gender:</td>
                                        <td>{{ $infant->gender }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->gender }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Nationality:</td>
                                        <td>{{ $infant->nationality }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->nationality }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Date of Birth:</td>
                                        <td>{{ $infant->date_of_birth }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->date_of_birth }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Passport Number:</td>
                                        <td>{{ $infant->passport_number }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->passport_number }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Place of Issue:</td>
                                        <td>{{ $infant->place_of_issue }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->place_of_issue }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Date of Issue:</td>
                                        <td>{{ $infant->date_of_issue }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->date_of_issue }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Expiry Date:</td>
                                        <td>{{ $infant->expiry_date }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->expiry_date }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Destination:</td>
                                        <td>{{ $infant->destination }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->destination }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Class:</td>
                                        <td>{{ $infant->class }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->class }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>SSR Group:</td>
                                        <td>{{ $infant->ssr_group }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->ssr_group }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Port of Origin:</td>
                                        <td>{{ $infant->port_of_origin }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->port_of_origin }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Visa Date of Issuance:</td>
                                        <td>{{ $infant->visa_date_of_issuance }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->visa_date_of_issuance }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Visa Date of Expiry:</td>
                                        <td>{{ $infant->visa_date_of_expiry }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->visa_date_of_expiry }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Proposed Flight Dates:</td>
                                        <td>Outbound: {{ $infant->proposed_flight_date_outbound }} - Inbound: {{ $infant->proposed_flight_date_inbound }}</td>
{{--                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->proposed_flight_date }}')">Copy</button></td>--}}
                                    </tr>
                                    <tr>
                                        <td>Agency Location:</td>
                                        <td>{{ $infant->agency_location }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->agency_location }}')">Copy</button></td>
                                    </tr>
                                    <tr>
                                        <td>Agency Name:</td>
                                        <td>{{ $infant->agency_name }}</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="copyToClipboard('{{ $infant->agency_name }}')">Copy</button></td>
                                    </tr>

                                @endif
                            </table>

                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="card arik-card">
                        <div class="card-header">
                            Update Ticket and PNR
                        </div>
                        <div class="card-body">
                            <form action="{{ route('frontend.tour_operations.passengers.update', $passenger) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <input type="text" id="ticket_id" name="ticket_id"
                                               value="{{ $passenger->ticket_id ?? '' }}" class="form-control" required>
                                        <label for="ticket_id">Ticket Number:</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" id="pnr_number" name="pnr_number"
                                               value="{{ $passenger->pnr_number ?? '' }}" class="form-control" required>
                                        <label for="pnr_number">PNR Number:</label>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn bg-maroon ">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card arik-card">
                        <div class="card-header">
                            Passenger Record Tab Release
                        </div>
                        <div class="card-body">
                            <form action="{{ route('frontend.tour_operations.passengers.unlock', $passenger) }}" method="POST">
                                @csrf
                                <p>Unhand this passenger so another TRO can work on it</p>
                                <button type="submit" class="btn btn-dark">Unhand</button>
                            </form>
                        </div>
                    </div>
{{--                    <div class="card arik-card">--}}
{{--                        <div class="card-header">--}}
{{--                            Abandon Booking--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <form action="#" method="POST">--}}
{{--                                @csrf--}}
{{--                                <div class="form-group">--}}
{{--                                    <label>Reason to Abandon</label>--}}
{{--                                    <textarea class="form-control" name="reason_for_abandonment" required></textarea>--}}
{{--                                </div>--}}
{{--                                <button type="submit" class="btn btn-warning">Abandon</button>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </section>

@endsection

@push('after-scripts')
    <script>
        function copyToClipboard(value) {
            const input = document.createElement('input');
            input.value = value;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showInstantToast('Value copied to clipboard: ' + value, 'success');
        }


        {{--window.addEventListener('beforeunload', function (event) {--}}
        {{--    // Prevent the default behavior of the event--}}
        {{--    event.preventDefault();--}}

        {{--    // Send a POST request to the specified route--}}
        {{--    fetch('{{ route('frontend.tour_operations.passengers.unlock', $passenger) }}', {--}}
        {{--        method: 'POST',--}}
        {{--        headers: {--}}
        {{--            'Content-Type': 'application/json',--}}
        {{--            // Add any required headers--}}
        {{--        },--}}
        {{--        body: JSON.stringify({}),--}}
        {{--    })--}}
        {{--        .then(function (response) {--}}
        {{--            // Handle the response as needed--}}
        {{--            console.log('Unlock request sent successfully');--}}
        {{--        })--}}
        {{--        .catch(function (error) {--}}
        {{--            // Handle any errors that occur during the request--}}
        {{--            console.error('Error sending unlock request:', error);--}}
        {{--        });--}}

        {{--    // Set a custom message in the confirmation dialog--}}
        {{--    // event.returnValue = 'Are you sure you want to leave this page?';--}}
        {{--});--}}

    </script>
@endpush
