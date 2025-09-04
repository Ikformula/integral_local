<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Passenger;
use App\Models\TourOperationLocationStaffUser;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class TourOperationsManagementController extends Controller
{
    public function index()
    {
        $permissionName = 'make tour bookings';
        $tros = User::permission($permissionName)->get();


        $location_operators = TourOperationLocationStaffUser::all();

        $locations = [
            'Abuja',
            'Abuja Silverbird',
            'Kano',
            'Lagos',
        ];

        foreach($locations as $location){
            $stats[$location] = [
                'title' => $location,
                'value' => Passenger::where('location', $location)->where('ticket_id', null)->count(),
                'icon' => 'book-open'
            ];
        }

        return view('frontend.tour_operations.location_staff_users')->with([
            'tros' => $tros,
            'location_operators' => $location_operators,
            'locations' => $locations,
            'stats' => $stats
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
           'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $count = TourOperationLocationStaffUser::where('location', $request->location)
            ->where('user_id', $request->user_id)
            ->count();
        if($count){
            return back()->withErrors('This Staff has already been assigned to that location');
        }
        $location_operator = new TourOperationLocationStaffUser();
        $location_operator->location = $request->location;
        $location_operator->user_id = $request->user_id;
        $location_operator->assigner_user_id = auth()->id();
        $location_operator->save();

        return back()->withFlashSuccess('Operator added successfully');
    }

    public function update(Request $request, TourOperationLocationStaffUser $tro)
    {
        $count = TourOperationLocationStaffUser::where('location', $request->location)
            ->where('user_id', $tro->user_id)
            ->count();
        if($count){
            return back()->withErrors('This Staff has already been assigned to that location');
        }

        $tro->location = $request->location;
        $tro->save();
        return back()->withFlashInfo('Operator updated successfully');
    }
    public function destroy(Request $request, TourOperationLocationStaffUser $tro)
    {
        $tro->delete();
        return back()->withFlashWarning('Operator deleted successfully');
    }
}
