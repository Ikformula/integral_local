<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\TourOperationLocationStaffUser;
use Illuminate\Http\Request;
use App\Models\Passenger;
use Illuminate\Support\Facades\DB;

class TourOperationsController extends Controller
{

    public function pendings(){
        $user = auth()->user();
        $locations = TourOperationLocationStaffUser::where('user_id', $user->id)->pluck('location');
        if($user->can('access all tour booking location data')){
            $passengers = Passenger::where('ticket_id', null)->where('ticket_id', null)->paginate(20);
        }else {
            $passengers = Passenger::where('ticket_id', null)->whereIn('location', $locations)->where('ticket_id', null)->paginate(20);
        }

//        $passengers = Passenger::where('ticket_id', null)->where('currently_opened_by_user_id', null)->paginate(20);
        return view('frontend.tour_operations.index')->with([
            'passengers' => $passengers
        ]);
    }


    public function completed(){
        $user = auth()->user();
        $locations = TourOperationLocationStaffUser::where('user_id', $user->id)->pluck('location');
        if($user->can('access all tour booking location data')){
            $passengers = Passenger::where('ticket_id', null)->where('ticket_id', '!=', null)->paginate(20);
        }else {
            $passengers = Passenger::where('ticket_id', null)->whereIn('location', $locations)->where('ticket_id', '!=', null)->paginate(20);
        }

        return view('frontend.tour_operations.index')->with([
            'passengers' => $passengers,
            'diff_title' => 'Completed Bookings'
        ]);
    }

    public function myCurrentlyOpened(){
        $passengers = Passenger::where('ticket_id', null)->where('currently_opened_by_user_id', auth()->id())->paginate(20);
        return view('frontend.tour_operations.index')->with([
            'passengers' => $passengers,
            'diff_title' => 'List of Records Currently Opened By Me'
        ]);
    }

    public function update(Request $request, Passenger $passenger){
        $passenger->update($request->all());

        if($request->filled('ticket_id')){
            $passenger->currently_opened_by_user_id = null;
            $passenger->save();

            $now = now();

            DB::table('passenger_viewing_logs')
                ->where('passenger_id', $passenger->id)
                ->where('user_id', auth()->id())
                ->update([
                    'closed_by_user_at' => $now,
                    'updated_at' => $now,
                ]);
        }

        return redirect()->route('frontend.tour_operations.passengers.list');
    }

    public function unlock(Passenger $passenger){
        $passenger->currently_opened_by_user_id = null;
        $passenger->save();

        $now = now();

        DB::table('passenger_viewing_logs')
            ->where('passenger_id', $passenger->id)
            ->where('user_id', auth()->id())
            ->update([
                'closed_by_user_at' => $now,
                'updated_at' => $now,
            ]);

        return redirect()->route('frontend.tour_operations.passengers.list')->withFlashSuccess('Passenger record unhanded');
    }

    public function show(Passenger $passenger)
    {
        if($passenger->currently_opened_by_user_id && $passenger->currently_opened_by_user_id != auth()->id()){
            return back()->withFlashWarning('That passenger record is currently being worked on by '.$passenger->currentlyOpenedBy->full_name);
        }

        $now = now();
        $passenger->currently_opened_by_user_id = auth()->id();
        $passenger->save();
        DB::table('passenger_viewing_logs')
            ->insert([
                'user_id' => $passenger->currently_opened_by_user_id,
               'passenger_id' => $passenger->id,
               'opened_by_user_at' => $now,
               'created_at' => $now,
               'updated_at' => $now,
            ]);
        return view('frontend.tour_operations.show')->with([
            'passenger' => $passenger,
        ]);
    }

    public function checkLockStatus(Request $request)
    {
        $validated = $request->validate([
            'passenger_id' => ['required', 'numeric']
        ]);

        $pax = Passenger::find($validated['passenger_id']);
        if($pax){
            return ['status' => true, 'user' => $pax->currentlyOpenedBy->full_name];
        }

        return ['status' => false, 'user' => null];
    }
}
