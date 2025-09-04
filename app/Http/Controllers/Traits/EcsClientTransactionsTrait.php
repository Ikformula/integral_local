<?php


namespace App\Http\Controllers\Traits;


use App\Models\EcsClient;
use App\Models\EcsClientAccountSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait EcsClientTransactionsTrait
{
    public function summariesList(Request $request, $other_params = [])
    {
        $client = null;
        if($request->has('client_id') && $request->client_id != 'All')
            $client = EcsClient::find($request->client_id);

        if(isset($other_params['client']))
            $client = $other_params['client'];

//        if(isset($client)){
//            $earliest_summary = EcsClientAccountSummary::where('client_id', $client->id)->orderBy('created_at', 'ASC')->first();
//        }else{
//            $earliest_summary = EcsClientAccountSummary::whereNotNull('id')->orderBy('created_at', 'ASC')->first();
//        }
//        if(!isset($earliest_summary))
            $earliest_summary = EcsClientAccountSummary::whereNotNull('id')->orderBy('for_date', 'ASC')->first();
        $earliest_date = $earliest_summary->for_date;

        if($request->filled('from_date')){
            $validated = $request->validate([
                'from_date' => ['before:tomorrow']
            ]);
            $from_date_temp = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
        } else {
            $from_date_temp = $earliest_date;
        }

        if($request->filled('to_date')){
            $validated = $request->validate([
                'to_date' => ['before:tomorrow', 'after_or_equal:from_date']
            ]);
            $to_date_temp = Carbon::createFromFormat('Y-m-d', $request->to_date)->endOfDay();
        } else {
            $to_date_temp = Carbon::today()->endOfDay();
        }

        $from_date = $from_date_temp->copy();
        $to_date = $to_date_temp->copy();
        $query = EcsClientAccountSummary::query()
            ->whereBetween('for_date', [$from_date_temp, $to_date_temp]);

        if(isset($client))
            $query->where('client_id', $client->id);

        $filterSwitch = $request->input('filterSwitch', 'allStatusesSwitch');
        if($filterSwitch !== 'allStatusesSwitch') {
            if ($filterSwitch === 'disputeSwitch') {
                $query->whereNotNull('client_disputed_at');
            } elseif ($filterSwitch === 'approvedSwitch') {
                $query->whereNotNull('client_approved_at');
            } elseif ($filterSwitch === 'unattendedSwitch') {
                $query->whereNull('client_approved_at')->whereNull('client_disputed_at');
            }
        }

        $items = $query->orderBy('for_date', 'DESC')->get();

        if($client) {
            return view('frontend.ecs_external.account_summaries')->with([
                'items' => $items,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'params' => $request->query(),
                'earliest_date' => $earliest_date,
                'client' => $client
            ]);
        }

        return view('frontend.ecs_client_account_summaries.index')->with([
            'items' => $items,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'params' => $request->query(),
            'earliest_date' => $earliest_date
        ]);
    }

    public function addTransactionSummary(EcsClient $client, $amount, $summarisable = null, $type = 'credit', $source = null, $details = null)
    {
        $summary = new EcsClientAccountSummary();
        if(isset($summarisable)) {
            $summary->summarisable_id = $summarisable->id;
            $summary->summarisable_type = get_class($summarisable);
        }
        if($type == 'credit'){
            $summary->credit_amount = $amount;
            $working_amount = $amount;
        } else {
            $summary->debit_amount = $amount;
            $working_amount = -1 * $amount;
        }

        if($summary->summarisable_type == "App\Models\EcsRefund"){
            $summary->details = $details ?? 'REFUND: '. $summarisable->first_name;
            $summary->ticket_number = $summarisable->ticket_number;
            $summary->source = $source ?? 'FLIGHT TICKET BOOKING';
        }elseif($summary->summarisable_type == "App\Models\EcsFlightTransaction"){
            $summary->details = $details ?? 'REFUND: '. $summarisable->first_name;
            $summary->ticket_number = $summarisable->ticket_number;
            $summary->source = $source ?? 'REFUND';
        }elseif($summary->summarisable_type == "App\Models\EcsClient"){
            $summary->details = $details ?? 'Account Initialization';
            $summary->source = $source ?? 'ACCOUNT OPENING';
        }elseif($summary->summarisable_type == "App\Models\EcsClientAccountSummary" || $source == 'summary'){
            $summary->details = $details ?? 'Manual Summary Creation';
            $summary->source = $source ?? 'Balance Transaction';
        }else{
            $summary->source = $source;
        }

        if(isset($summarisable)){
            if(isset($summarisable->for_date)){
                $summary->for_date = $summarisable->for_date;
            }else{
                $summary->for_date = $summarisable->for_date->toDateString();
            }
        }else{
            $summary->for_date = $for_date ?? now(); // TODO: $for_date is not defined anywhere: Sept. 3rd, 2025
        }

        // Find the latest summary before or on for_date
        $latest_before = EcsClientAccountSummary::where('client_id', $client->id)
            ->where('for_date', '<=', $summary->for_date)
            ->orderBy('for_date', 'desc')
            ->first();
        $base_balance = $latest_before ? $latest_before->balance : 0;
        $summary->balance = $base_balance + $working_amount;
        $summary->client_id = $client->id;
        $summary->agent_user_id = auth()->id();
        $summary->save();

        // Update all later summaries for this client
        EcsClientAccountSummary::where('client_id', $client->id)
            ->where('for_date', '>', $summary->for_date)
            ->orderBy('for_date', 'asc')
            ->get()
            ->each(function($item) use ($working_amount) {
                $item->balance += $working_amount;
                $item->save();
            });

        // Update client current balance
        $client->current_balance += $working_amount;
        $client->save();
    }

    public function addDisputeMessage(EcsClientAccountSummary $summary, $msg = '')
    {
        if($msg == '' || is_null($msg))
            return false;

        $sender = auth()->user();
        $current_messages = $summary->messages;
        if(!empty($current_messages) && checkIsJson($current_messages)) {
            $current_messages_arr = json_decode($current_messages);
        }else{
            $current_messages_arr = [];
        }

            $current_messages_arr[] = [
                'sender_id' => $sender->id,
                'sender_name' => $sender->full_name,
                'sent_at' => now(),
                'sent_at_human' => now()->toDayDateTimeString(),
                'message' => $msg,
                'summary_id' => $summary->id
            ];

        $summary->messages = json_encode($current_messages_arr);
        $summary->save();

        return true;
    }

    public function totalSalesStat($params = [])
    {
        // Code for getting total sales statistics
        $query = EcsClientAccountSummary::query();
        if(isset($params['client_id']) && !empty($params['client_id'])) {
            $query->where('client_id', $params['client_id']);
        }
        if(isset($params['from_date']) && !empty($params['from_date'])) {
            $query->whereDate('for_date', '>=', $params['from_date']);
        }
        if(isset($params['to_date']) && !empty($params['to_date'])) {
            $query->whereDate('for_date', '<=', $params['to_date']);
        }
        if (isset($params['agent_user_id']) && !empty($params['agent_user_id'])) {
            $query->where('agent_user_id', $params['agent_user_id']);
        }

        $totalCredit = $query->sum('credit_amount');
        $totalDebit = $query->sum('debit_amount');
//        $totalBalance = $query->sum('balance');

        return [
            'total_credit' => $totalCredit,
            'total_debit' => $totalDebit,
//            'total_balance' => $totalBalance,
            'params' => $params
        ];
    }

    public function numSummaries($params = [])
    {
        // Code for getting total sales statistics
        $query = EcsClientAccountSummary::query();
        if(isset($params['client_id']) && !empty($params['client_id'])) {
            $query->where('client_id', $params['client_id']);
        }
        if(isset($params['from_date']) && !empty($params['from_date'])) {
            $query->whereDate('for_date', '>=', $params['from_date']);
        }
        if(isset($params['to_date']) && !empty($params['to_date'])) {
            $query->whereDate('for_date', '<=', $params['to_date']);
        }
        if (isset($params['agent_user_id']) && !empty($params['agent_user_id'])) {
            $query->where('agent_user_id', $params['agent_user_id']);
        }

        $approvedQuery = clone $query;
        $unapprovedQuery = clone $query;

        $numApproved = $approvedQuery->whereNotNull('client_approved_at')->count();
        $numPending = $unapprovedQuery->whereNull('client_approved_at')->whereNull('client_disputed_at')->count();
        $numDisputed = $query->whereNotNull('client_disputed_at')->whereNull('client_approved_at')->count();

        return [
            'total_approved' => $numApproved,
            'total_pending' => $numPending,
            'total_disputed' => $numDisputed,
            'params' => $params
        ];
    }


}
