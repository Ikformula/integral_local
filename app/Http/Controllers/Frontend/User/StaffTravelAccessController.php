<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Setting;
use App\Models\StaffTravelBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;
use Illuminate\Support\Facades\DB;

class StaffTravelAccessController extends Controller
{
    use OutgoingMessagesTrait;

    public function makeBookingGenHash(Request $request)
    {
        $auth_user = auth()->user();

        if(!$auth_user)
            return $this->routeForFailure('You need to be logged in');

        $staff_member = $auth_user->staff_member;
        if(!isset($staff_member))
            return $this->routeForFailure('Not a staff member');

        if($staff_member->staff_travel_blocked_at)
            return $this->routeForFailure('Staff Travel Booking Restriction in Place');

        $resp = $this->getSTBBalance($staff_member->staff_ara_id);
        if($resp['friends_balance'] <= 0)
            return $this->routeForFailure('You have reached the maximum discounted staff travel limit for this year');

        $now = Carbon::now();
        $stb_hash = $staff_member->email.$request->getClientIp().date('Y-m-d').date('H');

//        echo $stb_hash.'<br>';
//        echo password_hash($stb_hash, PASSWORD_BCRYPT).'<br>';
//        echo password_verify('sparkuphubaccess@gmail.com127.0.0.12023-03-3115', '$2y$10$EQiEABIifXWxCY39.CTNl.DajcJJNBEedmfylXbN7ZO8moWdcNI/y');

        $staff_member->stb_access_code = password_hash($stb_hash, PASSWORD_BCRYPT);
        $staff_member->stb_access_code_expires_at = $now->addMinutes('5');
        $staff_member->save();

        return redirect('https://arikair.crane.aero/staff/StaffLogin.xhtml');
    }

    public function getSTBBalance($staff_ara_id)
    {
        $now = Carbon::now();
        $years_bookings = StaffTravelBooking::where('staff_ara_id', $staff_ara_id)
            ->where('request_year', $now->year)
            ->get();
        $years_bookings_count = $years_bookings->sum('adult') + $years_bookings->sum('child');

        $max_bookings_allowed = Setting::where('category', 'staff_travel_portal')
            ->where('key', 'yearly_booking_allowance')
            ->first();

        $max_bookings_allowed = $max_bookings_allowed->value;

//        $resp['staff_balance'] = $max_bookings_allowed; // $max_bookings_allowed - $years_bookings_count;
        $resp['staff_balance'] = 0; // $max_bookings_allowed - $years_bookings_count;
        $resp['friends_balance'] = $max_bookings_allowed - $years_bookings_count;
        return $resp;
    }

