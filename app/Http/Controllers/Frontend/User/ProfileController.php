<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Models\StaffMember;
use App\Repositories\Frontend\Auth\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdateProfileRequest $request)
    {
        $output = $this->userRepository->update(
            $request->user()->id,
            $request->only('first_name', 'last_name', 'email', 'avatar_type', 'avatar_location'),
            $request->has('avatar_location') ? $request->file('avatar_location') : false
        );

        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            auth()->logout();

            return redirect()->route('frontend.auth.login')->withFlashInfo(__('strings.frontend.user.email_changed_notice'));
        }

        return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    }

    public function editIDcard(Request $request)
    {
        $validated = $request->validate([
            'staff_ara_id' => 'required|string|exists:staff_member_details,staff_ara_id'
        ]);

        $auth_user = auth()->user();

        if($auth_user->isAdmin() || $auth_user->can('manage own unit info') || $auth_user->can('update other staff info')){
            $is_authorized = true;
        }else if(isset($auth_user->staff_member) && $auth_user->staff_member->staff_ara_id == $request->staff_ara_id){
            $is_authorized = true;
        }else{
            return redirect()->route('frontend.index')->withErrors('Unauthorized action');
        }


        $staff = StaffMember::where('staff_ara_id', $request->staff_ara_id)->first();
        if(!$staff){
            return back()->withErrors('No associated staff information in database');
        }

        if(!$auth_user->can('update other staff info') && $staff->department_name != $auth_user->staff_member->department_name){
            return back()->withFlashInfo('You can only modify details of staff members in your department');
        }

        $department_members = StaffMember::where('department_name', $staff->department_name)->get();
        $department_members_count = $department_members->count();
        $department_members_uploaded_id_count = StaffMember::where('department_name', $staff->department_name)
            ->where('id_card_file_name', '!=', null)
            ->count();
        if($staff->manager_ara_id){
            $manager = StaffMember::where('staff_ara_id', $staff->manager_ara_id)->first();
        }

        $hybrid_work_schedules = DB::table('staff_remote_schedules')
            ->where('staff_ara_id', $request->staff_ara_id)
            ->get();

        return view('frontend.user.id_card')->with([
            'staff' => $staff,
            'department_members' => $department_members,
            'department_members_count' => $department_members_count,
            'department_members_uploaded_id_count' => $department_members_uploaded_id_count,
            'manager' => $manager ?? null,
            'hybrid_work_schedules' => $hybrid_work_schedules
        ]);
    }

    public function uploadIDCard(Request $request)
    {
        $validated = $request->validate([
           'id_card_file' => 'mimetypes:image/jpeg,image/png',
            'id_expiry_date' => 'required|date|after:'.Carbon::now()->toDateString(),
            'location_in_hq' => 'required|string',
            'staff_ara_id' => 'required|string|exists:staff_member_details,staff_ara_id'
        ]);

        $auth_user = auth()->user();

        if($auth_user->isAdmin() || $auth_user->can('manage own unit info') || $auth_user->can('update other staff info')){
            $is_authorized = true;
        }else if(isset($auth_user->staff_member) && $auth_user->staff_member->staff_ara_id == $request->staff_ara_id){
            $is_authorized = true;
        }else{
            return redirect()->route('frontend.index')->withErrors('Unauthorized action');
        }

        $staff = StaffMember::where('staff_ara_id', $request->staff_ara_id)->first();
        if(!$staff){
            return back()->withFlashWarning('No staff account attached');
        }

        if(!$auth_user->can('update other staff info') && $staff->department_name != $auth_user->staff_member->department_name){
            return back()->withFlashInfo('You can only modify details of staff members in your department');
        }

        if($request->hasFile('id_card_file')) {
            $imageName = $staff->staff_ara_id . '.' . $request->id_card_file->extension();
            $request->id_card_file->move(public_path('/img/id_cards'), $imageName);
            $staff->id_card_file_name = $imageName;
        }
        $staff->id_expiry_date = $request->id_expiry_date;
        $staff->location_in_hq = $request->location_in_hq;
        $staff->id_remarks = $request->remarks;
        $staff->location = $request->location;
        $staff->surname = $request->surname;
        $staff->other_names = $request->other_names;
        $staff->paypoint = $request->paypoint;
        $staff->department_name = $request->department_name;
        $staff->email = $request->email;
        $staff->employment_category = $request->employment_category;
        $staff->shift_nonshift = $request->shift_nonshift;
        $staff->save();

        return back()->withFlashSuccess('ID card saved');
    }

    public function staffMembersProfiles()
    {
        $auth_user = auth()->user();
        if(!$auth_user->can('manage own unit info') && !$auth_user->can('update other staff info')) {
            return redirect()->route('frontend.index')->withErrors('Unauthorized action');
        }

        if($auth_user->can('update other staff info')){
            $staff_members = StaffMember::all();
        }else{
            $staff_members = StaffMember::where('department_name', $auth_user->staff_member->department_name)->get();
        }

        $stats['Staff ID Card Updates']['title'] = 'Staff ID Card Updates';
        $stats['Staff ID Card Updates']['value'] = StaffMember::where('id_card_file_name', '!=', null)->count().' out of '.StaffMember::count();
        $stats['Staff ID Card Updates']['icon'] = 'id-badge';

        return view('frontend.staff_management.staff_members_list')->with([
            'staff_members' => $staff_members,
            'stats' => $stats
        ]);
    }

    public function updateManager(Request $request)
    {
        $validated = $request->validate([
            'staff_ara_id' => 'required|string|exists:staff_member_details,staff_ara_id',
            'manager_ara_id' => 'required|string|exists:staff_member_details,staff_ara_id',
        ]);

        $auth_user = auth()->user();
        if(!$auth_user->can('manage own unit info') && !$auth_user->can('update other staff info')) {
            return redirect()->route('frontend.index')->withErrors('Unauthorized action');
        }

        DB::table('staff_member_details')
            ->where('staff_ara_id', $validated['staff_ara_id'])
            ->update([
                'manager_ara_id' => $validated['manager_ara_id']
            ]);

        return redirect()->back()->withFlashSuccess('Staff manager updated');
    }

}
