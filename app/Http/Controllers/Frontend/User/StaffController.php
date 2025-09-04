<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $allStaff = Staff::all();
        return view('frontend.staff-special.index', compact('allStaff'));
    }

    public function create()
    {
        return view('frontend.staff-special.edit');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|max:50',
            'id_no' => 'required|string|max:50|unique:staff',
            'surname' => 'required|string|max:100',
            'other_names' => 'required|string|max:100',
            'department_name' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'job_title' => 'required|string|max:100',
            'grade' => 'nullable|string|max:50',
            'location_2' => 'nullable|string|max:100',
            'gross_pay_monthly' => 'required|numeric',
            'staff_cadre' => 'required|string|max:50',
            'nationality' => 'required|string|max:50',
            'staff_category' => 'required|string|max:50',
            'gender' => 'required|string|max:10',
            'join_date' => 'required|date',
            'end_date' => 'nullable|date',
            'years_of_service' => 'required|numeric',
            'rounded_up_years' => 'required|integer',
            'in_lieu' => 'nullable|numeric',
            'one_month_gross_feyw' => 'required|numeric',
            'redundancy_pay' => 'required|numeric',
            'total_severance' => 'required|numeric',
            'ext_till' => 'nullable|date',
            'current_employment_status' => 'required|string|max:50',
            'effective_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $staff = Staff::create($validatedData);

        $allStaff = Staff::all();
        return view('frontend.staff-special.table', compact('allStaff'));
    }

    public function edit(Staff $staff)
    {
        return view('frontend.staff-special.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|max:50',
            'id_no' => 'required|string|max:50|unique:staff,id_no,' . $staff->id,
            'surname' => 'required|string|max:100',
            'other_names' => 'required|string|max:100',
            'department_name' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'job_title' => 'required|string|max:100',
            'grade' => 'nullable|string|max:50',
            'location_2' => 'nullable|string|max:100',
            'gross_pay_monthly' => 'required|numeric',
            'staff_cadre' => 'required|string|max:50',
            'nationality' => 'required|string|max:50',
            'staff_category' => 'required|string|max:50',
            'gender' => 'required|string|max:10',
            'join_date' => 'required|date',
            'end_date' => 'nullable|date',
            'years_of_service' => 'nullable|numeric',
            'rounded_up_years' => 'nullable|integer',
            'in_lieu' => 'required|numeric',
            'one_month_gross_feyw' => 'required|numeric',
            'redundancy_pay' => 'required|numeric',
            'total_severance' => 'required|numeric',
            'ext_till' => 'nullable|date',
            'current_employment_status' => 'required|string|max:50',
            'effective_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $staff->update($validatedData);

        $allStaff = Staff::all();
        return view('frontend.staff-special.table', compact('allStaff'));
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        $allStaff = Staff::all();
        return view('frontend.staff-special.table', compact('allStaff'));
    }
}