    public function verifyAuth(Request $request)
    {
//        $chk = [
//            'user' => $request->user,
//            'user_ip_address' => $request->user_ip_address,
//            'request_getClientIP' => $request->getClientIp()
//        ];
//
//        dd($chk);

        $validated = $request->validate([
           'user' => 'required|exists:staff_member_details,email',
            'user_ip_address' => 'required'
        ]);

        $auth_user = User::where('email', $request->user)->first();

        $now = Carbon::now();
        if(!$auth_user)
            return $this->routeForFailure('You need to be logged in', 'json');

        $staff_member = $auth_user->staff_member;
        if(!isset($staff_member))
            return $this->routeForFailure('Not a staff member', 'json');

        if($request->user != $staff_member->email && $request->user != $staff_member->staff_ara_id)
            return $this->routeForFailure('Username does not match that of logged in user', 'json');

        if($staff_member->staff_travel_blocked_at)
            return $this->routeForFailure('Staff Travel Booking Restriction in Place', 'json');

        if($now > $staff_member->stb_access_code_expires_at)
            return $this->routeForFailure('Staff Travel Authentication Token Expired', 'json');

        if(!password_verify($request->user.$request->user_ip_address.date('Y-m-d').date('H'), $staff_member->stb_access_code))
            return $this->routeForFailure('Invalid authentication data: '.$request->user_ip_address, 'json');

        $staff_ara_id = $staff_member->staff_ara_id;

        $resp = $this->getSTBBalance($staff_ara_id);
        $Result = header('Content-type: text/xml');

        $Result .= "<?xml version='1.0' encoding='utf-8'?>\n";
        $Result .= "<validateResponse>\n";

        if($resp['friends_balance'] >= 1) {
            $Result .= "  <staff_balance>" . $resp['staff_balance'] . "</staff_balance>\n";
            $Result .= "  <friends_balance>" . $resp['friends_balance'] . "</friends_balance>\n";
            $Result .= " <employee>\n";

            foreach($staff_member->getAttributes() as $key => $value) {
                $value = str_replace(' ', '', $value);
                $Result .=  "  <$key>$value</$key>\n";
            }

            $Result .= " </employee>\n";
            $success = true;
        }else{
            $success = false;
            $Result .= " <status_code>\n";

            $Result .=  "1\n";

            $Result .= " </status_code>\n";

            $Result .= " <status_message>\n";

            $Result .=  "Invalid Data\n";

            $Result .= " </status_message>\n";
        }

        $Result .= "</validateResponse>\n";

        unset($data);
        $data['subject'] = config('app.name')." Staff Travel Login Initiated";
        $data['greeting'] = "Hi ".$staff_member->name;
        $data['line'][] = "Login was initiated to your ".  config('app.name')." Staff Travel Booking Account on ".  $now->toDayDateTimeString(). ". If this was not you, kindly report to itteam@arikair.com.";
        $data['to'] = $staff_member->email;
        $data['to_name'] = $staff_member->name;

        $this->storeMessage($data, $auth_user->id);


        // STORE LOGIN LOG
        DB::table('stb_login_logs')
            ->insert([
                'staff_ara_id' => $staff_member->staff_ara_id,
                'ip_address' => $request->getClientIp(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);



        $response = [
          'success' => $success,
          'Result' => $Result
        ];

        return json_encode($response);
    }

    public function verifyAuthFORMER(Request $request)
    {

        $auth_user = auth()->user();

        if(!$auth_user)
            return $this->routeForFailure('You need to be logged in');

        $validated = $request->validate([
           'user' => 'required',
            'pass' => 'required'
        ]);

        $staff_member = $auth_user->staff_member;
        if(!isset($staff_member))
            return $this->routeForFailure('Not a staff member');

        if($request->user != $staff_member->email && $request->user != $staff_member->staff_ara_id)
            return $this->routeForFailure('Username does not match that of logged in user');

        if($staff_member->staff_travel_blocked_at)
            return $this->routeForFailure('Staff Travel Booking Restriction in Place');

        $now = Carbon::now();
        // STORE LOGIN LOG
        DB::table('stb_login_logs')
            ->insert([
               'staff_ara_id' => $staff_member->staff_ara_id,
                'ip_address' => $request->getClientIp(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);


        $staff_ara_id = $staff_member->staff_ara_id;

        $years_bookings = StaffTravelBooking::where('staff_ara_id', $staff_ara_id)
            ->where('request_year', $now->year)
            ->get();
        $years_bookings_count = $years_bookings->sum('adult') + $years_bookings->sum('child');

        $max_bookings_allowed = Setting::where('category', 'staff_travel_portal')
            ->where('key', 'yearly_booking_allowance')
            ->first();

        $max_bookings_allowed = $max_bookings_allowed->value;


        $resp['staff_balance'] = $max_bookings_allowed; // $max_bookings_allowed - $years_bookings_count;
        $resp['friends_balance'] = $max_bookings_allowed - $years_bookings_count;

        $Result = header('Content-type: text/xml');

        $Result .= "<?xml version='1.0' encoding='utf-8'?>\n";
        $Result .= "<validateResponse>\n";

        if($resp['friends_balance'] >= 1) {
            $Result .= "  <staff_balance>" . $resp['staff_balance'] . "</staff_balance>\n";
            $Result .= "  <friends_balance>" . $resp['friends_balance'] . "</friends_balance>\n";
            $Result .= " <employee>\n";

            foreach($staff_member->getAttributes() as $key => $value) {
                $value = str_replace(' ', '', $value);
                $Result .=  "  <$key>$value</$key>\n";
            }

            $Result .= " </employee>\n";
        }else{
            $Result .= " <status_code>\n";

            $Result .=  "1\n";

            $Result .= " </status_code>\n";

            $Result .= " <status_message>\n";

            $Result .=  "Invalid Data\n";

            $Result .= " </status_message>\n";
        }

        $Result .= "</validateResponse>\n";

        unset($data);
        $data['subject'] = config('app.name')." Staff Travel Login Initiated";
        $data['greeting'] = "Hi ".$staff_member->name;
        $data['line'][] = "Login was initiated to your ".  config('app.name')." Staff Travel Booking Account on ".  $now->toDayDateTimeString(). ". If this was not you, kindly report to itteam@arikair.com.";
        $data['to'] = $staff_member->email;
        $data['to_name'] = $staff_member->name;

        $this->storeMessage($data, $auth_user->id);

        return $Result;
    }


    public function finalizeBooking (Request $request)
    {
        // finalizebooking.php
    }

    public function sendPasswordResetEmail()
    {
        $auth_user = auth()->user();
        $staff_member = $auth_user->staff_member;
        if(!isset($staff_member))
            return $this->routeForFailure('Not a staff member');

        if(!is_null($staff_member->staff_travel_blocked_at))
            return $this->routeForFailure('Staff Travel Access Blocked');

        // generate new token/code
        $token = '';
        for($i = 1; $i <= 6; $i++){
            $token .= rand(0, 9);
        }

        // store token and expiry
        $expiry_minutes = 10;
        $staff_member->st_password_reset_code = md5($token);
        $staff_member->stp_reset_code_expires_at = Carbon::now()->addMinutes($expiry_minutes);
        $staff_member->save();

        // store/send email
        unset($data);
        $data['subject'] = config('app.name')." Staff Travel Password Reset Initiated";
        $data['greeting'] = "Hi ".$staff_member->name;
        $data['line'][] = "Someone (hopefully you) tried to reset your ".  config('app.name')." Staff Travel Password on ".  Carbon::now()->toDayDateTimeString(). ". You can complete the process with the reset token below.";
        $data['line'][] = ' ';
        $data['line'][] = $token;
        $data['line'][] = ' ';
        $data['line'][] = "This code expires in ".$expiry_minutes." minutes and should only be used on Arik Integral. Do not share with any body.";
        $data['to'] = $staff_member->email;
        $data['to_name'] = $staff_member->name;

        $this->storeMessage($data, $auth_user->id);

        return view('frontend.staff_travel.reset_code')->withMessage('A reset token has been sent to your Arik Email');
    }

    public function verifyResetCode(Request $request)
    {
        $auth_user = auth()->user();
        $staff_member = $auth_user->staff_member;
        if(!isset($staff_member))
            return $this->routeForFailure('Not a staff member');

        if(!is_null($staff_member->staff_travel_blocked_at))
            return $this->routeForFailure('Staff Travel Access Blocked');

        $validated = $request->validate([
           'reset_token' => 'required|numeric',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        $now = Carbon::now();
        if(is_null($staff_member->stp_reset_code_expires_at) || $staff_member->stp_reset_code_expires_at < $now){
            return $this->routeForFailure('Reset Token Expired');
        }

        if(md5($request->reset_token) != $staff_member->st_password_reset_code){
            return $this->routeForFailure('The code doesn\'t match our records');
        }

        $staff_member->st_password_reset_code = null;
        $staff_member->stp_reset_code_expires_at = null;
        $staff_member->staff_travel_password = ($request->password); // TODO: add appropriate hashing algorithm
        $staff_member->st_password_changed_at = $now;
        $staff_member->save();

        return redirect()->route('frontend.index')->withFlashSuccess('Your staff travel portal password has been reset');
    }

    public function routeForFailure($msg, $is_json = false)
    {
        if($is_json == 'json'){
            return [
                'success' => false,
                'msg' => $msg
            ];
        }

        return redirect()->route('frontend.index')->withErrors($msg);
    }
}
