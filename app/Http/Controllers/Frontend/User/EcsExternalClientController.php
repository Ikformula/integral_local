<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsClient;
use App\Models\EcsClientAccountSummary;
use App\Models\EcsFlightTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EcsClientTransactionsTrait;

class EcsExternalClientController extends Controller
{
    use EcsClientTransactionsTrait;

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


        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('for_date', [$request->from_date, $request->to_date]);
        }

        // Client filter
        $user_id = auth()->id();
        $user = auth()->user();
        $client_user = $user->isEcsClient;
        if(!$client_user)
            return 'No client account found. Contact Arik Air Commercial team';
        $client = $client_user->client;
            $query->where('client_id', $client->id);

        // Agent filter (if allowed)
        if ($showAgent && $request->filled('agent_user_id')) {
            $query->where('agent_user_id', $request->agent_user_id);
        }

        $ecs_flight_transactions = $query->orderByDesc('for_date')->paginate(100)->appends($request->all());

        // For filter dropdowns
        $clients = EcsClient::where('id', $client->id);
        $agents = $showAgent ? \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('name', 'ecs agent');
        })->get() : collect();

        $is_client = true;

        return view('frontend.ecs_flight_transactions.index', compact('ecs_flight_transactions', 'clients', 'agents', 'showAgent', 'isSupervisorOrSuperUser', 'is_client'));
    }


    public function accountSummaries(Request $request)
    {
        $user_id = auth()->id();
        $user = auth()->user();
        $client_user = $user->isEcsClient;
        if(!$client_user)
            return 'No client account found. Contact Arik Air Commercial team';
        $client = $client_user->client;

        $earliest_summary = EcsClientAccountSummary::where('client_id', $client->id)->orderBy('created_at', 'ASC')->first();
        $earliest_date = $earliest_summary->created_at;

        if($request->filled('from_date')){
            $validated = $request->validate([
                'from_date' => ['before:today']
            ]);
            $from_date_temp = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
        } else {
            $from_date_temp = $earliest_date;
        }

        if($request->filled('to_date')){
            $validated = $request->validate([
                'to_date' => ['before:tomorrow', 'after:from_date']
            ]);
            $to_date_temp = Carbon::createFromFormat('Y-m-d', $request->to_date)->startOfDay();
        } else {
            $to_date_temp = Carbon::today();
        }

        $from_date = $from_date_temp->copy();
        $to_date = $to_date_temp->copy();
        $query = EcsClientAccountSummary::query()
            ->where('client_id', $client->id)
            ->whereBetween('created_at', [$from_date_temp, $to_date_temp]);

        if(!$request->has('allStatusesSwitch')) {
            if ($request->has('disputeSwitch')) {
                $query->whereNotNull('client_disputed_at');
            } elseif ($request->has('approvedSwitch')) {
                $query->whereNotNull('client_approved_at');
            } elseif ($request->has('unattendedSwitch')) {
                $query->whereNull('client_approved_at')->whereNull('client_disputed_at');
            }
        }

        $items = $query->orderBy('created_at', 'DESC')->get();

        return view('frontend.ecs_external.account_summaries')->with([
            'items' => $items,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'params' => $request->query(),
            'earliest_date' => $earliest_date,
            'client' => $client
        ]);

    }

    public function ticketLogForClient(Request $request)
    {
        $user_id = auth()->id();
        $user = auth()->user();
        $client_user = $user->isEcsClient;
        if(!$client_user)
            return 'No client account found. Contact Arik Air Commercial team';
        $client = $client_user->client;

        $summaries = $this->summariesList($request, ['view' => 'ticket log', 'client' => $client]);
        return view('frontend.ecs_flight_transactions.ticket-log', compact('client'))->with($summaries);
    }

    public function approveFlightTrx(EcsFlightTransaction $ecs_trx)
    {
        $ecs_trx->client_disputed_at = null;
        $ecs_trx->client_approved_at = now();
        $ecs_trx->approver_client_user_id = auth()->id();
        $client = $ecs_trx->client_idRelation;
        if($ecs_trx->source == 'REFUND') {
            $client->approved_balance += $ecs_trx->totalAmount();
        }else {
            $client->approved_balance -= $ecs_trx->totalAmount();
        }
        $client->save();
        $ecs_trx->save();

        return redirect()->back()->withFlashSuccess('Transaction Approved');
    }


    public function disputeFlightTrx(Request $request, EcsFlightTransaction $ecs_trx)
    {
        $ecs_trx->client_disputed_at = now();
        $ecs_trx->client_approved_at = null;
        $ecs_trx->approver_client_user_id = null;
        $ecs_trx->disputer_client_user_id = auth()->id();
        $ecs_trx->dispute_comment = $request->dispute_comment;
        $ecs_trx->save();

        return redirect()->back()->withFlashWarning('Transaction Disputed');
    }


    public function approveTrx(EcsClientAccountSummary $ecs_summary)
    {
        $ecs_summary->client_disputed_at = null;
        $ecs_summary->client_approved_at = now();
        $ecs_summary->approver_client_user_id = auth()->id();
        $client = $ecs_summary->client_idRelation;
        $client->approved_balance += $ecs_summary->credit_amount;
        $client->approved_balance -= $ecs_summary->debit_amount;
        $client->save();
        $ecs_summary->save();

        return redirect()->back()->withFlashSuccess('Transaction Approved');
    }

    public function disputeTrx(Request $request, EcsClientAccountSummary $ecs_summary)
    {
        if($request->filled('fresh_dispute')) {
            $ecs_summary->client_disputed_at = now();
            $ecs_summary->disputer_client_user_id = auth()->id();
            $ecs_summary->save();
        }

        // Store message
        $msg = $request->message;
        $msg_sent_status = $this->addDisputeMessage($ecs_summary, $msg);

        return redirect()->back()->withFlashSuccess('Transaction dispute updated');
    }

}
