<?php

namespace App\Http\Controllers\Backend\Auth\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('backend.auth.permission.index')->withPermissions($permissions);
    }

    public function store(Request $request)
    {
        $permission = Permission::create($request->all());
        return back()->withFlashSuccess('Permission added');
    }

    public function permissionUsers(Permission $permission)
    {
        return view('backend.auth.permission.users')->with([
           'users' => $permission->users,
           'permission' => $permission
        ]);
    }
}
