@extends('frontend.layouts.app')

@section('title',  'CUG Users')
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Add New Record Form -->
        <div class="row mb-4">
            <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New CUG Line</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="addForm">
                        <div class="form-group">
                            <label for="staff_ara_id">Staff Member</label>
                            <select name="staff_ara_id" id="staff_ara_id" class="form-control" required>
                                <option value="" disabled selected>Select Staff Member</option>
                                @foreach ($staffMembers as $staff)
                                    <option value="{{ $staff->staffMember_ara_id }}">
                                        {{ $staff->surname }} {{ $staff->other_names }} - ARA{{ $staff->staffMember_ara_id }} ({{ $staff->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" maxlength="25" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="service_provider">Service Provider</label>
                            <input type="text" name="service_provider" id="service_provider" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="phone_type">Phone Type</label>
                            <select name="phone_type" id="phone_type" class="form-control" required>
                                <option value="feature phone">Feature Phone</option>
                                <option value="smartphone">Smartphone</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone_model">Phone Model</label>
                            <input type="text" name="phone_model" id="phone_model" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add</button>
                    </form>
                </div>
            </div>
            </div>
        </div>

        <!-- Existing Records Table -->
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-header">CUG Lines</div>
                <div class="card-body">
                    <table id="cugLinesTable" class="table table-bordered nowrap w-100">
                        <thead>
                        <tr>
                            <th>Staff Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Phone Number</th>
                            <th>Phone Type</th>
                            <th>Phone Model</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
{{--                        @foreach ($cugLines as $line)--}}
{{--                            <tr data-id="{{ $line->id }}">--}}
{{--                                @if($line->staffMember)--}}
{{--                                <td>{{ $line->staffMember->full_name }}</td>--}}
{{--                                <td>{{ $line->staffMember->email }}</td>--}}
{{--                                <td>{{ $line->staffMember->department_name }}</td>--}}
{{--                                    @else--}}
{{--                                    <td></td>--}}
{{--                                    <td></td>--}}
{{--                                    <td></td>--}}
{{--                                    <td></td>--}}
{{--                                @endif--}}
{{--                                <td>--}}
{{--                                    <input type="text" name="phone_number" class="form-control" value="{{ $line->phone_number }}">--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <select name="phone_type" class="form-control">--}}
{{--                                        <option value="feature phone" {{ $line->phone_type == 'feature phone' ? 'selected' : '' }}>Feature Phone</option>--}}
{{--                                        <option value="smartphone" {{ $line->phone_type == 'smartphone' ? 'selected' : '' }}>Smartphone</option>--}}
{{--                                    </select>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <input type="text" name="phone_model" class="form-control" value="{{ $line->phone_model }}">--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <button class="btn btn-success btn-sm btn-update">Update</button>--}}
{{--                                    <button class="btn btn-danger btn-sm btn-delete">Delete</button>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // const table = $('#cugLinesTable').DataTable();
            let table = $('#cugLinesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("frontend.cug_lines.cugLinesJson") }}',
                columns: [
                    { data: 'staff_name', name: 'staff_name' }, // Combines surname and other_names
                    { data: 'email', name: 'email' },
                    { data: 'department_name', name: 'department_name' },
                    { data: 'phone_number', name: 'phone_number' },
                    { data: 'phone_type', name: 'phone_type' },
                    { data: 'phone_model', name: 'phone_model' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
            });



            // Add record
            $('#addForm').on('submit', function (e) {
                e.preventDefault();

                fetch('{{ route("frontend.cug_lines.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(Object.fromEntries(new FormData(this))),
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.message) {
                            showInstantToast(data.message, 'success');
                        } else {
                            showInstantToast('Unexpected response from the server.', 'info');
                        }
                        table.ajax.reload();
                    })
                    .catch(error => {
                        console.error('Error occurred:', error);
                        showInstantToast('An error occurred while processing your request. Please try again.', 'danger');
                    });
            });


            // Update record
            $('#cugLinesTable').on('click', '.btn-update', function () {
                const row = $(this).closest('tr');
                const id = $(this).data('id');
                const data = {
                    phone_number: row.find('input[name="phone_number"]').val(),
                    phone_type: row.find('select[name="phone_type"]').val(),
                    phone_model: row.find('input[name="phone_model"]').val(),
                };

                fetch(`{{ url('cug-lines') }}/${id}/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.message) {
                            showInstantToast(data.message, 'success');
                        } else {
                            showInstantToast('Unexpected response from the server.', 'info');
                        }
                        table.ajax.reload();
                    })
                    .catch(error => {
                        console.error('Error occurred:', error);
                        showInstantToast('An error occurred while updating the record. Please try again.', 'danger');
                    });
            });


            // Delete record
            $('#cugLinesTable').on('click', '.btn-delete', function () {
                const row = $(this).closest('tr');
                const id = $(this).data('id');

                fetch(`{{ url('cug-lines') }}/${id}/delete`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.message) {
                            showInstantToast(data.message, 'success');
                        } else {
                            showInstantToast('Unexpected response from the server.', 'info');
                        }
                        table.ajax.reload();
                    })
                    .catch(error => {
                        console.error('Error occurred:', error);
                        showInstantToast('An error occurred while deleting the record. Please try again.', 'danger');
                    });
            });

        });
    </script>
@endpush
