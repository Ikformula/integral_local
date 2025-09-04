@extends('frontend.layouts.app')

@section('title', 'Disruption Logs')
@yield('title', 'Disruption Logs')

@push('after-styles')
    <style>
        /*.table td {*/
        /*    padding: 0.1rem;*/
        /*}*/
        .table-wrapper {
            max-height: 100vh; /* Adjust the height as needed */
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
            min-width: 12rem;
            box-shadow: rgba(13,72,255,0.2);
        }

        th {

        }
    </style>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
    <div class="row">
    <div class="col">

        <div class="card text-center">
          <div class="card-header">
              {{ $logs->links() }}
          </div>
          <div class="card-body table-responsive p-0 text-nowrap table-wrapper">
              <table class="table table-striped table-borderedd table-hover table-sm">
                  <thead>
                  <tr>
                      <th class="table-head">Action Date</th>
                      <th class="table-head">Time Sent by CRC</th>
                      <th class="sticky-column">Log Number</th>
                      <th class="table-head">Requested By</th>
                      <th class="table-head">Flight Date Start</th>
                      <th class="table-head">Flight Date End</th>
                      <th class="table-head">Days of Week</th>
                      <th class="table-head">Flight Number</th>
                      <th class="table-head">Old Flight Time</th>
                      <th class="table-head">Old Flight Route</th>
                      <th class="table-head">New Flight Number</th>
                      <th class="table-head">New Flight Time</th>
                      <th class="table-head">Disruption Status</th>
                      <th class="table-head">Reason for Disruption</th>
                      <th class="table-head">Actions Taken</th>
                      <th class="table-head">Actioned By</th>
                      <th class="table-head">Callcentre Comments</th>
                      <th class="table-head">Pax figure for disrupted flights</th>
                      <th class="table-head">Pax figure for connecting flights</th>
                      <th class="table-head">Actions</th>
                  </tr>
                  </thead>
                  <tbody id="flight-logs-tbody">
                  @foreach($logs as $log)
                      <tr id="trow-{{ $log->id }}">
                              <td>@csrf
                                  <input type="hidden" name="log_id" value="{{ $log->id }}"><input class="form-control" type="date" name="action_date" value="{{ $log->action_date }}"></td>
                              <td><input class="form-control" type="time" name="time_sent_by_crc" value="{{ $log->time_sent_by_crc }}"></td>
                              <td class="sticky-column">{{ $log->log_number }}</td>
                              <td>
                                  @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'requested_by', 'option_list' => 'requested_by_options', 'value' => $log->requested_by])
                              </td>
                              <td><input class="form-control" type="date" name="flight_date_start" value="{{ $log->flight_date_start }}"></td>
                              <td><input class="form-control" type="date" name="flight_date_end" value="{{ $log->flight_date_end }}"></td>
                              <td><input class="form-control" type="number" name="days_of_week" value="{{ $log->days_of_week }}"></td>
                              <td>
                                  @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'flight_number', 'option_list' => 'flight_number_options', 'value' => $log->flight_number])
                              </td>
                              <td><input class="form-control" type="time" name="old_flight_time" value="{{ $log->old_flight_time }}"></td>
                              <td>
                                  @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'old_flight_route', 'option_list' => 'old_flight_route_options', 'value' => $log->old_flight_route])
                              </td>
                              <td><input class="form-control" type="text" name="new_flight_number" value="{{ $log->new_flight_number }}"></td>
                              <td><input class="form-control" type="time" name="new_flight_time" value="{{ $log->new_flight_time }}"></td>
                              <td>
                                  @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'disruption_status', 'option_list' => 'disruption_status_options', 'value' => $log->disruption_status])
                              </td>
                              <td>
                                  @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'reason_for_disruption', 'option_list' => 'reason_for_disruption_options', 'value' => $log->reason_for_disruption])
                              </td>
                              <td>
                                  @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'actions_taken', 'option_list' => 'actions_taken_options', 'value' => $log->actions_taken])
                              </td>
                              <td>
                                  @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'actioned_by', 'option_list' => 'actioned_by_options', 'value' => $log->actioned_by])
                              </td>
                              <td>
                                  @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'callcentre_comments', 'option_list' => 'callcentre_comments_options', 'value' => $log->callcentre_comments])
                              </td>
                              <td><input class="form-control" type="number" name="pax_figure_for_disrupted_flt" value="{{ $log->pax_figure_for_disrupted_flt }}"></td>
                              <td><input class="form-control" type="number" name="pax_figure_for_connecting_pax" value="{{ $log->pax_figure_for_connecting_pax }}"></td>
                              <td >
                                  <button type="button" class="btn btn-success btn-xs me-2 update-btn" data-id="{{ $log->id }}">Update</button>
                                  <button type="button" class="btn btn-danger btn-xs delete-btn" data-id="{{ $log->id }}">Delete</button>
                              </td>
                      </tr>
                  @endforeach

                  <!-- Row for adding a new record -->
                  <tr id="form-tr" class="bg-warning">
                      <form id="add-form">
                          @csrf
                          <td><input class="form-control" type="date" name="action_date"></td>
                          <td><input class="form-control" type="time" name="time_sent_by_crc"></td>
                          <td></td> <!-- No log number for new record -->
                          <td>
                                  @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'requested_by', 'option_list' => 'requested_by_options'])
                          </td>
                          <td><input class="form-control" type="date" name="flight_date_start"></td>
                          <td><input class="form-control" type="date" name="flight_date_end"></td>
                          <td><input class="form-control" type="text" name="days_of_week"></td>
                          <td>
                              @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'flight_number', 'option_list' => 'flight_number_options'])
                          </td>
                          <td><input class="form-control" type="time" name="old_flight_time"></td>
                          <td>
                              @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'old_flight_route', 'option_list' => 'old_flight_route_options'])
                          </td>
                          <td><input class="form-control" type="text" name="new_flight_number"></td>
                          <td><input class="form-control" type="time" name="new_flight_time"></td>
                          <td>
                              @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'disruption_status', 'option_list' => 'disruption_status_options'])

                          </td>
                          <td>@include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'reason_for_disruption', 'option_list' => 'reason_for_disruption_options'])
                          </td>
                          <td>
                              @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'actions_taken', 'option_list' => 'actions_taken_options'])
                          </td>
                          <td>
                              @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'actioned_by', 'option_list' => 'actioned_by_options'])
                          </td>
                          <td>
                              @include('frontend.flight_disruption._select-dropdowns', ['field_name' => 'callcentre_comments', 'option_list' => 'callcentre_comments_options'])
                          </td>
                          <td><input class="form-control" type="number" name="pax_figure_for_disrupted_flt"></td>
                          <td><input class="form-control" type="number" name="pax_figure_for_connecting_pax"></td>
                          <td>
                              <button type="button" class="btn btn-primary add-btn" id="submit-btn">Submit</button>
                          </td>
                      </form>
                  </tr>
                  <tr>
                      <th>Action Date</th>
                      <th>Time Sent by CRC</th>
                      <th class="sticky-column">Log Number</th>
                      <th>Requested By</th>
                      <th>Flight Date Start</th>
                      <th>Flight Date End</th>
                      <th>Days of Week</th>
                      <th>Flight Number</th>
                      <th>Old Flight Time</th>
                      <th>Old Flight Route</th>
                      <th>New Flight Number</th>
                      <th>New Flight Time</th>
                      <th>Disruption Status</th>
                      <th>Reason for Disruption</th>
                      <th>Actions Taken</th>
                      <th>Actioned By</th>
                      <th>Callcentre Comments</th>
                      <th>Pax figure for disrupted flights</th>
                      <th>Pax figure for connecting flights</th>
                      <th>Actions</th>
                  </tr>
                  </tbody>
              </table>
          </div>
            @if(1 < 0)
            <div class="card-footer">
                <div class="row justify-content-start">
                <div class="form-group">
                    <input type="text" class="form-control-lg" id="pasteData">
                </div>
                <button type="button" class="btn btn-primary btn-md" id="processDataBtn">Process Pasted Data</button>
                </div>
            </div>
                @endif
        </div>

        {{ $logs->links() }}
    </div>
    </div>
    </div>

        @endsection

        @push('after-scripts')
            <script>
                // Add event listeners to all 'update' buttons
                document.querySelectorAll('.update-btn').forEach(button => {
                    button.addEventListener('click', function() {
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

                        fetch('{{ route('flight_disruption.update') }}', {
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
                    button.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const row = this.closest('tr'); // Get the row associated with the delete button

                        if (confirm('Are you sure you want to delete this record?')) {
                            fetch('{{ url('api/flight_disruption') }}/' + id, {
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


                document.querySelector('.add-btn').addEventListener('click', function() {
                    const form = document.querySelector('#add-form');
                    $('#submit-btn').html('Processing...').addClass('disabled');

                    const formData = new FormData(form);

                    fetch('{{ route('frontend.flight_disruption.store') }}', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) { // Assuming the response contains a success flag
                                const tableBody = document.getElementById('flight-logs-tbody'); // Adjust selector to match your table
                                const newRow = document.createElement('tr'); // Create a new row
                                const newLog = data.new_log; // Accessing the new log data from the server response

                                // Add the form elements in the new row
                                newRow.innerHTML = `


    <td>
     @csrf
    <input type="hidden" name="log_id" value="${newLog.id}">
    <input class="form-control" type="date" name="action_date" value="${newLog.action_date}"></td>
    <td><input class="form-control" type="time" name="time_sent_by_crc" value="${newLog.time_sent_by_crc}"></td>
    <td class="sticky-column">${newLog.log_number}</td>
    <td>
        <select class="form-control" name="requested_by">
            <option selected value="${newLog.requested_by}">${newLog.requested_by}</option>
            @include('frontend.flight_disruption._select-dropdowns-for-js', ['field_name' => 'requested_by', 'option_list' => 'requested_by_options'])
                                </select>
                            </td>
                            <td><input class="form-control" type="date" name="flight_date_start" value="${newLog.flight_date_start}"></td>
    <td><input class="form-control" type="date" name="flight_date_end" value="${newLog.flight_date_end}"></td>
    <td><input class="form-control" type="number" name="days_of_week" value="${newLog.days_of_week}"></td>
    <td>
        <select class="form-control" name="flight_number">
            <option selected value="${newLog.flight_number}">${newLog.flight_number}</option>
            @include('frontend.flight_disruption._select-dropdowns-for-js', ['field_name' => 'flight_number', 'option_list' => 'flight_number_options'])
                                </select>
                            </td>
                            <td><input class="form-control" type="time" name="old_flight_time" value="${newLog.old_flight_time}"></td>
    <td>
        <select class="form-control" name="old_flight_route">
            <option selected value="${newLog.old_flight_route}">${newLog.old_flight_route}</option>
            @include('frontend.flight_disruption._select-dropdowns-for-js', ['field_name' => 'old_flight_route', 'option_list' => 'old_flight_route_options'])
                                </select>
                            </td>
                            <td><input class="form-control" type="text" name="new_flight_number" value="${newLog.new_flight_number}"></td>
    <td><input class="form-control" type="time" name="new_flight_time" value="${newLog.new_flight_time}"></td>
    <td>
        <select class="form-control" name="disruption_status">
            <option selected value="${newLog.disruption_status}">${newLog.disruption_status}</option>
            @include('frontend.flight_disruption._select-dropdowns-for-js', ['field_name' => 'disruption_status', 'option_list' => 'disruption_status_options'])
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="reason_for_disruption">
                                    <option selected value="${newLog.reason_for_disruption}">${newLog.reason_for_disruption}</option>
            @include('frontend.flight_disruption._select-dropdowns-for-js', ['field_name' => 'reason_for_disruption', 'option_list' => 'reason_for_disruption_options'])
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="actions_taken">
                                    <option selected value="${newLog.actions_taken}">${newLog.actions_taken}</option>
            @include('frontend.flight_disruption._select-dropdowns-for-js', ['field_name' => 'actions_taken', 'option_list' => 'actions_taken_options'])
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="actioned_by">
                                    <option selected value="${newLog.actioned_by}">${newLog.actioned_by}</option>
            @include('frontend.flight_disruption._select-dropdowns-for-js', ['field_name' => 'actioned_by', 'option_list' => 'actioned_by_options'])
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="callcentre_comments">
                                    <option selected value="${newLog.callcentre_comments}">${newLog.callcentre_comments}</option>
            @include('frontend.flight_disruption._select-dropdowns-for-js', ['field_name' => 'callcentre_comments', 'option_list' => 'callcentre_comments_options'])
                                </select>
                            </td>
                          <td><input class="form-control" type="number" name="pax_figure_for_disrupted_flt" value="${newLog.pax_figure_for_disrupted_flt}"></td>
                          <td><input class="form-control" type="number" name="pax_figure_for_connecting_pax" value="${newLog.pax_figure_for_connecting_pax}"></td>
                            <td>
                                <button type="button" class="btn btn-success btn-xs me-2 update-btn" data-id="${newLog.id}">Update</button>
        <button type="button" class="btn btn-danger btn-xs delete-btn" data-id="${newLog.id}">Delete</button>
    </td>`;
                                newRow.id = 'trow-' + newLog.id;
                                // Insert the new row just before the form row
                                tableBody.insertBefore(newRow, document.querySelector('#form-tr'));
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
                                const response = await fetch(`{{ route('flight_disruption.update', ':id') }}`.replace(':id', id), {
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
                                    const response = await fetch(`{{ url('api/flight_disruption') }}/${id}`, {
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
                        console.log('Pasted data: ' + pastedData);
                        // Split the data by tab character (assuming tab-separated values from Excel)
                        var dataArray = pastedData.split('\t');

                        // Populate the form fields with the data
                        var formFields = $('#add-form').find('input');
                        formFields.each(function(index, field) {
                            if (dataArray[index]) {
                                $(field).val(dataArray[index]);
                            }
                        });

                        // Clear the input field
                        // $('#pasteData').val('');
                    });
                });
            </script>
@endpush


