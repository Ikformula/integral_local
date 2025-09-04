<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\GoogleWorkspaceUser;
use App\Models\StaffMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffManagementController extends Controller
{
    public function emailFix()
    {
        $staff_members = StaffMember::whereNull('email')->orderBy('other_names', 'ASC')->get();
            $close_matching_emails = [];
        // Retrieve all Google Workspace Users
        $googleUsers = DB::table('google_workspace_users')
            ->select('id', 'email', 'first_name', 'last_name')
            ->whereNull('email_user_staff')
            ->get()
            ->keyBy('id');

            foreach ($googleUsers as $googleUser) {
                $closest_matching_staffs = StaffMember::where('other_names', 'LIKE', '%' . $googleUser->first_name . '%')
                    ->orWhere('other_names', 'LIKE', '%' . $googleUser->last_name . '%')
                    ->orWhere('surname', 'LIKE', '%' . $googleUser->last_name . '%')
                    ->orWhere('surname', 'LIKE', '%' . $googleUser->first_name . '%')
                    ->get();

                $bestMatch = null;
                $bestScore = 0;

                foreach ($closest_matching_staffs as $match) {
                    // Combine both names for comparison
                    $full_name = ($match->surname ? $match->surname . ' ' : '') . $match->other_names;

                    // Calculate similarity score based on occurrences of first and last names
                    $score = similar_text($googleUser->first_name . ' ' . $googleUser->last_name, $full_name, $percent);

                    // Update best match if current score is higher
                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $bestMatch = $match;
                    }
                }

                if ($bestMatch) {
                    $close_matching_emails[$bestMatch->staff_ara_id] = $googleUser->email;
                }
            }


        return view('frontend.staff_management.emails')->with([
            'staff_members' => $staff_members,
            'close_matching_emails' => $close_matching_emails
        ]);
    }

    public function storeEmail(Request $request)
    {
//        $validated = $request->validate([
//            'id' => ['required', 'numeric'],
//            'staff_ara_id' => ['required', 'string', 'max:5'],
//           'email' => ['required', 'email'],
//        ]);

        DB::table('staff_member_details')
            ->where('id', $request->id)
            ->update([
                'email' => $request->email
            ]);

        DB::table('google_workspace_users')
            ->where('email', $request->email)
            ->update([
               'email_user_staff' => $request->staff_ara_id
            ]);

        return [
            'status' => 'success',
            'message' => $request->staff_ara_id .' updated with email: '.$request->email
        ];
    }

    public function findEmailUserStaff()
    {
        $google_workspace_users = DB::table('google_workspace_users')
            ->where('email_user_staff', null)
            ->get();
        $staff_members = StaffMember::where('email', '!=', null);
        foreach($google_workspace_users as $user){
            $matched_staff_member[$user->id] = $staff_members->where('email', $user->email)->get();
            if($matched_staff_member[$user->id]->count()){
                $staff_member[$user->id] = $matched_staff_member[$user->id]->first();
                DB::table('google_workspace_users')
                    ->where('email', $user->email)
                    ->update([
                        'email_user_staff' => $staff_member[$user->id]->staff_ara_id
                    ]);
                echo $user->email . ' matched to ' . $staff_member[$user->id]->staff_ara_id.'<br>';
//                $staff_members->forget($staff_member[$user->id]->id);
            }else{
                echo $user->email . ' not matched to a staff<br>';
            }
        }
    }


    public function updateEmailUserStaff()
    {
        // chatGPT
        // Retrieve Google Workspace Users with no staff_ara_id set
        $usersToUpdate = DB::table('google_workspace_users')
            ->whereNull('email_user_staff')
            ->get();

        foreach ($usersToUpdate as $user) {
            $matchingStaffMember = DB::table('staff_member_details')
                ->where('email', $user->email)
                ->first();

            if ($matchingStaffMember) {
                DB::table('google_workspace_users')
                    ->where('id', $user->id)
                    ->update(['email_user_staff' => $matchingStaffMember->staff_ara_id]);
            }
        }

        return response()->json(['message' => 'Email_user_staff updated successfully']);
    }

    public function createStaffForm()
    {
        $locations = array(
            array(
                "location" => "LOS",
            ),
            array(
                "location" => "BNI",
            ),
            array(
                "location" => "ABV",
            ),
            array(
                "location" => "PHC",
            ),
            array(
                "location" => "ENU",
            ),
            array(
                "location" => "ILR",
            ),
            array(
                "location" => "KAN",
            ),
            array(
                "location" => "QRW",
            ),
            array(
                "location" => "JOS",
            ),
            array(
                "location" => "MIU",
            ),
            array(
                "location" => "YOL",
            ),
            array(
                "location" => "QOW",
            ),
            array(
                "location" => "SKO",
            ),
            array(
                "location" => "IBA",
            ),
            array(
                "location" => "ABB",
            ),
            array(
                "location" => "LAGOS",
            ),
            array(
                "location" => "EDO",
            ),
            array(
                "location" => "DELTA",
            ),
            array(
                "location" => "ABUJA FCT",
            ),
            array(
                "location" => "ENUGU",
            ),
            array(
                "location" => "RIVERS",
            ),
            array(
                "location" => "Ibadan",
            ),
            array(
                "location" => "ADAMAWA",
            ),
            array(
                "location" => "ABUJA",
            ),
            array(
                "location" => "BENIN",
            ),
            array(
                "location" => "ASABA",
            ),
            array(
                "location" => "PORT HARCOURT",
            ),
        );

        return view('frontend.staff_management.add_staff_record', compact('locations'));
    }

    public function storeStaff(Request $request)
    {
        $validated = $request->validate([
           'staff_ara_id' => ['required', 'string', 'unique:staff_member_details,staff_ara_id'],
        ]);

        $arr = $request->all();
        $arr['staff_id'] = 'ARA'.$request->staff_ara_id;
        $staff_member = StaffMember::create($arr);
        return redirect(route('frontend.user.profile.editIDcard').'?staff_ara_id='.$request->staff_ara_id)->withFlashSuccess('Staff Information added');
    }

    public function storeStaffDeactivation(Request $request)
    {
        $validated = $request->validate([
            'staff_ara_id' => ['required', 'string', 'exists:staff_member_details,staff_ara_id'],
        ]);

        $staffMember = StaffMember::where('staff_ara_id', $request->staff_ara_id)->first();
        $staffMember->resigned_on = $request->resigned_on;
        $staffMember->restrict_access_from = $request->restrict_access_from;
        $staffMember->deactivate_from = $request->deactivate_from;
        $staffMember->save();

        return redirect()->route('frontend.user.profiles')->withFlashInfo('Staff deactivated');
    }



    public function deactivateStaff()
    {
        $now = now();
        $staff_members = StaffMember::where('deactivate_from', '<=', $now)
            ->where('deactivated_at', null)
            ->get();
        if($staff_members){
            foreach ($staff_members as $staff_member){
                $staff_member->deleted_at = $now;
                $staff_member->deactivated_at = $staff_member->deactivate_from;
                $staff_member->save();
                echo $staff_member->staff_ara_id.'<br>';
            }
        }else{
            echo 'none';
        }
    }

    public function storeRemoteSchedule(Request $request)
    {

        $validated = $request->validate([
            'staff_ara_id' => ['required', 'string', 'exists:staff_member_details,staff_ara_id'],
        ]);

        $previous_schedule = DB::table('staff_remote_schedules')
            ->where('week_day', $request->week_day)
            ->where('staff_ara_id', $request->staff_ara_id)
            ->first();

        if($previous_schedule){
            $ended_on = Carbon::parse($request->commenced_on);

            DB::table('staff_remote_schedules')
                ->where('id', $previous_schedule->id)
                ->update(['ended_on' => $ended_on->subDay()]);
        }

        $days = [
            'monday' =>   	 1,
            'tuesday' =>     2,
            'wednesday' =>   3,
            'thursday' =>    4,
            'friday' =>      5,
        ];

        DB::table('staff_remote_schedules')
            ->insert([
                'staff_ara_id' => $request->staff_ara_id,
                'week_day' => $request->week_day,
               'location' => $request->location,
               'commenced_on' => $request->commenced_on,
                'day' => $days[$request->week_day]
            ]);

        return back()->withFlashSuccess('Schedule updated');
    }
}
