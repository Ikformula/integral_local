<?php
namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsFlightTransaction;
use App\Models\EcsBooking;
use Illuminate\Http\Request;

class EcsFlightTransactionController extends Controller
{
    public function index(EcsBooking $item)
    {
        return response()->json($item->flightTransactions()->get());
    }

    public function store(Request $request, EcsBooking $item)
    {
        $data = $request->all();
        $data['ecs_booking_id'] = $item->id;
        $ft = EcsFlightTransaction::create($data);
        return response()->json($ft);
    }

    public function update(Request $request, EcsBooking $item, EcsFlightTransaction $flight_transaction)
    {
        $flight_transaction->update($request->all());
        return response()->json($flight_transaction);
    }

    public function destroy(EcsBooking $item, EcsFlightTransaction $flight_transaction)
    {
        $flight_transaction->delete();
        return response()->json(['success' => true]);
    }
}
