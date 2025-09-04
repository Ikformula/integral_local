<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\HrAdvisor;
use App\Models\StaffMember;
use App\Models\StbRegistrationWindow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;

class StbHrController extends Controller
{
    use OutgoingMessagesTrait;

    protected $departments = [
        'ADMINISTRATION',
        'CABIN OPERATIONS',
        'CEO OFFICE',
        'COMMERCIAL',
        'CORPORATE SERVICES',
        'FINANCE',
        'FLIGHT OPERATIONS',
        'GROUND OPERATIONS',
        'HR',
        'INFORMATION TECHNOLOGY',
        'INTERNAL CONTROL',
        'OCC',
        'QUALITY ASSURANCE',
        'SECURITY',
        'TECHNICAL'
    ];

    public function index()
    {
        $hr_advisors = HrAdvisor::all();
        $stb_reg_windows = StbRegistrationWindow::latest()->take(10)->get();
        $staff_members = StaffMember::where('department_name', 'HR')->get();
        $now = now();
        $departments = $this->departments;
        $current_window = $stb_reg_windows->whereNull('closed_at')->first();
        $now_in_current_window = $current_window->from_date <= $now && $now < $current_window->to_date ? true : false;
        return view('frontend.staff_travel.hr_mgt', compact('hr_advisors', 'stb_reg_windows', 'now', 'staff_members', 'departments', 'current_window', 'now_in_current_window'));
    }

    public function storeWindow(Request $request)
    {
        $request->validate([
           'from_date' => ['required'],
           'to_date' => ['required', 'after:from_date']
        ]);
        $user = auth()->user();
        $now = now();
        $open_windows = StbRegistrationWindow::whereNull('closed_at')->get();
        foreach ($open_windows as $window){
            $window->remarks = $window->remarks.' -- Batch Closed at '.$now->toDateTimeString().' by '.$user->full_name;
            $window->closed_at = $now;
            $window->save();
        }

        $arr['window_year'] = Carbon::parse($request->to_date)->year;
        $arr['set_by_user_id'] = $user->id;
        $arr['staff_ara_id'] = $user->staff_member ? $user->staff_member->staff_ara_id : null;

        $window = StbRegistrationWindow::create(array_merge($arr, $request->all()));
        return back()->withFlashSuccess('Window stored');
    }

    public function closeWindow(Request $request, StbRegistrationWindow $stbRegistrationWindow)
    {
        $now = now();
        $stbRegistrationWindow->remarks = $request->remarks.' -- Force Closed at '.$now->toDateTimeString().' by '.auth()->user()->full_name;
        $stbRegistrationWindow->closed_at = $now;
        $stbRegistrationWindow->save();

        return back()->withFlashSuccess('Window closed');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string',
            'staff_ara_id' => 'required|exists:staff_member_details,staff_ara_id',
        ]);

        if(!in_array($request->department_name, $this->departments))
            return back()->withErrors('Invalid Department Name');

        try {
            // Check if permission already exists
            $exists = HrAdvisor::where('staff_ara_id', $request->staff_ara_id)
                ->where('department_name', $request->department_name)
                ->exists();

            if ($exists) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Assignment already done for this advisor and department'
//                ], 422);
                return redirect()->back()->withErrors('Assignment already done for this advisor and department');
            }

            $arr['set_by_user_id'] = auth()->id();
            $permission = HrAdvisor::create(array_merge($arr, $request->all()));

            $staff = StaffMember::where('staff_ara_id', $request->staff_ara_id)->first();
            if($staff && $staff->user) {
                $user = $staff->user;
                $user->assignRole('hr advisor');
            }

//            return response()->json([
//                'success' => true,
//                'message' => 'HR Advisor assigned successfully',
//                'data' => $permission,
//                'department_name' => $permission->department_name,
//                'user' => $user,
//                'staff' => $staff,
//                'staff_name_and_ara' => $staff->name_and_ara
//            ]);

            return redirect()->back()->withFlashSuccess('HR Advisor assigned successfully');
        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Error adding permission'
//            ], 500);

            return redirect()->back()->withErrors('Error adding permission');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department_name' => 'required|string',
        ]);

        if(!in_array($request->department_name, $this->departments))
            return back()->withErrors('Invalid Department Name');

        try {
            $permission = HrAdvisor::findOrFail($id);

            // Check if new permission combination already exists
            $exists = HrAdvisor::where('staff_ara_id', $permission->staff_ara_id)
                ->where('department_name', $request->department_name)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Permission already exists for this advisor and department'
//                ], 422);
                return redirect()->back()->withErrors('Assignment already done for this advisor and department');
            }

            $permission->update([
                'department_name' => $request->department_name,
                'set_by_user_id' => auth()->id()
            ]);

            $staff = $permission->staff_member;
            $user = $staff->user;
            if($user)
                $user->assignRole('hr advisor');

//            return response()->json([
//                'success' => true,
//                'message' => 'Permission updated successfully',
//                'data' => $permission,
//                'department_name' => $permission->department_name,
//                'user' => $user,
//                'staff' => $staff
//            ]);
            return redirect()->back()->withFlashSuccess('Permission updated successfully');

        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Error updating permission'
//            ], 500);
            return redirect()->back()->withErrors('Assignment already done for this advisor and department');

        }
    }

    public function destroy($id)
    {
        try {
            $permission = HrAdvisor::findOrFail($id);
            $permission->delete();

//            return response()->json([
//                'success' => true,
//                'message' => 'Permission deleted successfully'
//            ]);
            return redirect()->back()->withFlashSuccess('Permission deleted successfully');
        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Error deleting permission'
//            ], 500);
            return redirect()->back()->withErrors('Error deleting permission');
        }
    }

    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'email_body' => 'required'
        ]);
        $data['formatted_line'][] = $request->email_body;
        $data['action_text'] = "STB Beneficiaries";
        $data['action_url'] = route('frontend.staff_travel_beneficiaries.index');

        if ($request->filled('emails')) {
            $data['to'] = explode(',', (str_replace(' ', '', $request->emails)));
        }

        if ($request->filled('cc_emails')) {
            $data['cc'] = explode(',', (str_replace(' ', '', $request->cc_emails)));
        }

        if ($request->filled('bcc_emails')) {
            $data['bcc'] = explode(',', (str_replace(' ', '', $request->bcc_emails)));
        }

        $data['to_name'] = 'Team';

        if($request->filled('emails') || $request->filled('cc_emails') || $request->filled('bcc_emails'))
            $this->storeMessage($data, null);

        return redirect()->back()->withFlashSuccess('Email processed successfully');
    }
}
