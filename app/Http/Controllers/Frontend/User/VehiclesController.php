<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\StaffMember;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    public function storeVehicle(Request $request)
    {
        $staff = StaffMember::where('staff_ara_id', $request->staff_ara_id)
            ->first();
        if(!$staff){
            return back()->withFlashDanger('Staff member with the ARA ID '.$request->staff_ara_id.' does not exist in our database');
        }

        $vehicle = Vehicle::create($request->all());

        if($vehicle){
            return back()->withFlashSuccess('Vehicle registered successfully');
        }else{
            return back()->withFlashDanger('Vehicle not registered');
        }
    }

    public function search(Request $request)
    {
        $vehicle = Vehicle::where('reg_number', $request->reg_num.'%')->first();
        if($vehicle){
            return [
                'found' => true,
                'vehicle' => $vehicle,
                'staff' => $vehicle->staffMember()
            ];
        }

        return [
            'found' => false
        ];
    }
}
