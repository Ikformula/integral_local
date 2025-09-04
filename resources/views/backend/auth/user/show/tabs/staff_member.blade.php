<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>ARA ID</th>
                <td>{{ $user->staff_member->staff_ara_id }}</td>
            </tr>

            <tr>
                <th>Surname</th>
                <td>{{ $user->staff_member->surname }}</td>
            </tr>

            <tr>
                <th>Other Name</th>
                <td>{{ $user->staff_member->other_names }}</td>
            </tr>

            <tr>
                <th>Department</th>
                <td>{{ $user->staff_member->department_name }}</td>
            </tr>

            <tr>
                <th>Paypoint</th>
                <td>{{ $user->staff_member->paypoint }}</td>
            </tr>

            <tr>
                <th>Job Title</th>
                <td>{{ $user->staff_member->job_title }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $user->staff_member->email }}</td>
            </tr>

        </table>
    </div>
</div><!--table-responsive-->
