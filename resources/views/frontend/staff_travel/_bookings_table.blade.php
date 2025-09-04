<table class="table table-striped text-nowrap w-100">
    <thead>
    <tr>
        <th>S/N</th>
        <th>Staff</th>
{{--        <th>Username</th>--}}
        <th>Beneficiary</th>
        <th>Request Date</th>
        <th>Request Time</th>
        <th>Departure</th>
        <th>Returns</th>
        <th>Adult</th>
        <th>Child</th>
        <th>Infant</th>
        <th>PNR</th>
        <th>IP Address</th>
        <th>Ticket ID</th>
        <th>Transaction ID</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($staff_travel_bookings as $booking)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $booking->staff_member->name_and_ara }}</td>
{{--            <td>{{ $booking->username }}</td>--}}
            <td>{{ $booking->beneficiary_id != 0 ? $booking->beneficiary->name : 'Self' }}</td>
            <td>{{ $booking->request_date }}</td>
            <td>{{ $booking->request_time }}</td>
            <td>{{ $booking->departure }}</td>
            <td>{{ $booking->returns }}</td>
            <td>{{ $booking->adult }}</td>
            <td>{{ $booking->child }}</td>
            <td>{{ $booking->infant }}</td>
            <td>{{ $booking->pnr }}</td>
            <td>{{ $booking->ip_address }}</td>
            <td>{{ $booking->ticket_id }}</td>
            <td>{{ $booking->transaction_id }}</td>
            <td>{{ $booking->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
