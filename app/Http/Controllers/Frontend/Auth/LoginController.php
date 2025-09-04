<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Events\Frontend\Auth\UserLoggedIn;
use App\Events\Frontend\Auth\UserLoggedOut;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Mail\GeneralMailing;
use App\Models\Auth\User;
use App\Models\StaffMember;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class LoginController.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(home_route());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return config('access.users.username');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => PasswordRules::login(),
            'g-recaptcha-response' => ['required_if:captcha_status,true', 'captcha'],
        ], [
            'g-recaptcha-response.required_if' => __('validation.required', ['attribute' => 'captcha']),
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param         $user
     *
     * @throws GeneralException
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        // Check to see if the users account is confirmed and active
        if (! $user->isConfirmed()) {
            auth()->logout();

            // If the user is pending (account approval is on)
            if ($user->isPending()) {
                throw new GeneralException(__('exceptions.frontend.auth.confirmation.pending'));
            }

            // Otherwise see if they want to resent the confirmation e-mail

            throw new GeneralException(__('exceptions.frontend.auth.confirmation.resend', ['url' => route('frontend.auth.account.confirm.resend', e($user->{$user->getUuidName()}))]));
        }

        if (! $user->isActive()) {
            auth()->logout();

            throw new GeneralException(__('exceptions.frontend.auth.deactivated'));
        }

        event(new UserLoggedIn($user));

        if (config('access.users.single_login')) {
            auth()->logoutOtherDevices($request->password);
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Remove the socialite session variable if exists
        if (app('session')->has(config('access.socialite_session_name'))) {
            app('session')->forget(config('access.socialite_session_name'));
        }

        // Fire event, Log out user, Redirect
        event(new UserLoggedOut($request->user()));

        // Laravel specific logic
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect()->route('frontend.index');
    }

    public function sendOTP(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(!$user){
            $staff_member = StaffMember::where('email', $request->email)->first();
            if(!$staff_member || !$staff_member->email)
                return redirect()->route('frontend.auth.login')->withErrors('No user with that email found');

                $user = User::create([
                    'first_name' => $staff_member->other_names,
                    'last_name' => $staff_member->surname,
                    'email' => $staff_member->email,
                    'confirmation_code' => md5(uniqid(mt_rand(), true)),
                    'active' => true,
                    'password' => \Hash::make($staff_member->email),
                    // If users require approval or needs to confirm email
                    'confirmed' => 1,
                ]);

                if ($user) {
                    // Add the default site role to the new user
                    $user->assignRole(config('access.users.default_role'));
                }

        }


        $user->otp = generateNumericOTP(6);
        $user->save();

        unset($data);
        $data['subject'] = "OTP to login on ".app_name();
        $data['greeting'] = "Dear " . $user->full_name;
        $data['line'][] = "Below is your OTP to login on ".app_name().", generated on ".now()->toDayDateTimeString().'.';
        $data['line'][] = " ";
        $data['line'][] = $user->otp;
        $data['line'][] = " ";
        $data['line'][] = "If you did not trigger this action, quickly send a message to IT Helpdesk on ithelpdesk@arikair.com";
        $data['to'] = $user->email;
        $data['to_name'] = $user->full_name;

        Mail::send(new GeneralMailing($data));
        session(['email' => $user->email]);
        return view('frontend.auth.otp')->withFlashInfo('An OTP '.$user->otp.' has been sent to your email "'.$request->email.'"');
    }

    public function verifyOTP(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(!$user)
            return redirect()->route('frontend.auth.login')->withErrors('No user with that email found');

        if($user->otp == $request->otp)
            Auth::login($user, true);
            return $this->authenticated($request, $user);

        return view('frontend.auth.otp')->withErrors('Incorrect OTP Entered');
    }

}
