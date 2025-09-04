@extends('frontend.layouts.app')

@section('title', 'Holidays' )

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
                            <h3 class="card-title">Holidays</h3>
                            <button class="btn bg-navy float-right" data-toggle="modal" data-target="#addHolidayModal">Add Holiday</button>

                        </div>
                        <div class="card-body">
                            <table id="holidayTable" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Entered By</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
{{--                                @foreach ($holidays as $holiday)--}}
{{--                                    <tr>--}}
{{--                                        <td>{{ $holiday->title }}</td>--}}
{{--                                        <td>{{ $holiday->entered_by_staff_ara_id }}</td>--}}
{{--                                        <td>{{ $holiday->holidate }}</td>--}}
{{--                                        <td>--}}
{{--                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editHolidayModal{{$holiday->id}}">Edit</button>--}}
{{--                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteHolidayModal{{$holiday->id}}">Delete</button>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Holiday Modal -->
    <div class="modal fade" id="addHolidayModal" tabindex="-1" role="dialog" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHolidayModalLabel">Add Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add Holiday Form -->
                    <form id="addHolidayForm">
                        @csrf <!-- CSRF token -->

                        <!-- Holiday Title -->
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <!-- Entered By Staff ID -->
                        <div class="form-group">
                            <label for="entered_by_staff_ara_id">Entered By Staff ID</label>
                            <input type="text" class="form-control" id="entered_by_staff_ara_id" name="entered_by_staff_ara_id" required>
                        </div>

                        <!-- Holiday Date -->
                        <div class="form-group">
                            <label for="holidate">Holiday Date</label>
                            <input type="date" class="form-control" id="holidate" name="holidate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addHoliday()">Add Holiday</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Holiday Modal -->
    <div class="modal fade" id="editHolidayModal" tabindex="-1" role="dialog" aria-labelledby="editHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHolidayModalLabel">Edit Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Edit Holiday Form -->
                    <form id="editHolidayForm">
                        @csrf <!-- CSRF token -->

                        <!-- Hidden input for holiday ID -->
                        <input type="hidden" id="editHolidayId" name="id">

                        <!-- Holiday Title -->
                        <div class="form-group">
                            <label for="editTitle">Title</label>
                            <input type="text" class="form-control" id="editTitle" name="title" required>
                        </div>

                        <!-- Entered By Staff ID -->
                        <div class="form-group">
                            <label for="editEnteredByStaff">Entered By Staff ID</label>
                            <input type="text" class="form-control" id="editEnteredByStaff" name="entered_by_staff_ara_id" required>
                        </div>

                        <!-- Holiday Date -->
                        <div class="form-group">
                            <label for="editHolidate">Holiday Date</label>
                            <input type="date" class="form-control" id="editHolidate" name="holidate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateHoliday()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteHolidayModal" tabindex="-1" role="dialog" aria-labelledby="deleteHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteHolidayModalLabel">Delete Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this holiday?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="deleteHoliday()">Delete</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('after-scripts')
    <script>

        fetch('{{ route('frontend.attendance.holidays.index') }}')
            .then(response => response.json())
            .then(data => {
                const holidayTable = document.getElementById('holidayTable');
                const tbody = holidayTable.querySelector('tbody');

                data.forEach(holiday => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${holiday.title}</td>
                    <td>${holiday.entered_by_staff_ara_id}</td>
                    <td>${holiday.holidate}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="editHoliday(${holiday.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="confirmDelete(${holiday.id})">Delete</button>
                    </td>
                `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error(error));


        function addHoliday() {
            const title = document.getElementById('title').value;
            const entered_by_staff_ara_id = document.getElementById('entered_by_staff_ara_id').value;
            const holidate = document.getElementById('holidate').value;

            const formData = {
                title: title,
                entered_by_staff_ara_id: entered_by_staff_ara_id,
                holidate: holidate,
            };

            fetch('{{ route('frontend.attendance.holidays.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                },
                body: JSON.stringify(formData),
            })
                .then(response => response.json())
                .then(data => {
                    // Handle the response, e.g., close the modal, update the table, etc.
                    $('#addHolidayModal').modal('hide'); // Close the modal
                    const holidayTable = document.getElementById('holidayTable');
                    const tbody = holidayTable.querySelector('tbody');

                        const row = document.createElement('tr');
                        row.innerHTML = `
                    <td>${data.title}</td>
                    <td>${data.entered_by_staff_ara_id}</td>
                    <td>${data.holidate}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="editHoliday(${data.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="confirmDelete(${data.id})">Delete</button>
                    </td>
                `;
                        tbody.appendChild(row);

                })
                .catch(error => {
                    console.error(error);
                    // Handle any error that may occur during the POST request
                });
        }


        // Function to populate the edit modal with holiday data
        function editHoliday(id) {
            // Fetch holiday data for the given ID and populate the edit modal
            fetch(`{{ config('app.url') }}/attendance/holidays/${id}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the edit modal form fields with retrieved data
                    document.getElementById('editHolidayId').value = data.id;
                    document.getElementById('editTitle').value = data.title;
                    document.getElementById('editEnteredByStaff').value = data.entered_by_staff_ara_id;
                    document.getElementById('editHolidate').value = data.holidate;

                    $('#editHolidayModal').modal('show');
                })
                .catch(error => console.error(error));
        }

        // Function to update a holiday
        function updateHoliday() {
            const id = document.getElementById('editHolidayId').value;
            const title = document.getElementById('editTitle').value;
            const entered_by_staff_ara_id = document.getElementById('editEnteredByStaff').value;
            const holidate = document.getElementById('editHolidate').value;

            const formData = {
                title: title,
                entered_by_staff_ara_id: entered_by_staff_ara_id,
                holidate: holidate,
            };

            fetch(`{{ config('app.url') }}/attendance/holidays/${id}`, {
                method: 'PUT', // Use PUT to update the holiday
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                },
                body: JSON.stringify(formData),
            })
                .then(response => response.json())
                .then(data => {
                    // Handle the response, e.g., close the modal, update the table, etc.
                    $('#editHolidayModal').modal('hide'); // Close the modal
                    // You can also update the table with the edited holiday data here
                })
                .catch(error => {
                    console.error(error);
                    // Handle any error that may occur during the PUT request
                });
        }


        // Function to confirm and delete a holiday
        function confirmDelete(id) {
            // Set the ID of the holiday to delete in a hidden input field
            document.getElementById('deleteHolidayId').value = id;

            $('#deleteHolidayModal').modal('show');
        }

        // Function to delete a holiday
        function deleteHoliday() {
            const id = document.getElementById('deleteHolidayId').value;

            fetch(`{{ config('app.url') }}/attendance/holidays/${id}`, {
                method: 'DELETE', // Use DELETE to delete the holiday
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                },
            })
                .then(response => {
                    if (response.ok) {
                        // Handle the response, e.g., close the modal, update the table, etc.
                        $('#deleteHolidayModal').modal('hide'); // Close the modal
                        // You can also update the table to remove the deleted holiday here
                    } else {
                        // Handle the case where the deletion request failed
                        console.error('Delete request failed.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    // Handle any error that may occur during the DELETE request
                });
        }

    </script>
@endpush
