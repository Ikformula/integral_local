<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ManagerAbsenceLatenessAuthorization;
use App\Models\StaffMember;
use Illuminate\Http\Request;
use App\Models\StaffAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;

class StaffAttendanceAuthorizationsController extends Controller
{
    use OutgoingMessagesTrait;

    public function userCreatedExemptions()
    {
        $auth_user = auth()->user();
        $staff = $auth_user->staff_member;
        if(!$staff){
            return back()->withFlashWarning('No staff account attached');
        }

        $authorizations = ManagerAbsenceLatenessAuthorization::where('manager_ara_id', $staff->staff_ara_id)->latest()->get();

        return view('frontend.staff_attendance.staff_attendance_exemptions_management')->with(['authorizations' => $authorizations]);

    }

    public function staffUnderMe()
    {
        $auth_user = auth()->user();
        $staff = $auth_user->staff_member;
        if(!$staff){
            return back()->withFlashWarning('No staff account attached');
        }

        $staff_members = StaffMember::where('manager_ara_id', $staff->staff_ara_id)->get();

        if(!$staff_members->count())
            return redirect()->route('frontend.user.dashboard')->withErrors('No staff found under your management');

        return view('frontend.staff_attendance.staff_under_management')->with([
            'staff_members' => $staff_members
        ]);
    }

    public function createExemption(Request $request)
    {
        $auth_user = auth()->user();
        $staff = $auth_user->staff_member;
        if(!$staff){
            return back()->withFlashWarning('No staff account attached');
        }

        if($request->filled('staff_ara_id')){
            $staff_members = StaffMember::where('manager_ara_id', $staff->staff_ara_id)
                ->where('staff_ara_id', $request->staff_ara_id)
                ->get();
        }else {
            $staff_members = StaffMember::where('manager_ara_id', $staff->staff_ara_id)->get();
        }

        if(!$staff_members->count())
            return redirect()->route('frontend.user.dashboard')->withErrors('No staff under your management found');
        return view('frontend.staff_attendance.create_staff_attendance_exemption')->with([
           'staff_members' => $staff_members,
            'own_staff_details' => $staff
        ]);
    }

    public function storeExemption(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required'],
            'end_date' => ['nullable', 'after:start_date']
        ]);

        $exemption = new ManagerAbsenceLatenessAuthorization();
        $exemption->staff_ara_id = $request->staff_ara_id;
        $exemption->manager_ara_id = $request->manager_ara_id;
        $exemption->start_date = $request->start_date;
        $exemption->end_date = $request->end_date;
        $exemption->is_indefinite = $request->is_indefinite;
        $exemption->reason = $request->reason;
        $exemption->notes = $request->notes;
        $exemption->save();

        return redirect()->route('frontend.attendance.managed.authorizations')->withFlashInfo('Authorization saved');
    }

}
