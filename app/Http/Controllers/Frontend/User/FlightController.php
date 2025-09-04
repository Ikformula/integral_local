<?php
namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\EcsBooking;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index(EcsBooking $item)
    {
        return response()->json($item->flights()->get());
    }

    public function store(Request $request, EcsBooking $item)
    {
        $data = $request->all();
        $data['ecs_booking_id'] = $item->id;
        $flight = Flight::create($data);
        return response()->json($flight);
    }

    public function update(Request $request, EcsBooking $item, Flight $flight)
    {
        $flight->update($request->all());
        return response()->json($flight);
    }

    public function destroy(EcsBooking $item, Flight $flight)
    {
        $flight->delete();
        return response()->json(['success' => true]);
    }
}
