<!-- staff/create.blade.php and staff/edit.blade.php -->
<div class="modal-header">
    <h5 class="modal-title" id="staff-modal-label">{{ isset($staff) ? 'Edit Staff' : 'Add Staff' }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<form hx-post="{{ isset($staff) ? route('frontend.user.staff.update', $staff) : route('frontend.user.staff.store') }}"
      hx-target="#staff-table"
      hx-swap="outerHTML"
      hx-trigger="submit">
    @csrf
    @if(isset($staff))
        @method('PUT')
    @endif
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" name="status" value="{{ $staff->status ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="id_no">ID No</label>
                    <input type="text" class="form-control" id="id_no" name="id_no" value="{{ $staff->id_no ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" class="form-control" id="surname" name="surname" value="{{ $staff->surname ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="other_names">Other Names</label>
                    <input type="text" class="form-control" id="other_names" name="other_names" value="{{ $staff->other_names ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="department_name">Department Name</label>
                    <input type="text" class="form-control" id="department_name" name="department_name" value="{{ $staff->department_name ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ $staff->location ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="job_title">Job Title</label>
                    <input type="text" class="form-control" id="job_title" name="job_title" value="{{ $staff->job_title ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="grade">Grade</label>
                    <input type="text" class="form-control" id="grade" name="grade" value="{{ $staff->grade ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="location_2">Location 2</label>
                    <input type="text" class="form-control" id="location_2" name="location_2" value="{{ $staff->location_2 ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="gross_pay_monthly">Gross Pay (Monthly)</label>
                    <input type="number" step="0.01" class="form-control" id="gross_pay_monthly" name="gross_pay_monthly" value="{{ $staff->gross_pay_monthly ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="staff_cadre">Staff Cadre</label>
                    <input type="text" class="form-control" id="staff_cadre" name="staff_cadre" value="{{ $staff->staff_cadre ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="nationality">Nationality</label>
                    <input type="text" class="form-control" id="nationality" name="nationality" value="{{ $staff->nationality ?? '' }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="staff_category">Staff Category</label>
                    <input type="text" class="form-control" id="staff_category" name="staff_category" value="{{ $staff->staff_category ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="Male" {{ (isset($staff) && $staff->gender == 'Male') ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ (isset($staff) && $staff->gender == 'Female') ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="join_date">Join Date</label>
                    <input type="date" class="form-control" id="join_date" name="join_date" value="{{ $staff->join_date ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $staff->end_date ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="years_of_service">Years of Service</label>
                    <input type="number" step="0.01" class="form-control" id="years_of_service" name="years_of_service" value="{{ $staff->years_of_service ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="rounded_up_years">Rounded Up Years</label>
                    <input type="number" class="form-control" id="rounded_up_years" name="rounded_up_years" value="{{ $staff->rounded_up_years ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="in_lieu">In Lieu</label>
                    <input type="number" step="0.01" class="form-control" id="in_lieu" name="in_lieu" value="{{ $staff->in_lieu ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="one_month_gross_feyw">One Month Gross FEYW</label>
                    <input type="number" step="0.01" class="form-control" id="one_month_gross_feyw" name="one_month_gross_feyw" value="{{ $staff->one_month_gross_feyw ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="redundancy_pay">Redundancy Pay</label>
                    <input type="number" step="0.01" class="form-control" id="redundancy_pay" name="redundancy_pay" value="{{ $staff->redundancy_pay ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="total_severance">Total Severance</label>
                    <input type="number" step="0.01" class="form-control" id="total_severance" name="total_severance" value="{{ $staff->total_severance ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="ext_till">Ext. Till</label>
                    <input type="date" class="form-control" id="ext_till" name="ext_till" value="{{ $staff->ext_till ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="current_employment_status">Current Employment Status</label>
                    <input type="text" class="form-control" id="current_employment_status" name="current_employment_status" value="{{ $staff->current_employment_status ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="effective_date">Effective Date</label>
                    <input type="date" class="form-control" id="effective_date" name="effective_date" value="{{ $staff->effective_date ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="reason">Reason</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3">{{ $staff->reason ?? '' }}</textarea>
                </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" hx-confirm="Are you sure you want to save these changes?">Save</button>
    </div>
</form>
