<table id="staff-table" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Status</th>
        <th>ID No</th>
        <th>Surname</th>
        <th>Other Names</th>
        <th>Department</th>
        <th>Location</th>
        <th>Job Title</th>
        <th>Grade</th>
        <th>Gross Pay (Monthly)</th>
        <th>Staff Cadre</th>
        <th>Nationality</th>
        <th>Staff Category</th>
        <th>Gender</th>
        <th>Join Date</th>
        <th>Years of Service</th>
        <th>Current Employment Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($allStaff as $employee)
        <tr>
            <td>{{ $employee->status }}</td>
            <td>{{ $employee->id_no }}</td>
            <td>{{ $employee->surname }}</td>
            <td>{{ $employee->other_names }}</td>
            <td>{{ $employee->department_name }}</td>
            <td>{{ $employee->location }}</td>
            <td>{{ $employee->job_title }}</td>
            <td>{{ $employee->grade }}</td>
            <td>{{ number_format($employee->gross_pay_monthly, 2) }}</td>
            <td>{{ $employee->staff_cadre }}</td>
            <td>{{ $employee->nationality }}</td>
            <td>{{ $employee->staff_category }}</td>
            <td>{{ $employee->gender }}</td>
            <td>{{ $employee->join_date }}</td>
            <td>{{ $employee->years_of_service }}</td>
            <td>{{ $employee->current_employment_status }}</td>
            <td>
                <button class="btn btn-sm btn-info" hx-get="{{ route('frontend.user.staff.edit', $employee) }}" hx-target="#modal-content">
                    Edit
                </button>
                <button class="btn btn-sm btn-danger"
                        hx-delete="{{ route('frontend.user.staff.destroy', $employee) }}"
                        hx-confirm="Are you sure you want to delete this staff member?"
                        hx-target="#staff-table"
                        hx-swap="outerHTML">
                    Delete
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
