
<div class="card bg-gradient-primary arik-card animate__animated animate__backInUp">
    <div class="card-header">
        <h3 class="card-title">
            @canany (['manage own unit info', 'update other staff info'])
                Confirm Staff Member's Details
            @else
                Confirm Your Details
            @endcanany
        </h3>
    </div>
    <div class="card-body p-0">
        @canany (['manage own unit info', 'update other staff info'])
            <form action="{{ route('frontend.user.profile.editIDcard') }}" class="m-3" method="GET">
                <label>Staff ARA Number</label>
                <div class="input-group mb-3">
                    <input type="search" name="staff_ara_id" id="staff_ara_id"
                           value="{{ $_GET['staff_ara_id'] }}" class="form-control">
                    <div class="input-group-append">
                        <button type="submit" class="btn bg-maroon">Search</button>
                    </div>
                </div>
            </form>
        @endcanany

        <table class="table">
            <tbody>
            <tr>
                <th>Name:</th>
                <td>{{ $staff->name }}</td>
            </tr>

            <tr>
                <th>ARA Number:</th>
                <td>{{ $staff->staff_ara_id }}</td>
            </tr>

            <tr>
                <th>Designation:</th>
                <td>{{ $staff->job_title }}</td>
            </tr>
            <tr>
                <th>Department:</th>
                <td>{{ $staff->department_name }}</td>
            </tr>
            <tr>
                <th>Paypoint:</th>
                <td>{{ $staff->paypoint }}</td>
            </tr>

            @if(isset($staff->location))
                <tr>
                    <th>Location:</th>
                    <td>{{ $staff->location }}</td>
                </tr>
            @endif

            @if(!is_null($staff->id_card_file_name))
                <tr>
                    <th>Location at HQ:</th>
                    <td>{{ $staff->location_in_hq ?? '' }}</td>
                </tr>
                <tr>
                    <th>ID Card Expiry Date:</th>
                    <td>{{ $staff->id_expiry_date ?? '' }}</td>
                </tr>
            @endif

            </tbody>
        </table>

    </div>
</div>
