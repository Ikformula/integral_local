<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\SpiSector;
use App\Models\SpiSectorUserPermission;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpiPermissionController extends Controller
{
    public function index()
    {
        $sectors = SpiSector::all();
        $permissions = SpiSectorUserPermission::with(['sector', 'user'])->get();
        $users = User::where('id', '!=', 1)->orderBy('first_name', 'ASC')->get();

        return view('frontend.spi.spi_permissions_mgt', compact('sectors', 'permissions', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sector_id' => 'required|exists:spi_sectors,id',
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            // Check if permission already exists
            $exists = SpiSectorUserPermission::where('user_id', $request->user_id)
                ->where('sector_id', $request->sector_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission already exists for this user and sector'
                ], 422);
            }

            $permission = SpiSectorUserPermission::create([
                'sector_id' => $request->sector_id,
                'user_id' => $request->user_id
            ]);

            $user = User::find($request->user_id);
            $user->givePermissionTo('enter SPI data');
            $staff = $user ? $user->staff_member : null;

            return response()->json([
                'success' => true,
                'message' => 'Permission added successfully',
                'data' => $permission,
                'sector' => $permission->sector,
                'user' => $user,
                'staff' => $staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding permission'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sector_id' => 'required|exists:spi_sectors,id',
        ]);

        try {
            $permission = SpiSectorUserPermission::findOrFail($id);

            // Check if new permission combination already exists
            $exists = SpiSectorUserPermission::where('user_id', $permission->user_id)
                ->where('sector_id', $request->sector_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission already exists for this user and sector'
                ], 422);
            }

            $permission->update([
                'sector_id' => $request->sector_id
            ]);


            $user = User::find($permission->user_id);
            $user->givePermissionTo('enter SPI data');
            $staff = $user ? $user->staff_member : null;

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully',
                'data' => $permission,
                'sector' => $permission->sector,
                'user' => $user,
                'staff' => $staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating permission'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $permission = SpiSectorUserPermission::findOrFail($id);
            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting permission'
            ], 500);
        }
    }
}
