<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsClient;
use App\Models\EcsFlight;
use App\Models\EcsFlightTransaction;
use App\Models\EcsTransactionTax;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EcsClientTransactionsTrait;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;


class EcsFlightTransactionAjaxController extends Controller
{
    use EcsClientTransactionsTrait;

    public function show($id)
    {
        $ecs_flight_transaction = EcsFlightTransaction::with(['flights', 'taxes', 'client_idRelation', 'agentUser'])->findOrFail($id);
        return view('frontend.ecs_flight_transactions.show', compact('ecs_flight_transaction'));
    }

    public function index(Request $request)
    {
        $query = EcsFlightTransaction::with([
            //            'ecs_booking_idRelation',
            'client',
            //            'client_approver_idRelation',
            'agentUser',
        ]);

        $logged_in_user = auth()->user();
        $userRoles = $logged_in_user->roles->pluck('name')->toArray();
        $isSupervisorOrSuperUser = in_array('ecs supervisor', $userRoles) || in_array('ecs super user', $userRoles) || $logged_in_user->isAdmin();
        $showAgent = !$isSupervisorOrSuperUser;

        // If not supervisor/super user, only show records entered by the logged in user
        if (!$isSupervisorOrSuperUser) {
            $query->where('agent_user_id', $logged_in_user->id);
        }

        // Handle menu-driven filter logic
        $filter = $request->has('filter') ? $request->input('filter') : 'view';
        switch ($filter) {
            case 'view':
                // Default: show all except internally approved (unless checkbox checked)
                if (!$request->boolean('include_internally_approved', false)) {
                    $query->whereNull(['internal_approved_at', 'pushed_to_reconciliation_at', 'client_approved_at']);
                }
                break;
            case 'refunds':
                $query->where('source', 'REFUND');
                break;
            case 'push_to_reconciliation':
                // Show transactions that are client approved but not yet pushed to reconciliation
                $query->whereNull('client_approved_at')
                    ->whereNull('pushed_to_reconciliation_at');
                break;
            case 'reverse':
                // Show transactions that have been pushed to reconciliation
                $query->whereNotNull('pushed_to_reconciliation_at')
                    ->whereNull('internal_approved_at');
                break;
            case 'disapproved':
                // Show transactions that have been rejected internally
                $query->whereNotNull('rejected_internally_at');
                break;
            case 'verify':
                // Show transactions that are not yet internally approved and not rejected
                $query->whereNull('internal_approved_at')
                    ->whereNull('rejected_internally_at');
                break;
            case 'push_to_client':
                // Show transactions that are internally approved but not yet pushed to client
                $query->whereNotNull('internal_approved_at')
                    ->whereNull('pushed_to_client_at');
                break;
            default:
                // Default: show all except internally approved (unless checkbox checked)
                if (!$request->boolean('include_internally_approved', false)) {
                    $query->whereNull('internal_approved_at');
                }
                break;
        }

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('for_date', [$request->from_date, $request->to_date]);
        }

        // Client filter
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Agent filter (if allowed)
        if ($showAgent && $request->filled('agent_user_id')) {
            $query->where('agent_user_id', $request->agent_user_id);
        }

        $ecs_flight_transactions = $query->orderByDesc('for_date')->paginate(100)->appends($request->all());

