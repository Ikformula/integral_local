<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsFlightTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EcsClientTransactionsTrait;

class EcsFlightTransactionAjaxController extends Controller
{
    use EcsClientTransactionsTrait;
    public function index()
    {
        $ecs_flight_transactions = EcsFlightTransaction::with([
            'ecs_booking_idRelation',
            'client_idRelation',
            'client_approver_idRelation',
        ])->get();

        return view('frontend.ecs_flight_transactions.index', compact('ecs_flight_transactions'));
    }

    public function store(Request $request)
    {
        // Prevent duplicate transactions
        $existing = EcsFlightTransaction::where('ecs_booking_id', $request->ecs_booking_id)
            ->where('name', $request->name)
            ->where('ticket_number', $request->ticket_number)
            ->where('client_id', $request->client_id)
            ->where('is_cancelled', $request->is_cancelled)
            ->where('service_fee', $request->service_fee)
            ->where('ticket_fare', $request->ticket_fare)
            ->where('penalties', $request->penalties)
            ->first();
        if ($existing) {
            return back()->withFlashWarning('Duplicate transaction detected. No record saved.');
        }

        $trx = EcsFlightTransaction::create($request->all());
        $booking = $trx->ecsBooking;
        if($trx->is_cancelled == 'no') {
            $amount = $booking->ticket_fare + $booking->penalties + $trx->service_fee + $booking->totalTaxes();
            $this->addTransactionSummary($booking->client_idRelation, $amount, $trx, 'debit');
        }

        return back()->withFlashSuccess('Flight Transaction created successfully.');
    }

    public function update(Request $request, $id)
    {
        $ecs_flight_transactions = EcsFlightTransaction::findOrFail($id);
        if(is_null($ecs_flight_transactions->client_approved_at)) {
            $ecs_flight_transactions->update($request->all());
            // add/subtract the difference from the following trxs
            return back()->withFlashSuccess('Flight Transaction updated successfully.');
        }
        return back()->withFlashWarning('Transaction updates not allowed after client approval');
    }

    public function destroy($id)
    {
        EcsFlightTransaction::destroy($id);
        // add/subtract that amount from the following transactions
        return back()->withFlashSuccess('Flight Transaction deleted successfully.');
    }
}
