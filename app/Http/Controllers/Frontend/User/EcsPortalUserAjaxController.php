<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\EcsPortalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EcsPortalUserAjaxController extends Controller
{
    private function getRoles()
    {
        $roles = ['ecs agent', 'ecs supervisor', 'ecs super user'];
        $db_roles = DB::table('roles')
            ->whereIn('name', $roles)
            ->get();

        $arranged_roles = [];
        foreach ($roles as $role){
            $db_role = $db_roles->where('name', $role)->first();
            $arranged_roles[$db_role->id] = $db_role->name;
        }

        return $arranged_roles;
    }
    public function index()
    {
        $ecs_portal_users = EcsPortalUser::with([
            'user_idRelation',
            'added_byRelation',
        ])->get();

        $roles = $this->getRoles();
        $users = User::all();

        return view('frontend.ecs_portal_users.index', compact('ecs_portal_users', 'roles', 'users'));
    }

    public function store(Request $request)
    {
        $user = User::find($request->user_id);
        if(!$user)
            return redirect()->back()->withErrors('Invalid User selected');

        $arr = $request->all();
        $roles = $this->getRoles();
        $arr['staff_ara_id'] = $user->staff_member ? $user->staff_member->staff_ara_id : null;
        $arr['role'] = $roles[$request->role_id];
        $arr['added_by'] = auth()->id();

        $user->assignRole($arr['role']);

        $existing = EcsPortalUser::where('user_id', $user->id)->first();
        if($existing){
            $existing->update($arr);
        }else {
            EcsPortalUser::create($arr);
        }
        return back()->withFlashSuccess('ECS User added successfully.');
    }

    public function update(Request $request, $id)
    {
        $ecs_portal_users = EcsPortalUser::findOrFail($id);
        $ecs_portal_users->update($request->all());
        return back()->withFlashSuccess('ECS Users updated successfully.');
    }

    public function destroy($id)
    {
        $ecs_role = EcsPortalUser::find($id);
        $user = $ecs_role->user_idRelation;
        if($user->hasRole($ecs_role->role))
            $user->removeRole($ecs_role->role);
        EcsPortalUser::destroy($id);
        return back()->withFlashSuccess('ECS User removed successfully.');
    }
}
