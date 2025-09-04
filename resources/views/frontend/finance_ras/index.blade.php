@extends('frontend.layouts.app')

@section('title', 'Finance - RA ')

@push('after-styles')
    <style>
        /*.table td {*/
        /*    padding: 0.1rem;*/
        /*}*/
        .table-wrapper {
            max-height: 95vh; /* Adjust the height as needed */
            overflow-y: auto; /* Enable vertical scrolling */
        }

        .form-control {
            border-radius: 0;
        }

        .sticky-column {
            position: sticky; /* Enable sticky behavior */
            left: 0; /* Fix to the left side */
            background-color: #EEF2FF; /* Maintain background to avoid overlap */
            z-index: 2; /* Ensure sticky column is on top */
        }

        .table-head {
            position: sticky; /* Enable sticky behavior */
            top: 0; /* Fix the header to the top */
            background-color: #f8f9fa; /* Background to avoid overlap */
            z-index: 2; /* Ensure header stays on top */
        }

        .wide-th {
            min-width: 12rem;
        }
    </style>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card text-center">
                        {{--                        <div class="card-header">--}}
                        {{--                            <h3 class="card-title">Finance - RA - Google Data Studio BI Report </h3>--}}
                        {{--                        </div>--}}
                        <div class="card-body table-responsive p-0 text-nowrap table-wrapper">
                            <table class="table table-striped table-borderedd table-hover table-sm">
                                <thead>
                                <tr>
                                    <th class="table-head">ID</th>
                                    <th class="table-head sticky-column">Date</th>
                                    @foreach($columns as $column => $title)
                                        <th class="wide-th table-head">{{ $title }}</th>
                                    @endforeach
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="finance-ras-logs-tbody">
                                @foreach($finance_ras as $log)
                                    <tr id="trow-{{ $log->id }}">
                                        <td>{{ $log->id }}</td>
                                        <td class="sticky-column">@csrf
                                            <input type="hidden" name="log_id" value="{{ $log->id }}"><input
                                                class="form-control" type="date" name="date_f"
                                                value="{{ $log->date_f }}"></td>
                                        @foreach($columns as $column => $title)
                                            <td><input class="form-control" type="number" name="{{ $column }}"
                                                       value="{{ $log->$column }}"></td>
                                        @endforeach

                                        <td>
                                            <button type="button" class="btn btn-success btn-xs me-2 update-btn"
                                                    data-id="{{ $log->id }}">Update
                                            </button>
                                            <button type="button" class="btn btn-danger btn-xs delete-btn"
                                                    data-id="{{ $log->id }}">Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr id="form-tr-hdr">
                                    <th>ID</th>
                                    <th>Date</th>
                                    @foreach($columns as $column => $title)
                                        <th>{{ $title }}</th>
                                    @endforeach
                                    <th>Actions</th>
                                </tr>
                                <!-- Row for adding a new record -->
                                <tr id="form-tr" class="bg-warning">
                                    <td></td>
                                    <form id="add-form">
                                        @csrf
                                        <td class="sticky-column"><input class="form-control" type="date" name="date_f"></td>
                                        @foreach($columns as $column => $title)
                                            <td><input class="form-control" type="number" name="{{ $column }}"></td>
                                        @endforeach
                                        <td>
                                            <button type="button" class="btn btn-primary add-btn" id="submit-btn">
                                                Submit
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        @if(1 < 0)
                        <div class="card-footer">
                            <div class="form-group">
                                <input type="text" class="form-control-lg" id="pasteData">
                            </div>
                            <button type="button" class="btn btn-primary" id="processDataBtn">Process Pasted Data</button>
                        </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>

        @endsection

        @push('after-scripts')
        <script>
            // Add event listeners to all 'update' buttons
            document.querySelectorAll('.update-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const row = document.getElementById('trow-' + id);
                    const formData = new FormData();

                    // Gather data from various elements in the row
                    row.querySelectorAll('input, select').forEach(element => {
                        const name = element.name;
                        const value = element.value;
                        formData.append(name, value);
                    });

                    // Include the log ID
                    formData.append('log_id', id);

                    fetch('{{ route('finance_ra.update') }}', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                showInstantToast(data.message); // Show success message
                            } else {
                                showInstantToast('Error updating record.', 'danger'); // Show failure message
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showInstantToast('An error occurred. Please try again later.', 'danger');
                        });
                });
            });


            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const row = this.closest('tr'); // Get the row associated with the delete button

                    if (confirm('Are you sure you want to delete this record?')) {
                        fetch('{{ url('api/finance_ra') }}/' + id, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('[name=_token]').value
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) { // Assuming 'success' is returned on successful deletion
                                    showInstantToast('Record deleted successfully.', 'warning'); // Display success toast

                                    // Remove the row from the table without reloading
                                    row.remove();
                                } else {
                                    showInstantToast('Error deleting record.', 'danger'); // Display error toast
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showInstantToast('Error deleting record.', 'danger'); // Handle fetch errors
                            });
                    }
                });
            });


            document.querySelector('.add-btn').addEventListener('click', function () {
                console.log('submitted');
                const form = document.querySelector('#add-form');
                $('#submit-btn').html('Processing...').addClass('disabled');

                const formData = new FormData(form);

                fetch('{{ route('frontend.finance_ra.store') }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) { // Assuming the response contains a success flag
                            const tableBody = document.getElementById('finance-ras-logs-tbody'); // Adjust selector to match your table
                            const newRow = document.createElement('tr'); // Create a new row
                            const newLog = data.new_log; // Accessing the new log data from the server response

                            // Add the form elements in the new row
                            newRow.innerHTML = `

    <td>${newLog.id}</td>
    <td class="sticky-column">
     @csrf
                            <input type="hidden" name="log_id" value="${newLog.id}">
    <input class="form-control" type="date" name="date_f" value="${newLog.date_f}"></td>
    @foreach($columns as $column => $title)
                            <td><input class="form-control" type="number" name="{{ $column }}" value="${ newLog.{{ $column }} }"></td>
    @endforeach
                            <td>
                                <button type="button" class="btn btn-success btn-xs me-2 update-btn" data-id="${newLog.id}">Update</button>
        <button type="button" class="btn btn-danger btn-xs delete-btn" data-id="${newLog.id}">Delete</button>
    </td>`;
                            newRow.id = 'trow-' + newLog.id;
                            // Insert the new row just before the form row
                            tableBody.insertBefore(newRow, document.querySelector('#form-tr-hdr'));
                            attachEventListenersToRow(newRow);
                            showInstantToast('Record added successfully!', 'success'); // Notify user that the record was added

                        } else {
                            showInstantToast('Error adding record.', 'danger'); // Handle failure
                        }

                        $('#submit-btn').html('Submit').removeClass('disabled');
                        document.getElementById('add-form').reset();
                    })
                    .catch(error => console.error('Error:', error));
            });


            // Ensure all form data is sent
            function attachEventListenersToRow(row) {
                const updateButton = row.querySelector('.update-btn');
                if (updateButton) {
                    updateButton.addEventListener('click', async function () {
                        const id = this.dataset.id;

                        // Find the form with the correct id and create a FormData object
                        const formData = new FormData();

                        // Gather data from various elements in the row
                        row.querySelectorAll('input, select').forEach(element => {
                            const name = element.name;
                            const value = element.value;
                            formData.append(name, value);
                        });

                        // Include the log ID
                        formData.append('log_id', id);

                        try {
                            const response = await fetch(`{{ route('finance_ra.update', ':id') }}`.replace(':id', id), {
                                method: 'POST', // Make sure the method matches the endpoint's expectation
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('[name=_token]').value, // Add CSRF token
                                },
                            });

                            const data = await response.json();

                            if (data.success) {
                                showInstantToast(data.message, 'success');
                            } else {
                                showInstantToast('Error updating record.', 'danger');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            showInstantToast('An error occurred while updating the record.', 'danger');
                        }
                    });
                }

                const deleteButton = row.querySelector('.delete-btn');
                if (deleteButton) {
                    deleteButton.addEventListener('click', async function () {
                        const id = this.dataset.id;
                        const row = this.closest('tr');

                        if (confirm('Are you sure you want to delete this record?')) {
                            try {
                                const response = await fetch(`{{ url('api/finance_ra') }}/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('[name=_token]').value, // Ensure CSRF token
                                    },
                                });

                                const data = await response.json();

                                if (data.success) {
                                    showInstantToast('Record deleted successfully.', 'warning');
                                    row.remove(); // Remove the deleted row
                                } else {
                                    showInstantToast('Error deleting record.', 'danger');
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                showInstantToast('An error occurred while deleting the record.', 'danger');
                            }
                        }
                    });
                }
            }

        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var element = document.getElementById('form-tr');
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            });
        </script>

        <script>
            // To allow pasting from a row in a spreadsheet
            $(document).ready(function() {
                $('#processDataBtn').click(function() {
                    // Get the pasted data from the textarea
                    var pastedData = $('#pasteData').val().trim();
                    // Split the data by tab character (assuming tab-separated values from Excel)
                    var dataArray = pastedData.split('\t');

                    // Populate the form fields with the data
                    var formFields = $('#add-form').find('input');
                    formFields.each(function(index, field) {
                        var value = dataArray[index];
                        console.log(value);
                        if (index === 0) {
                            // Assume the first field is a date field and leave it as it is
                            $(field).val(value);
                        } else if (value) {
                            // Process the value to remove commas and convert "0.00" to "0"
                            value = value.replace(/,/g, ''); // Remove commas
                            value = parseFloat(value); // Convert to a float
                            if (value === 0.00) {
                                value = 0; // Change "0.00" to "0"
                            }
                            $(field).val(value);
                        }
                    });

                    // Clear the input field
                    // $('#pasteData').val('');
                });
            });
        </script>
    @endpush


