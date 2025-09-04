<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\StaffTravelBeneficiary;
use App\Models\StbLoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Traits\StaffTravelTrait;

class StaffTravelController extends Controller
{
    use StaffTravelTrait;

    public function makeBooking()
    {
        $user = auth()->user();
        $staff_member = $user->staff_member;
        if(!$staff_member)
            return redirect()->back()->withErrors('No staff records found for your account');
        $staff_ara_id = $staff_member->staff_ara_id;
        $booking_balance = $this->getSTBBalance($staff_ara_id);

        if(!$booking_balance)
            return redirect()->back()->withErrors('Not enough staff travel balance on your account');

        return view('frontend.staff_travel.make_booking', compact('booking_balance'));
    }

    /***** April 29th, 2025 ****/
    public function bookingInit(Request $request)
    {
        $user = auth()->user();
        $stb_login_log = new StbLoginLog();
        $stb_login_log->staff_ara_id = $user->staff_member ? $user->staff_member->staff_ara_id : '0000';
        $stb_login_log->ip_address = $request->getClientIp();
        $stb_login_log->session_id = Str::uuid();
        $otp = $this->generateHashedOtp();
        $stb_login_log->encrypted_passcode = $otp['hashed'];
        $stb_login_log->save();

        return redirect()->to(config('services.staff_travel.crane_url')."?s_id={$stb_login_log->session_id}&otp={$otp['otp']}");
    }

    function generateHashedOtp($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $otp = collect(str_split($characters))
            ->shuffle()
            ->random($length)
            ->implode('');

        $hashedOtp = Hash::make($otp);

        return [
            'otp' => $otp,
            'hashed' => $hashedOtp
        ];
    }

}
