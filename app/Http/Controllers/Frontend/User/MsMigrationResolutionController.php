<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\MicrosoftUser;
use App\Models\StaffMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MsMigrationResolutionController extends Controller
{
    public function index()
    {
        $staff_members = StaffMember::whereNull('email')->orderBy('other_names', 'ASC')->whereNull('deactivated_at')->get();
        $close_matching_emails = [];
        $ms_users = DB::table('microsoft_users')
            ->select('id', 'display_name', 'email', 'first_name', 'last_name', 'licenses')
            ->whereNull('staff_ara_id')
            ->get()
            ->keyBy('id');

        foreach ($ms_users as $ms_user){
                $closest_matching_staffs = StaffMember::where('other_names', 'LIKE', '%' . $ms_user->first_name . '%')
                    ->orWhere('other_names', 'LIKE', '%' . $ms_user->last_name . '%')
                    ->orWhere('other_names', 'LIKE', '%' . $ms_user->display_name . '%')
                    ->orWhere('surname', 'LIKE', '%' . $ms_user->last_name . '%')
                    ->orWhere('surname', 'LIKE', '%' . $ms_user->first_name . '%')
                    ->orWhere('email', 'LIKE', '%' . $ms_user->email . '%')
                    ->get();

                $bestMatch = null;
                $bestScore = 0;

                foreach ($closest_matching_staffs as $match) {
                    // Combine both names for comparison
                    $full_name = ($match->surname ? $match->surname . ' ' : '') . $match->other_names;

                    // Calculate similarity score based on occurrences of first and last names
                    $score = similar_text($ms_user->first_name . ' ' . $ms_user->last_name, $full_name, $percent);

                    // Update best match if current score is higher
                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $bestMatch = $match;
                    }
                }

                if ($bestMatch) {
                    $close_matching_emails[$bestMatch->staff_ara_id] = $ms_user->email;
                }

            }

//        foreach ($ms_users as $ms_user) {
//            $bestMatch = null;
//            $bestScore = PHP_INT_MAX; // Lower score is better for Levenshtein
//
//            // Iterate over staff members without email to find the closest match
//            foreach ($staff_members as $staff_member) {
//                // Combine both names for comparison
//                $staff_full_name = trim($staff_member->surname . ' ' . $staff_member->other_names);
//
//                // Microsoft user full name
//                $ms_user_full_name = trim($ms_user->first_name . ' ' . $ms_user->last_name);
//
//                // Calculate Levenshtein distance
//                $levenshtein_score = levenshtein($ms_user_full_name, $staff_full_name);
//
//                // Update best match if current score is lower (better match)
//                if ($levenshtein_score < $bestScore) {
//                    $bestScore = $levenshtein_score;
//                    $bestMatch = $staff_member;
//                }
//            }
//
//            if ($bestMatch) {
//                // Assign the best match email
//                $close_matching_emails[$bestMatch->staff_ara_id] = $ms_user->email;
//                // Optionally, update the staff member's email in the database
//                $bestMatch->email = $ms_user->email;
//                $bestMatch->save();
//            }
//        }


//        foreach ($staff_members as $staff_member) {
//            $max_similarity = 0;
//            $closest_email = null;
//
//            // Concatenate staff member's name
//            $staff_full_name = $staff_member->surname . ' ' . $staff_member->other_names;
//
//            // Iterate over Microsoft users to find the closest match
//            foreach ($ms_users as $ms_user) {
//                // Concatenate Microsoft user's name
//                $ms_user_full_name = $ms_user->first_name . ' ' . $ms_user->last_name;
//
//                // Calculate similarity
//                similar_text($staff_full_name, $ms_user_full_name, $similarity);
//
//                // If the similarity is higher than the max similarity, update closest email
//                if ($similarity > $max_similarity) {
//                    $max_similarity = $similarity;
//                    $closest_email = $ms_user->email;
//                }
//            }
//
//            // Store the closest matching email for the staff member
//            $close_matching_emails[$staff_member->id] = $closest_email;
//        }
        return view('frontend.staff_management.ms-exchange-emails')->with([
            'staff_members' => $staff_members,
            'close_matching_emails' => $close_matching_emails,
            'ms_users' => $ms_users
        ]);
    }

    public function storeEmail(Request $request)
    {
        DB::table('staff_member_details')
            ->where('id', $request->id)
            ->update([
                'email' => $request->email
            ]);

        DB::table('microsoft_users')
            ->where('email', $request->email)
            ->update([
                'staff_ara_id' => $request->staff_ara_id
            ]);

        return [
            'status' => 'success',
            'message' => $request->staff_ara_id .' updated with email: '.$request->email
        ];
    }

    public function storeMatchingEmailsBulk()
    {
        $ms_users = MicrosoftUser::all();
        $emails_with_staff = [];
        foreach($ms_users as $ms_user){
            $staff_member = StaffMember::where('email', $ms_user->email)->first();
            if($staff_member){
                $ms_user->staff_ara_id = $staff_member->staff_ara_id;
                $ms_user->save();
            }
        }

        return MicrosoftUser::whereNotNull('staff_ara_id')->get();
    }

    public function migrationChecks()
    {
        $ms_users = MicrosoftUser::whereNotNull('staff_ara_id')->get();
        return view('frontend.staff_management.staff-migration-checks', compact('ms_users'));
    }

    public function updateMigrationChecks(Request $request)
    {

    }
}
