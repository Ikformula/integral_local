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

class StaffAttendanceMiscController extends Controller
{
    use OutgoingMessagesTrait;

    public function getPremisesRecs(Request $request)
    {
// Get current date and time
        $currentTime = Carbon::now();
        $today = Carbon::today();

        $staffIds = DB::table('staff_attendances')
            ->select('staff_ara_id')
            ->where('created_at', '<=', $currentTime)
            ->whereNull('deleted_at')
            ->whereIn('direction', ['in', 'out'])
            ->groupBy('staff_ara_id')
            ->havingRaw('MAX(CASE WHEN direction = ? THEN created_at ELSE ? END) >= ?', ['in', '1970-01-01 00:00:00', $currentTime])
            ->havingRaw('MAX(CASE WHEN direction = ? THEN created_at ELSE ? END) < ?', ['out', $currentTime, $currentTime])
            ->get()
            ->pluck('staff_ara_id');

        dd($staffIds);
    }

    public function notifyLateComer(Request $request)
    {
        $validated = $request->validate([
            'staff_ara_id' => ['required', 'string', 'exists:staff_member_details,staff_ara_id'],
            'email_type' => ['required', 'string'],
        ]);
        $staff_ara_id = $validated['staff_ara_id'];

        $staff = StaffMember::where('staff_ara_id', $staff_ara_id)->first();
        if(!$staff->email){
            return response()->json([
                'status' => 'error',
               'message' => 'No defined email address for this staff'
            ]);
        }

        unset($data);
        if($validated['email_type'] == 'lateness') {

            $data['subject'] = config('app.name') . " - Constant Lateness";
            $data['greeting'] = "Dear " . $staff->surname . ' ' . $staff->other_names;
            $data['line'][] = "I am writing to express my concern regarding your recent pattern of frequent late arrivals to work. Punctuality is crucial in maintaining a productive work environment, and thus we would like you to make improvements.";
            $data['line'][] = "While we understand that unforeseen circumstances can occasionally cause delays, it is important that we address this issue promptly. We kindly request that you prioritize punctuality moving forward and familiarize yourself with our company's attendance policies and guidelines. If you are facing any challenges that may be affecting your ability to arrive on time, please let us know so that we can discuss potential solutions.";

        }else if($validated['email_type'] == 'absence') {

//            $data['line'][] = "Your cooperation in rectifying this matter is greatly appreciated. If you have any questions or concerns, please feel free to reach out to us. Thank you for your attention to this urgent matter.";
            $data['subject'] = config('app.name') . " - Absenteeism";
            $data['greeting'] = "Dear " . $staff->surname . ' ' . $staff->other_names;
            $data['line'][] = "I am writing to express my concern regarding your recent pattern of being absent to work. Punctuality is crucial in maintaining a productive work environment, and thus we would like you to make improvements.";
            $data['line'][] = "While we understand that unforeseen circumstances can occasionally cause you to be absent, it is important that we address this issue promptly. We kindly request that you prioritize attendance moving forward and familiarize yourself with our company's attendance policies and guidelines. If you are facing any challenges that may be affecting your ability to be present when you are supposed to, please let us know so that we can discuss potential solutions.";

        }
            $data['line'][] = "Your cooperation in rectifying this matter is greatly appreciated. If you have any questions or concerns, please feel free to reach out to us. Thank you for your attention to this urgent matter.";

        $data['line'][] = "Best regards";
        $data['line'][] = "";
        $data['line'][] = "HR Team";
        $data['to'] = $staff->email;
//        $data['to'] = 'asuquobartholomewi@gmail.com';
        $data['to_name'] = $staff->surname.' '.$staff->other_names;
        $data['from'] = 'arikpass@arikair.com';
        $data['from_name'] = 'Arik Pass';
        $data['cc'] = 'kate.obasi@arikair.com';
//        $data['cc'] = 'arikpasstest@mailinator.com';


        $message_store = $this->storeMessage($data, null, true);

        $now = Carbon::now();
        DB::table('attendance_email_logs')
            ->insert([
               'staff_ara_id' => $staff_ara_id,
                'outgoing_message_recipient_id' => $message_store->id,
                'type' => $validated['email_type'] == 'absence' ? 'absence' : $validated['email_type'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

        $attendanceLogs = DB::table('attendance_email_logs')
            ->where('staff_ara_id', $staff_ara_id)
            ->where('type', $validated['email_type'])
            ->orderBy('created_at', 'DESC')
            ->get();

        if(!is_null($attendanceLogs)){
            $attendances[$staff_ara_id]['emails_count'] = $attendanceLogs->where('type',$validated['email_type'])->count();
            $last_sent = $attendanceLogs->where('type',$validated['email_type'])->first();

            $attendances[$staff_ara_id]['last_sent'] = Carbon::parse($last_sent->created_at)->diffForHumans();
        }else{
            $attendances[$staff_ara_id]['emails_count'] = 0;
            $attendances[$staff_ara_id]['last_sent'] = 'never';
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email sent',
            'staff_ara_id' => $staff_ara_id,
            'email_type' => $validated['email_type'],
            'emails_count' => $attendances[$staff_ara_id]['emails_count'],
            'last_sent' => $attendances[$staff_ara_id]['last_sent']
        ]);

//        return redirect()->back()->withFlashInfo('Email sent');
    }

}
