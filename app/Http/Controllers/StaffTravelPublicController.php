<?php

namespace App\Http\Controllers;

use App\Models\StaffTravelBeneficiary;
use App\Models\StaffTravelBooking;
use App\Models\StbLoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Traits\StaffTravelTrait;

class StaffTravelPublicController extends Controller
{
    use StaffTravelTrait;
    private $session_expires_minutes = 5;
    private $crane_url = "localhost";

    public function validateLogin(Request $request)
    {
        // Verify Secret key
        if (!$this->verifySecretKey($request)) {
            return response()->json(['error' => 'Invalid secret key'], 403);
        }

        // Verify session ID, otp correctness
        $stb_login = StbLoginLog::where('session_id', $request->input('session_id'))
            ->where('created_at', '>=', now()->subMinutes($this->session_expires_minutes))
            ->whereNull('finalized_at')
            ->first();

        if (!$stb_login || !$this->isValidOtp($request->input('otp'), $stb_login)) {
            return response()->json(['error' => 'Invalid session ID or OTP'], 403);
        }

        $staff_member = $stb_login->staff_member;
        if(!$staff_member)
            return response()->json(['error' => 'Invalid Staff Records'], 403);
        $staff_ara_id = $stb_login->staff_ara_id;
        // return approved beneficiaries and discount balance
        $response = [];

        $response['balance'] = $this->getSTBBalance($staff_ara_id);

        $response['beneficiaries'] = StaffTravelBeneficiary::select([
            'id',
            'firstname',
            'surname',
            'other_name',
            'gender',
            'dob',
            'relationship',
            'photo' // needed internally to generate photo_url
        ])
            ->where('staff_ara_id', $staff_ara_id)
            ->where('status', 'approved')
            ->take(getSettingValue('max_number_of_beneficiaries'))
            ->get()
            ->makeHidden(['photo']);

        $staff_self[] = [
            'id' => 0,
            'firstname' => '',
            'surname' => $staff_member->surname,
            'other_name' => $staff_member->other_names,
            'gender' => $staff_member->gender,
            'dob' => '',
            'relationship' => 'Self',
            'photo_url' => asset('img/id_cards/'.$staff_member->id_card_file_name)
        ];

        $beneficiaries = $response['beneficiaries']->toArray();

        $response['beneficiaries'] = array_merge($staff_self, $beneficiaries);

        $response['staff_member'] = [
            'staff_ara_id' => $staff_ara_id,
            'name' => $staff_member->name,
        ];

        $stb_login->logged_in_at = now();
        $stb_login->save();
        return response()->json($response);
    }

    public function finalizeBooking(Request $request)
    {
        $validated = $request->validate([
           'session_id' => ['required', 'string'],
           'tickets' => ['required']
        ]);

        // Verify Secret key
        if (!$this->verifySecretKey($request)) {
            return response()->json(['error' => 'Invalid secret key'], 403);
        }

        // Verify session ID, otp correctness
        $stb_login = StbLoginLog::where('session_id', $request->input('session_id'))
            ->whereNotNull('logged_in_at')
            ->whereNull('finalized_at')
            ->first();

        if (!$stb_login || !$this->isValidOtp($request->input('otp'), $stb_login)) {
            return response()->json(['error' => 'Invalid session ID or OTP'], 403);
        }

        $staff_member = $stb_login->staff_member;
        $staff_ara_id = $stb_login->staff_ara_id;

        // Store tickets
        $tickets = $validated['tickets'];

        // Add additional columns to each ticket
        $now = now();
        foreach ($tickets as &$ticket) {
            $ticket['stb_login_id'] = $stb_login->id;
            $ticket['staff_ara_id'] = $staff_ara_id;
            $ticket['created_at'] = $now;
            $ticket['updated_at'] = $now;
        }

        // Insert all tickets into the database
        StaffTravelBooking::insert($tickets);
        $stb_login->finalized_at = now();
        $stb_login->save();

        return response()->json(['success' => 'Tickets stored successfully'], 200);
    }

    /**
     * Verify the request domain source.
     *
     * @param Request $request
     * @param string $expectedDomain
     * @return bool
     */
    private function verifyRequestDomain(Request $request, string $expectedDomain): bool
    {
        $referer = $request->headers->get('referer');
        $customHeader = $request->headers->get('x-client-domain');

        $domain = parse_url($referer, PHP_URL_HOST) ?? $customHeader;

        \Log::debug('Domain verification', [
            'referer' => $referer,
            'x-client-domain' => $customHeader,
            'parsed_domain' => $domain,
        ]);

        return $domain === $expectedDomain;
    }


    /**
     * Verify the secret key.
     *
     * @param Request $request
     * @return bool
     */
    private function verifySecretKey(Request $request): bool
    {
        $secretKey = $request->header('X-Secret-Key');
        $expectedKey = env('CRANE_SECRET_KEY');
        return $secretKey === $expectedKey;
    }

    /**
     * Verify the session ID and OTP correctness.
     *
     * @param Request $request
     * @return bool
     */
    private function verifySessionAndOtp(Request $request): bool
    {
        $sessionId = $request->input('session_id');
        $otp = $request->input('otp');


        return $sessionId && $otp && $this->isValidSession($sessionId) && $this->isValidOtp($otp);
    }

    /**
     * Check if the session ID is valid.
     *
     * @param string $sessionId
     * @return bool
     */
    private function isValidSession(string $sessionId): bool
    {
        return StbLoginLog::where('session_id', $sessionId)
            ->where('created_at', '>=', now()->subMinutes($this->session_expires_minutes))
            ->count();
    }

    /**
     * Check if the OTP is valid.
     *
     * @param string $otp
     * @return bool
     */
    private function isValidOtp(string $otp, StbLoginLog $stb_login): bool
    {
        return Hash::check($otp, $stb_login->encrypted_passcode);
    }
}
