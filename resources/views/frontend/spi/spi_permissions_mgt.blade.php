@extends('frontend.layouts.app')

@section('title',  'SRB Users')
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">SPI Sector Permissions Management</h4>
                    </div>
                    <div class="card-body">
                        <!-- Add Permission Form -->
                        <form id="addPermissionForm" class="mb-4">
                            @csrf
                            <div class="row">
                                <div class="col-md-5">
                                    <select name="user_id" class="form-control" required id="user-select">
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->full_name }}
                                                , {{ isset($user->staff_member) ? 'ARA'.$user->staff_member->staff_ara_id. ' | '.$user->staff_member->department_name : ''}}
                                                ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select name="sector_id" class="form-control" required>
                                        <option value="">Select Sector</option>
                                        @foreach($sectors as $sector)
                                            <option value="{{ $sector->id }}">{{ $sector->sector_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Add Permission</button>
                                </div>
                            </div>
                        </form>


                        <!-- Permissions Table -->
                        <div class="table-responsive">
                            <table id="permissions-table" class="table table-bordered table-striped w-100">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>ARA ID</th>
                                    <th>Department</th>
                                    <th>Sector</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="permissions-tbody">
                                @foreach($permissions as $permission)
                                    @php
                                    $staff_member = isset($permission->user->staff_member) ? $permission->user->staff_member : null;
                                    @endphp
                                    <tr data-id="{{ $permission->id }}">
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->user->name }} ({{ $permission->user->email }})</td>
                                        <td>{{ isset($staff_member) ? 'ARA'.$staff_member->staff_ara_id : ''  }}</td>
                                        <td>{{ isset($staff_member) ? $staff_member->department_name : ''  }}</td>
                                        <td>
                                            <span class="sector-name">{{ $permission->sector->sector_name }}</span>
                                            <select class="form-control sector-edit">
                                                @foreach($sectors as $sector)
                                                    <option value="{{ $sector->id }}"
                                                        {{ $sector->id == $permission->sector_id ? 'selected' : '' }}>
                                                        {{ $sector->sector_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>{{ $permission->updated_at }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-btn">Update</button>
{{--                                            <button class="btn btn-sm btn-success save-btn" style="display: none;">--}}
{{--                                                Save--}}
{{--                                            </button>--}}
                                            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                                        </td>
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
@endsection

@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('#user-select').select2({
            theme: 'bootstrap4'
        });
    </script>


    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize DataTable
        const table = new DataTable('.table', {
            paging: false,
        });

        // Add Permission
        document.getElementById('addPermissionForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route("frontend.safety_review.permissions.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let newRowHtml = addPermissionRow(data);
                        table.row.add($(newRowHtml)).draw();
                        showInstantToast('Permission added successfully', 'success');
                    } else {
                        showInstantToast(data.message || 'Error adding permission', 'danger');
                    }
                });
        });

        // Use delegated event listeners for dynamically added elements
        const tableContainer = document.querySelector('#permissions-table');

        // Edit button click
        tableContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('edit-btn')) {
                const row = e.target.closest('tr');
                const id = row.dataset.id;
                const sectorEdit = row.querySelector('.sector-edit');
                const sectorId = sectorEdit.value;

                fetch(`{{ url('safety_review/permissions') }}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ sector_id: sectorId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const updatedRowHtml = updatePermissionRow(data);
                            table.row(row).data($(updatedRowHtml)).draw();
                            showInstantToast('Permission updated successfully', 'success');
                        } else {
                            showInstantToast(data.message || 'Error updating permission', 'danger');
                        }
                    });
            }
        });

        // Delete button click
        tableContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('delete-btn')) {
                if (!confirm('Are you sure you want to delete this permission?')) return;

                const row = e.target.closest('tr');
                const id = row.dataset.id;

                fetch(`{{ url('safety_review/permissions') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            table.row(row).remove().draw();
                            showInstantToast('Permission deleted successfully', 'warning');
                        } else {
                            showInstantToast(data.message || 'Error deleting permission', 'danger');
                        }
                    });
            }
        });
    });


    function addPermissionRow(data){
        let permission = data.data;
        let user = data.user;
        let staff_member = data.staff;
        let sector = data.sector;
        return `
                        <tr data-id="${permission.id}">
                            <td> ${permission.id} </td>
                            <td> ${user.first_name} ${user.last_name}  ( ${user.email} )</td>
                            <td> ${staff_member ? 'ARA' + staff_member.staff_ara_id : ''}  </td>
                            <td> ${staff_member ? staff_member.department_name : ''}  </td>
                            <td>
                                            <span class="sector-name">${sector.sector_name }</span>
                        <select class="form-control sector-edit">
        @foreach($sectors as $sector)
        <option value="{{ $sector->id }}"
                    ${ {{$sector->id}} == permission.sector_id ? 'selected' : '' }>
                    {{ $sector->sector_name }}
        </option>
@endforeach
        </select>
    </td>
    <td> ${permission.updated_at} </td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn">Update</button>
                        <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                    </td>
                </tr>
                `;
    }

    function updatePermissionRow(data) {
        let permission = data.data;
        let user = data.user;
        let staff_member = data.staff;
        let sector = data.sector;

        return [
            permission.id,
            `${user.first_name} ${user.last_name} (${user.email})`,
            staff_member ? 'ARA' + staff_member.staff_ara_id : '',
            staff_member ? staff_member.department_name : '',
            `<span class="sector-name">${sector.sector_name}</span>
         <select class="form-control sector-edit">
            @foreach($sectors as $sector)
            <option value="{{ $sector->id }}" ${ {{$sector->id}} == permission.sector_id ? 'selected' : ''}>
                    {{ $sector->sector_name }}
            </option>
@endforeach
            </select>`,
            permission.updated_at,
            `<button class="btn btn-sm btn-warning edit-btn">Update</button>
         <button class="btn btn-sm btn-danger delete-btn">Delete</button>`
        ];
    }
</script>
@endpush
