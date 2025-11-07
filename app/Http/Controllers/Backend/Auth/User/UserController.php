<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Events\Backend\Auth\User\UserDeleted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use App\Http\Requests\Backend\Auth\User\StoreUserRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserRequest;
use App\Models\Auth\User;
use App\Models\BusinessArea;
use App\Models\CoPresenter;
use App\Models\EcsClientUser;
use App\Models\LegalTeamExternalLawyer;
use App\Models\StaffMember;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request)
    {
        return view('backend.auth.user.index')
            ->withUsers($this->userRepository->getActivePaginated(2500, 'id', 'asc'));
    }


    public function search(Request $request)
    {
        $staff = StaffMember::where('staff_ara_id', $request->q)->first();
        if($staff){
            $users = User::where('first_name', 'LIKE', '%'.$staff->surname.'%')
                ->orWhere('first_name', 'LIKE', '%'.$staff->other_names.'%')
                ->orWhere('last_name', 'LIKE', '%'.$staff->surname.'%')
                ->orWhere('last_name', 'LIKE', '%'.$staff->other_names.'%')
                ->orWhere('email', 'LIKE', '%'.$staff->email.'%')
                ->paginate(2500);
        }else {
            $users = User::where('first_name', 'LIKE', '%' . $request->q . '%')
                ->orWhere('last_name', 'LIKE', '%' . $request->q . '%')
                ->orWhere('email', 'LIKE', '%' . $request->q . '%')
                ->paginate(2500);
        }

        return view('backend.auth.user.index')->with(['users' => $users]);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        return view('backend.auth.user.create')
            ->withRoles($roleRepository->with('permissions')->get(['id', 'name']))
            ->withPermissions($permissionRepository->get(['id', 'name']));
    }

    /**
     * @param StoreUserRequest $request
     *
     * @throws \Throwable
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->create($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'active',
            'confirmed',
            'confirmation_email',
            'roles',
            'permissions'
        ));

        if($request->filled('tour_operator_zone_name') && $request->filled('tour_operator_zone_location')){
            // create agency
            DB::table('tour_operator_zones')
                ->insert([
                   'name' => $request->tour_operator_zone_name,
                   'location' => $request->tour_operator_zone_location,
                    'phone_number' => $request->phone_number,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'operator_user_id' => $user->id,
                ]);
        }

        if($request->filled('client_id')){
            $ecs_client_user = new EcsClientUser();
            $ecs_client_user->client_id = $request->client_id;
            $ecs_client_user->user_id = $user->id;
            $ecs_client_user->position = $request->position;
            $ecs_client_user->save();

            return redirect()->back()->withFlashSuccess('Client User Created');
        }

        if($request->filled('firm')){
            $lawyer = new LegalTeamExternalLawyer();
            $lawyer->firm = $request->firm;
            $lawyer->notes = $request->notes;
            $lawyer->user_id = $user->id;
            $lawyer->save();

            $user->assignRole('External Lawyer');
            return redirect()->route('frontend.legal_team_external_lawyers.index')->withFlashSuccess('External Lawyer User Account Created');
        }

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.created'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @return mixed
     */
    public function show(ManageUserRequest $request, User $user)
    {
        return view('backend.auth.user.show')
            ->withUser($user);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param User                 $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository, User $user)
    {
        $business_score_area = BusinessArea::all();
        $accessible_biz_areas = $user->accessibleBusinessAreas();
        $accessible_biz_areas_ids = [];
        foreach($accessible_biz_areas as $accessible_biz_area){
            $accessible_biz_areas_ids[] = $accessible_biz_area->id;
        }

        return view('backend.auth.user.edit', compact('business_score_area', 'accessible_biz_areas_ids'))
            ->withUser($user)
            ->withRoles($roleRepository->get())
            ->withUserRoles($user->roles->pluck('name')->all())
            ->withPermissions($permissionRepository->get(['id', 'name']))
            ->withUserPermissions($user->permissions->pluck('name')->all());
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userRepository->update($user, $request->only(
            'first_name',
            'last_name',
            'email',
            'roles',
            'permissions'
        ));

        if($request->filled('staff_ara_id') && $request->filled('co_presentings') && count($request->co_presentings)){
            $staff_ara_id = $request->staff_ara_id;
            CoPresenter::where('staff_ara_id', $staff_ara_id)->delete();
            foreach($request->co_presentings as $co_presenting){
                $co_presenter = new CoPresenter();
                $co_presenter->staff_ara_id = $staff_ara_id;
                $co_presenter->business_area_id = $co_presenting;
                $co_presenter->save();
            }
        }

        if($request->filled('client_user_id')){
            $client_user = EcsClientUser::find($request->client_user_id);
            if($client_user) {
                $client_user->position = $request->position;
                $client_user->save();
            }
            return redirect()->back()->withFlashSuccess('Client User Updated');
        }

//        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.updated'));
        return redirect()->back()->withFlashSuccess(__('alerts.backend.users.updated'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @throws \Exception
     * @return mixed
     */
    public function destroy(ManageUserRequest $request, User $user)
    {
        // delete any staff account related to this user
        StaffMember::where('email', $user->email)
            ->delete();

        if($request->filled('client_user_id')){
            EcsClientUser::where('user_id', $user->id)->delete();
            return redirect()->back()->withFlashSuccess('Client User Deleted');
        }


        $this->userRepository->deleteById($user->id);

        event(new UserDeleted($user));


        return redirect()->route('admin.auth.user.deleted')->withFlashSuccess(__('alerts.backend.users.deleted'));
    }


}
