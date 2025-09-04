<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use Illuminate\Http\Request;

class LegalPublicController extends Controller
{
    public function verifyOTPforTFM(Request $request)
    {
        dd($request->email);
        $user = User::where('email', $request->email)->first();
        if(!$user)
            return redirect()->route('frontend.auth.login')->withErrors('No user with that email found');

        if($user->otp == $request->otp) {
            return response()->json([
                'authenticated' => true,
                'user' => $user->toArray()
            ]);
        }
    }
}