        // For filter dropdowns
        $clients = \App\Models\EcsClient::all();
        $agents = $showAgent ? \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('name', 'ecs agent');
        })->get() : collect();

        return view('frontend.ecs_flight_transactions.index', compact('ecs_flight_transactions', 'clients', 'agents', 'showAgent', 'isSupervisorOrSuperUser'));
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
        if ($trx->is_cancelled == 'no') {
            $amount = $booking->ticket_fare + $booking->penalties + $trx->service_fee + $booking->totalTaxes();
            $this->addTransactionSummary($booking->client_idRelation, $amount, $trx, 'debit');
        }

        return back()->withFlashSuccess('Flight Transaction created successfully.');
    }

    public function storeGroup(Request $request)
    {
        try {
            $duplicates = [];
            $created = [];

            // Calculate number of valid tickets
            $validTickets = [];
            if ($request->has('pax_names') && $request->has('ticket_numbers')) {
                foreach ($request->pax_names as $i => $pax_name) {
                    $ticket_number = $request->ticket_numbers[$i] ?? null;
                    if ($pax_name && $ticket_number) {
                        $validTickets[] = [
                            'name' => $pax_name,
                            'ticket_number' => $ticket_number,
                            'index' => $i
                        ];
                    }
                }
            }

            $n = count($validTickets);

            // Determine ticket_fare per ticket
            $ticket_fare = $request->ticket_fare;
            $amount_refundable = $request->amount_refundable;
            if ($n > 0 && $amount_refundable && is_numeric($amount_refundable)) {
                $ticket_fare = round($amount_refundable / $n, 2);
            }

            // Save tickets (passengers)
            foreach ($validTickets as $ticket) {
                $i = $ticket['index'];
                $pax_name = $ticket['name'];
                $ticket_number = $ticket['ticket_number'];
                $existing = EcsFlightTransaction::where('ecs_booking_id', $request->ecs_booking_id)
                    ->where('name', $pax_name)
                    ->where('ticket_number', $ticket_number)
                    ->where('client_id', $request->client_id)
                    ->where('booking_reference', $request->booking_reference)
                    ->first();
                if (!$existing) {
                    // Collect known fee fields so client fees are persisted on the transaction
                    $feeFields = [
                        'no_show_fee',
                        'excess_baggage_charge',
                        'date_change_fee',
                        'name_change_fee',
                        'reroute_fee',
                    ];

                    $feeData = [];
                    foreach ($feeFields as $ff) {
                        // Use 0 as default to ensure calculations work predictably
                        $feeData[$ff] = $request->has($ff) ? $request->input($ff) : 0;
                    }

                    $trx = EcsFlightTransaction::create(array_merge([
                        'ecs_booking_id' => $request->id,
                        'client_id' => $request->client_id,
                        'name' => $pax_name,
                        'ticket_number' => $ticket_number,
                        'booking_reference' => $request->booking_reference,
                        'service_fee' => $request->service_fee,
                        'ticket_fare' => $ticket_fare,
                        'penalties' => $request->penalties,
                        'for_date' => $request->for_date,
                        'remarks' => $request->remarks,
                        'agent_user_id' => $request->agent_user_id,
                        'source' => $request->source ?? 'TICKET SALE',
                        'no_show_fee' => $request->no_show_fee ?? 0,
                        'excess_baggage_charge' => $request->excess_baggage_charge ?? 0,
                        'date_change_fee' => $request->date_change_fee ?? 0,
                        'name_change_fee' => $request->name_change_fee ?? 0,
                        'reroute_fee' => $request->reroute_fee ?? 0,
                        'category' => $request->category,
                        'is_cancelled' => 'no',
                        'trx_id' => Str::uuid()->toString(),
                    ], $feeData));
                    $created[] = $trx;

                    // Save flights (segments)
                    if ($request->has('depart_from') && $request->has('arrive_at')) {
                        foreach ($request->depart_from as $j => $depart_from) {
                            $arrive_at = $request->arrive_at[$j] ?? null;
                            $departure_time = $request->departure_time[$j] ?? null;
                            if ($depart_from && $arrive_at && $request->flight[$j] && $request->class[$j]) {
                                EcsFlight::create([
                                    'ecs_transaction_id' => $trx->id,
                                    'booking_reference' => $request->booking_reference,
                                    'flight' => $request->flight[$j] ?? null,
                                    'class' => $request->class[$j] ?? null,
                                    'flight_date' => $request->flight_date[$j] ?? null,
                                    'depart_from' => $depart_from,
                                    'departure_time' => $departure_time,
                                    'arrive_at' => $arrive_at,
                                    'client_id' => $trx->client_id,
                                ]);
                            }
                        }
                    }

                    // Save taxes
                    if ($request->filled('tax')) {
                        foreach ($request->tax as $tax_name => $amount) {
                            if (!isset($amount)) continue;
                            $booking_tax = new EcsTransactionTax();
                            //                            $booking_tax->client_id = $request->client_id;
                            $booking_tax->transaction_id = $trx->id;
                            $booking_tax->tax_name = $tax_name;
                            $booking_tax->amount = $amount;
                            $booking_tax->save();
                        }
                    }

                    // Push to Summaries
                    $type = $trx->source == 'REFUND' ? 'credit' : 'debit';
//                    logger('trx id: '.$trx->id);
                    $summary = $this->addTransactionSummary($trx->client, $trx->totalAmount(), $trx, $type);
//                    logger('summary: '.$summary->id.' |Bal: '.$summary->balance);
                    if($summary){
                        continue;
                    }
                } else {
                    $duplicates[] = $existing;
                }
            }



            // Use the last created transaction to get the booking
            if (!empty($created) && $created[count($created) - 1]->ecsBooking) {
                return redirect()->route('frontend.ecs_bookings.show', $created[count($created) - 1]->ecsBooking->id)
                    ->withFlashSuccess('Request created successfully! ' . (count($duplicates) ? count($duplicates) . ' duplicates not saved.' : ''));
            } else {
                return redirect()->route('frontend.ecs_flight_transactions.index')->withFlashSuccess('Request created successfully! ' . (count($duplicates) ? count($duplicates) . ' duplicates not saved.' : ''));
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating Request: ' . $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $ecs_flight_transaction = EcsFlightTransaction::with(['flights', 'taxes'])->findOrFail($id);
        return view('frontend.ecs_flight_transactions.edit', compact('ecs_flight_transaction'));
    }

    public function update(Request $request, $id)
    {
        $ecs_flight_transaction = EcsFlightTransaction::with(['flights', 'taxes'])->findOrFail($id);
        if (is_null($ecs_flight_transaction->client_approved_at)) {
            // Capture old values for summary update
            $oldValues = [
                'client_id' => $ecs_flight_transaction->client_id,
                'amount' => $ecs_flight_transaction->totalAmount(),
                'for_date' => $ecs_flight_transaction->for_date,
            ];

            // Allow updating of fee fields as well
            $ecs_flight_transaction->update($request->only([
                'client_id',
                'name',
                'ticket_number',
                'booking_reference',
                'penalties',
                'ticket_fare',
                'service_fee',
                'for_date',
                'remarks',
                'no_show_fee',
                'excess_baggage_charge',
                'date_change_fee',
                'name_change_fee',
                'reroute_fee',
            ]));

            // Update flights
            if ($request->has('flights')) {
                foreach ($request->flights as $flightData) {
                    if (isset($flightData['id'])) {
                        $flight = $ecs_flight_transaction->flights->where('id', $flightData['id'])->first();
                        if ($flight) {
                            $flight->update([
                                'flight' => $flightData['flight'] ?? null,
                                'class' => $flightData['class'] ?? null,
                                'flight_date' => $flightData['flight_date'] ?? null,
                                'depart_from' => $flightData['depart_from'] ?? null,
                                'departure_time' => $flightData['departure_time'] ?? null,
                                'arrive_at' => $flightData['arrive_at'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Update taxes
            if ($request->has('taxes')) {
                foreach ($request->taxes as $taxData) {
                    if (isset($taxData['id'])) {
                        $tax = $ecs_flight_transaction->taxes->where('id', $taxData['id'])->first();
                        if ($tax) {
                            $tax->update([
                                //                                'tax_name' => $taxData['tax_name'] ?? null,
                                'amount' => $taxData['amount'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Update summaries after transaction update
            $this->updateSummaries($ecs_flight_transaction, $oldValues);

            //            return redirect()->route('frontend.ecs_flight_transactions.index')
            return redirect()->back()
                ->withFlashSuccess('Flight Transaction updated successfully.');
        }
        return back()->withFlashWarning('Transaction updates not allowed after client approval');
    }

    public function cancelFlightTransaction(Request $request, $id)
    {
        $ecs_flight_transaction = EcsFlightTransaction::findOrFail($id);
        if (is_null($ecs_flight_transaction->client_approved_at)) {
            $ecs_flight_transaction->is_cancelled = 'yes';
            $ecs_flight_transaction->cancel_comment = $request->input('cancel_comment');
            $ecs_flight_transaction->save();


//            if ($ecs_flight_transaction->pushed_to_client_at) {
                //                $amount = $ecs_flight_transaction->ticket_fare + $ecs_flight_transaction->penalties + $ecs_flight_transaction->service_fee + $ecs_flight_transaction->totalTaxes();
                //                $this->addTransactionSummary($ecs_flight_transaction->client, $amount, $ecs_flight_transaction, 'credit', 'Cancellation');

                // Remove summary and update balances
                $update = $this->updateSummaries($ecs_flight_transaction);
//            }

//            $ecs_flight_transaction->delete();

            return redirect()->route('frontend.ecs_flight_transactions.index')
                ->withFlashSuccess('Flight Transaction cancelled successfully.');
        }
        return back()->withFlashWarning('Transaction updates not allowed after client approval');
    }

    public function verify($id)
    {
        $trx = EcsFlightTransaction::findOrFail($id);
        if (!$trx->internal_approved_at) {
            $trx->internal_approved_at = now();
            $trx->internal_approver_id = auth()->id();
            $trx->rejected_internally_at = null;
            $trx->rejected_internally_by_user_id = null;
            $trx->rejection_comment = null;
            $trx->pushed_to_client_at = now();
            $trx->pushed_to_client_by_user_id = auth()->id();
            $trx->save();
            return redirect()->back()->withFlashSuccess('Transaction verified successfully.');
        }
        return redirect()->back()->withFlashWarning('Transaction already verified.');
    }

    public function bulkVerify(Request $request)
    {
        $ids = json_decode($request->input('transaction_ids', '[]'), true);

        if (empty($ids)) {
            return redirect()->back()->withFlashWarning('No transactions selected.');
        }

        $now = now();
        $userId = auth()->id();

        // Update only transactions that are NOT already approved
        $updatedCount = DB::table('ecs_flight_transactions')
            ->whereIn('id', $ids)
            ->whereNull('internal_approved_at')
            ->update([
                'internal_approved_at' => $now,
                'internal_approver_id' => $userId,
                'rejected_internally_at' => null,
                'rejected_internally_by_user_id' => null,
                'rejection_comment' => null,
                'pushed_to_client_at' => $now,
                'pushed_to_client_by_user_id' => $userId,
                'updated_at' => $now // update timestamp
            ]);

        if ($updatedCount === 0) {
            return redirect()->back()->withFlashWarning('No unverified transactions found in selection.');
        }

        return redirect()->back()->withFlashSuccess("$updatedCount transaction(s) verified successfully.");
    }



    public function reject(Request $request, $id)
    {
        $trx = EcsFlightTransaction::findOrFail($id);
        if (!$trx->internal_approved_at) {
            $trx->rejected_internally_at = now();
            $trx->rejected_internally_by_user_id = auth()->id();
            $trx->rejection_comment = $request->input('rejection_comment');
            $trx->internal_approved_at = null;
            $trx->internal_approver_id = null;
            $trx->pushed_to_reconciliation_at = null;
            $trx->save();
            return redirect()->back()->withFlashSuccess('Transaction rejected successfully.');
        }
        return redirect()->back()->withFlashWarning('Transaction already verified.');
    }

    public function pushToClient($id)
    {
        $trx = EcsFlightTransaction::with(['client'])->findOrFail($id);
        if ($trx->internal_approved_at && !$trx->pushed_to_client_at) {
            $trx->pushed_to_client_at = now();
            $trx->pushed_to_client_by_user_id = auth()->id();
            $trx->save();
            // Add transaction summary for client push
//            $amount = $trx->ticket_fare + $trx->penalties + $trx->service_fee + $trx->totalTaxes();
//            $type = $trx->source == 'REFUND' ? 'credit' : 'debit';
//            $this->addTransactionSummary($trx->client, $amount, $trx, $type, '');

            return redirect()->back()->withFlashSuccess('Transaction pushed to client successfully.');
        }
        return redirect()->back()->withFlashWarning('Transaction cannot be pushed. It may already be pushed or not yet approved.');
    }

    public function recall($id)
    {
        $trx = EcsFlightTransaction::findOrFail($id);
        $trx->pushed_to_client_at = null;
        $trx->pushed_to_client_by_user_id = null;
        $trx->internal_approved_at = null;
        $trx->internal_approver_id = null;
        $trx->save();
        return redirect()->back()->withFlashSuccess('Transaction recalled from the client successfully.');
    }

    public function pushToReconciliation($id)
    {
        $trx = EcsFlightTransaction::findOrFail($id);
        if (!$trx->client_approved_at && !$trx->pushed_to_reconciliation_at) {
            $trx->pushed_to_reconciliation_at = now();
            $trx->save();
            return redirect()->back()->withFlashSuccess('Transaction pushed to reconciliation successfully.');
        }
        return redirect()->back()->withFlashWarning('Transaction cannot be pushed. It may already be pushed or approved by client.');
    }

    public function reverseFromReconciliation($id)
    {
        $trx = EcsFlightTransaction::findOrFail($id);
        if ($trx->pushed_to_reconciliation_at) {
            $trx->pushed_to_reconciliation_at = null;
            $trx->save();
            return redirect()->back()->withFlashSuccess('Transaction reversed from reconciliation successfully.');
        }
        return redirect()->back()->withFlashWarning('Transaction is not in reconciliation.');
    }

    public function ticketLogClientSelection()
    {
        $clients = EcsClient::all();
        return view('frontend.ecs_flight_transactions.select_client-ticket-log', compact('clients'));
    }

    public function ticketLogForClient(Request $request)
    {
        $client = EcsClient::findOrFail($request->client_id);
        $summaries = $this->summariesList($request, ['view' => 'ticket log']);
        return view('frontend.ecs_flight_transactions.ticket-log', compact('client'))->with($summaries);
    }

    public function destroy($id)
    {
        $trx = EcsFlightTransaction::findOrFail($id);
        $trx->is_cancelled = 'yes';
        $trx->save();
        $this->updateSummaries($trx);
        $trx->delete();
        return back()->withFlashSuccess('Flight Transaction deleted successfully.');
    }
}
