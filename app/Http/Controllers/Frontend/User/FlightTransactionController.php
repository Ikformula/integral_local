<?php
namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\FlightTransaction;
use App\Models\EcsBooking;
use Illuminate\Http\Request;

class FlightTransactionController extends Controller
{
    public function index(EcsBooking $item)
    {
        return response()->json($item->flightTransactions()->get());
    }

    public function store(Request $request, EcsBooking $item)
    {
        $data = $request->all();
        $data['ecs_booking_id'] = $item->id;
        $ft = FlightTransaction::create($data);
        return response()->json($ft);
    }

    public function update(Request $request, EcsBooking $item, FlightTransaction $flight_transaction)
    {
        $flight_transaction->update($request->all());
        return response()->json($flight_transaction);
    }

    public function destroy(EcsBooking $item, FlightTransaction $flight_transaction)
    {
        $flight_transaction->delete();
        return response()->json(['success' => true]);
    }
}
