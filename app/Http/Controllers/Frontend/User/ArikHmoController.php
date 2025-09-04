<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\FamilyDetail;
use App\Models\StaffMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Repositories\Frontend\Auth\UserRepository;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;

class ArikHmoController extends Controller
{
    use OutgoingMessagesTrait;

//    /**
//     * @var UserRepository
//     */
//    protected $userRepository;
//
//    /**
//     * RegisterController constructor.
//     *
//     * @param UserRepository $userRepository
//     */
//    public function __construct(UserRepository $userRepository)
//    {
//        $this->userRepository = $userRepository;
//    }

    public function index()
    {
//        if(!auth()->user()->can('update other staff info')){
//            return back()->withErrors('Unauthorized action');
//        }

        abort_unless(auth()->user()->can('update other staff info'), 401, 'Unauthorized action');

        $staff_members = StaffMember::all();
        return view('frontend.hmo.index')->with([
            'staff_members' => $staff_members
        ]);
    }

    public function family_members()
    {
        abort_unless(auth()->user()->can('update other staff info'), 401, 'Unauthorized action');

        $family_members = FamilyDetail::all();
        return view('frontend.hmo.family_members')->with([
            'family_members' => $family_members
        ]);
    }

    public function show($ara_number)
    {
        $staff_member = StaffMember::where('staff_ara_id', $ara_number)->first();
        if(!$staff_member){
            return back()->withErrors('Invalid ARA Number');
        }

        $user = auth()->user();

        if(!$staff_member->user && $user->can('update other staff info')){
            // create new user account using this staff member's email
            $data = [
                'first_name' => $staff_member->other_names,
                'last_name' => $staff_member->surname,
                'email' => $staff_member->email,
                'password' => 'welcome@2023'
            ];

            $staff_member_user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'active' => true,
                'password' => $data['password'],
                // If users require approval or needs to confirm email
                'confirmed' => ! (config('access.users.requires_approval') || config('access.users.confirm_email')),
            ]);

            if ($staff_member_user) {
                // Add the default site role to the new user
                $staff_member_user->assignRole(config('access.users.default_role'));
            }

        }else{
            $staff_member_user = $staff_member->user;
        }

        $decision = 0;
        if($user->can('update other staff info') || $user->isAdmin()){
            $decision = 1;
        }

        if(isset($staff_member_user) && $user->id == $staff_member_user->id){
            $decision = 1;
        }

        if($decision == 1){
            return view('frontend.hmo.staff_info')->with([
                'staff_member' => $staff_member
            ]);
        }else{
            return redirect()->route('frontend.user.dashboard')->withErrors('Unauthorized action');
        }

    }

    public function update(Request $request, $ara_number)
    {
        $staff_member = StaffMember::where('staff_ara_id', $ara_number)->first();
        if(!$staff_member){
            return back()->withErrors('Invalid ARA Number');
        }

        $staff_member->update($request->all());

        foreach ($request->contact_number as $key => $value){
            DB::table('contact_details')
                ->updateOrInsert(
                    ['user_id' => $request->user_id, 'contact_category' => $key, 'contact_data_type' => 'phone number', ],
                    ['value' => $value,]
                );
        }

        $data['subject'] = "Your Employee Data Update " . app_name();
        $data['greeting'] = "Hi " . $staff_member->name;
        $data['line'][] = "Your data as been updated on the ArikHMO.";
        $now = Carbon::now();
        $data['line'][] = "Date: " . $now->toDayDateTimeString();
        $data['action_url'] = route('frontend.hmo.show.staff_member', $staff_member->staff_ara_id);
        $data['action_text'] = "View Data";
        $data['to'] = $staff_member->email;
        $data['to_name'] = $staff_member->name;

        $this->storeMessage($data, $staff_member_user->id);

        return back()->withFlashSuccess('Employee Data Updated');
    }

    public function addFamilyMember(Request $request)
    {
        $family_member = FamilyDetail::create($request->all());
        $success_message = 'Family member successfully added';
            return [
                'success' => true,
                'message' => $success_message,
                'familyMember' => $family_member
            ];
    }

    public function removeFamilyMember(Request $request)
    {
        $user = auth()->user();
        $family_member = FamilyDetail::find($request->family_member_id);

        if($user->can('update other staff info') || $user->id = $family_member->user_id) {
            if (!$family_member) {
                return [
                    'success' => false,
                    'message' => 'Could not find that family member'
                ];
            } else {
                $family_member->delete();
                return [
                    'success' => true,
                    'message' => 'Family member records removed'
                ];
            }
        }else{
            return [
                'success' => false,
                'message' => 'Unauthorized action'
            ];
        }
    }

    public function familyMember($ara_number, $family_member)
    {
        $user = auth()->user();
        $family_member = FamilyDetail::find($family_member);
        if($user->can('update other staff info') || $user->id = $family_member->user_id) {
            if (!$family_member) {
                return back()->withErrors('Family member data not found');
            }

            return view('frontend.hmo.family_member')->with([
               'staff_member' => $family_member->staff_member,
               'family_member' => $family_member
            ]);
        }

        return back()->withErrors('Unauthorized action');

    }

    public function updateFamilyMember(Request $request, $ara_number, $family_member)
    {
        $user = auth()->user();
        $family_member = FamilyDetail::find($family_member);
        if($user->can('update other staff info') || $user->id = $family_member->user_id) {
            if (!$family_member) {
                return back()->withErrors('Family member data not found');
            }

            $family_member->update($request->all());
            return back()->withFlashSuccess('Family member data updated');
        }

        return back()->withErrors('Unauthorized action');
    }
}
