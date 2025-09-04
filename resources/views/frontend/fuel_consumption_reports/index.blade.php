@extends('frontend.layouts.app')

@section('title', 'Fuel Consumption Reports')

@push('after-styles')
    <style>
        .table td {
            padding: 0.3rem;
        }
        .scrollable-div {
            height: 100vh; /* Change this value to set the desired fixed height */
            overflow: auto; /* This will enable vertical scrolling if content exceeds the height */
        }

        .flex-fill {
            height: 100%;
        }

        thead {
            position: sticky;
            top: 0;
            background-color: #fff;
        }

        .sticky-column {
            position: sticky;
            left: 0;
            z-index: 1;
            background-color: #f2f2f2;
        }
        th {
            min-width: 7rem;
        }
    </style>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-body table-responsive p-0 scrollable-div text-nowrap">
                            <table class="table table-striped table-borderless table-hover table-valign-middle">
                                <thead class="shadow" style="z-index: 9">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    @foreach($columns as $column)
                                        <th>{{ strtoupper(str_replace('_', ' ', $column)) }}</th>
                                    @endforeach
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="finance-ras-logs-tbody">
                                @foreach($fuel_consumption_reports as $log)
                                    <tr id="trow-{{ $log->id }}">
                                        <td>{{ $log->id }}</td>
                                        <td>@csrf
                                            <input type="hidden" name="log_id" value="{{ $log->id }}"><input
                                                class="form-control" type="date" name="date_f"
                                                value="{{ $log->date_f }}"></td>
                                        @foreach($columns as $column)
                                            <td>

                                            @if(in_array($column, ['location', 'vendors', 'ac_reg', 'destination']))
                                                <input class="form-control" type="text" name="{{ $column }}" value="{{ $log->$column }}" list="dl_{{ $column }}">
                                            @else
                                                <input class="form-control" type="text" name="{{ $column }}" value="{{ $log->$column }}">
                                            @endif
                                            </td>
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
                                    @foreach($columns as $column)
                                        <th>{{ strtoupper(str_replace('_', ' ', $column)) }}</th>
                                    @endforeach
                                    <th>Actions</th>
                                </tr>
                                <!-- Row for adding a new record -->
                                <tr id="form-tr">
                                    <td></td>
                                    <form id="add-form">
                                        @csrf
                                        <td><input class="form-control" type="date" name="date_f"></td>
                                        @foreach($columns as $column)
                                            <td>
                                                @if(in_array($column, ['location', 'vendors', 'ac_reg', 'destination']))
                                                    <input class="form-control" type="text" name="{{ $column }}" list="dl_{{ $column }}">
                                                @else
                                                    <input class="form-control" type="text" name="{{ $column }}">
                                                @endif
                                            </td>
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
                    </div>
                </div>
            </div>
        </div>

        <datalist id="dl_location">
            @foreach($locations as $location)
                <option value="{{ $location }}"></option>
            @endforeach
        </datalist>

        <datalist id="dl_destination">
            @foreach($destinations as $destination)
                <option value="{{ $destination }}"></option>
            @endforeach
        </datalist>

        <datalist id="dl_vendors">
            @foreach($vendor_names as $vendor)
                <option value="{{ $vendor }}"></option>
            @endforeach
        </datalist>

        <datalist id="dl_ac_reg">
            @foreach($ac_reg as $reg)
                <option value="{{ $reg }}"></option>
            @endforeach
        </datalist>

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

                    fetch('{{ route('fuel_consumption_report.update') }}', {
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
                        fetch('{{ url('api/fuel_consumption_report') }}/' + id, {
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

                fetch('{{ route('frontend.fuel_consumption_report.store') }}', {
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
    <td>
     @csrf
                            <input type="hidden" name="log_id" value="${newLog.id}">
    <input class="form-control" type="date" name="date_f" value="${newLog.date_f}"></td>
    @foreach($columns as $column)
                            <td><input class="form-control" type="text" name="{{ $column }}" value="${ newLog.{{ $column }} }"
                             @if(in_array($column, ['location', 'vendors', 'ac_reg', 'destination']))
                                list="dl_{{ $column }}"
                                @endif
                            ></td>
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
                            const response = await fetch(`{{ route('fuel_consumption_report.update', ':id') }}`.replace(':id', id), {
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
                                const response = await fetch(`{{ url('api/fuel_consumption_report') }}/${id}`, {
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
            // Check for new entries and add them to the datalist arrays
            document.querySelectorAll('input[list]').forEach(input => {
                input.addEventListener('change', function() {
                    const listId = this.getAttribute('list');
                    const datalist = document.getElementById(listId);
                    const newValue = this.value;

                    // Check if the value is already in the datalist
                    let exists = false;
                    datalist.querySelectorAll('option').forEach(option => {
                        if (option.value === newValue) {
                            exists = true;
                        }
                    });

                    // If the value is new, add it to the datalist
                    if (!exists) {
                        const newOption = document.createElement('option');
                        newOption.value = newValue;
                        datalist.appendChild(newOption);
                    }
                });
            });
        </script>

@endsection


