<?php


namespace App\Http\Controllers\Traits;


use App\Models\EcsClient;
use App\Models\EcsClientAccountSummary;
use App\Models\EcsFlightTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait EcsClientTransactionsTrait
{
    public function summariesList(Request $request, $other_params = [])
    {
        $client = null;
        if ($request->has('client_id') && $request->client_id != 'All')
            $client = EcsClient::find($request->client_id);

        if (isset($other_params['client']))
            $client = $other_params['client'];

        //        if(isset($client)){
        //            $earliest_summary = EcsClientAccountSummary::where('client_id', $client->id)->orderBy('created_at', 'ASC')->first();
        //        }else{
        //            $earliest_summary = EcsClientAccountSummary::whereNotNull('id')->orderBy('created_at', 'ASC')->first();
        //        }
        //        if(!isset($earliest_summary))
        $earliest_date = Carbon::yesterday();
        $earliest_summary = EcsClientAccountSummary::whereNotNull('id')->orderBy('for_date', 'ASC')->first();
        if($earliest_summary)
            $earliest_date = $earliest_summary->for_date;

        if ($request->filled('from_date')) {
            $validated = $request->validate([
                'from_date' => ['before:tomorrow']
            ]);
            $from_date_temp = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
        } else {
            $from_date_temp = $earliest_date;
        }

        if ($request->filled('to_date')) {
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

        if (isset($client))
            $query->where('client_id', $client->id);

        $filterSwitch = $request->input('filterSwitch', 'allStatusesSwitch');
        if ($filterSwitch !== 'allStatusesSwitch') {
            if ($filterSwitch === 'disputeSwitch') {
                $query->whereNotNull('client_disputed_at');
            } elseif ($filterSwitch === 'approvedSwitch') {
                $query->whereNotNull('client_approved_at');
            } elseif ($filterSwitch === 'unattendedSwitch') {
                $query->whereNull('client_approved_at')->whereNull('client_disputed_at');
            }
        }

        $items = $query->orderBy('for_date', 'ASC')
//            ->orderBy('id', 'ASC')
            ->orderBy('updated_at', 'ASC')
            ->get();

        if(isset($other_params['view']) && $other_params['view'] == 'ticket log'){
            return [
                'items' => $items,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'params' => $request->query(),
                'earliest_date' => $earliest_date,
                'other_params' => $other_params
            ];
        }

        if ($client) {
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
            'earliest_date' => $earliest_date,
            'other_params' => $other_params
        ]);
    }

    public function addTransactionSummary(EcsClient $client, $amount, $summarisable = null, $type = 'credit', $source = null, $details = null)
    {
        $type = strtolower($type);
        $summary = new EcsClientAccountSummary();
        if (isset($summarisable)) {
            $summary->summarisable_id = $summarisable->id;
            $summary->summarisable_type = get_class($summarisable);
        }
        if ($type == 'credit') {
            $summary->credit_amount = $amount;
            $working_amount = $amount;
        } elseif ($type == 'debit' && $amount >= 0) {
            $summary->debit_amount = $amount;
            $working_amount = -1 * $amount;
        } elseif ($amount < 0){
            $type = 'debit';
            $summary->debit_amount = $amount;
            $working_amount = $amount;
        } else {
            $summary->debit_amount = $amount;
            $working_amount = -1 * abs($amount);
        }

        if ($summary->summarisable_type == "App\Models\EcsFlightTransaction" && isset($summary->source) && $summary->source == 'REFUND') {
            $summary->details = $details ?? 'REFUND: ' . $summarisable->name;
            $summary->ticket_number = $summarisable->ticket_number;
            $summary->source = $source ?? 'REFUND';
        } elseif ($summary->summarisable_type == "App\Models\EcsFlightTransaction") {
            $summary->details = $details ?? 'TICKET SALES: ' . $summarisable->name;
            $summary->ticket_number = $summarisable->ticket_number;
            $summary->source = $source ?? 'TICKET SALE';
        } elseif ($summary->summarisable_type == "App\Models\EcsClient") {
            $summary->details = $details ?? 'Account Initialization';
            $summary->source = $source ?? 'ACCOUNT OPENING';
        } elseif ($summary->summarisable_type == "App\Models\EcsClientAccountSummary" || $source == 'summary') {
            $summary->details = $details ?? 'Manual Summary Creation';
            $summary->source = $source ?? 'Balance Transaction';
        } else {
            $summary->source = $source;
        }

        if (isset($summarisable) && isset($summarisable->for_date)) {
            $summary->for_date = $summarisable->for_date;
        } else {
            $summary->for_date = $for_date ?? now(); // TODO: $for_date is not defined anywhere: Sept. 3rd, 2025
        }

            // Find the latest summary before or on for_date
            $latest_before = EcsClientAccountSummary::where('client_id', $client->id)
                ->where('for_date', '<=', $summary->for_date)
                ->orderBy('id', 'desc')
                ->first();
            if ($latest_before) {
                $base_balance = $latest_before->balance;
            } else {
                $first_after = EcsClientAccountSummary::where('client_id', $client->id)
                    ->where('for_date', '>=', $summary->for_date)
                    ->orderBy('id', 'asc')
                    ->first();
                if ($first_after) {
                    $base_balance = $first_after->balance + ($first_after->debit_amount ?? 0) - ($first_after->credit_amount ?? 0);
                } else {
                    $base_balance = $client->current_balance;
                }
            }

//        $base_balance = $latest_before ? $latest_before->balance : $client->current_balance;
            $summary->balance = $base_balance + $working_amount;
            $summary->client_id = $client->id;
            $summary->agent_user_id = auth()->id();
            $summary->save();


            // Update all later summaries for this client
            EcsClientAccountSummary::where('client_id', $client->id)
                ->where('for_date', '>', $summary->for_date)
                ->orderBy('id', 'asc')
                ->get()
                ->each(function ($item) use ($working_amount) {
                    $item->balance += $working_amount;
                    $item->save();
                });

            // Update client current balance
            $client->current_balance += $working_amount;
            $client->save();


        return $summary;
    }

    public function addDisputeMessage(EcsClientAccountSummary $summary, $msg = '')
    {
        if ($msg == '' || is_null($msg))
            return false;

        $sender = auth()->user();
        $current_messages = $summary->messages;
        if (!empty($current_messages) && checkIsJson($current_messages)) {
            $current_messages_arr = json_decode($current_messages);
        } else {
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
        if (isset($params['client_id']) && !empty($params['client_id'])) {
            $query->where('client_id', $params['client_id']);
        }
        if (isset($params['from_date']) && !empty($params['from_date'])) {
            $query->whereDate('for_date', '>=', $params['from_date']);
        }
        if (isset($params['to_date']) && !empty($params['to_date'])) {
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
        if (isset($params['client_id']) && !empty($params['client_id'])) {
            $query->where('client_id', $params['client_id']);
        }
        if (isset($params['from_date']) && !empty($params['from_date'])) {
            $query->whereDate('for_date', '>=', $params['from_date']);
        }
        if (isset($params['to_date']) && !empty($params['to_date'])) {
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

    public function updateSummaries($trx, $oldValues = null)
    {
        // $trx: EcsFlightTransaction instance (after update/delete/cancel)
        // $oldValues: array of old values before update (for update), or null (for delete/cancel)

        // Find the related summary
        $summary = EcsClientAccountSummary::where('summarisable_id', $trx->id)
            ->where('summarisable_type', get_class($trx))
            ->first();
        if(!$summary)
            return null;

        // If transaction is cancelled, remove summary and adjust subsequent summaries
        if ($trx->is_cancelled === 'yes') {
            if ($summary) {
                $client_id = $summary->client_id;
                $amount = ($summary->credit_amount ?? 0) - ($summary->debit_amount ?? 0);
                $for_date = $summary->for_date;
                $summary->delete();
                // Adjust all later summaries for this client
                $later = EcsClientAccountSummary::where('client_id', $client_id)
                    ->where('for_date', '>=', $for_date)
                    ->where('id', '>', $summary->id)
                    ->orderBy('id', 'asc')->get();
                foreach ($later as $item) {
                    logger('-');
                    logger('Balance before: '.$item->balance);
                    $item->balance -= $amount;
                    $item->save();
                    logger('Balance after: '.$item->balance);
                }
                // Update client current balance
                $client = EcsClient::find($client_id);
                if ($client) {
                    $client->current_balance -= $amount;
                    $client->save();
                }
            }
            return;
        }

        // If summary exists, check for client_id or amount changes
        if ($summary) {
            $old_client_id = $oldValues['client_id'] ?? $trx->client_id;
            $old_amount = $oldValues['amount'];
            $new_amount = $trx->totalAmount();
            $amount_diff = $new_amount - $old_amount;
            $amount_diff = $trx->source == 'REFUND' ? -1 * $amount_diff : $amount_diff;
            $old_for_date = $oldValues['for_date'] ?? $trx->for_date;

            // If client_id changed, remove from old client, add to new client
            if ($trx->client_id != $old_client_id) {
                // Remove from old client
                $old_later = EcsClientAccountSummary::where('client_id', $old_client_id)
                    ->where('for_date', '>', $old_for_date)
                    ->where('id', '!=', $summary->id)
                    ->orderBy('id', 'asc')->get();
                $balance_offset = $trx->source == 'REFUND' ? $old_amount : -1 * $old_amount;
                foreach ($old_later as $item) {
                    $item->balance -= $balance_offset;
                    $item->save();
                }
                $old_client = EcsClient::find($old_client_id);
                if ($old_client) {
                    $old_client->current_balance -= $balance_offset;
                    $old_client->save();
                }
                // Update summary to new client and amount
                $summary->client_id = $trx->client_id;
                $summary->save();

                $summary = $this->fixInDate($trx, $summary);
                logger('New summary balance: '.$summary->balance);
            } else {
                // Only amount or date changed
                if ($amount_diff != 0 || $trx->for_date != $old_for_date) {
                    $summary = $this->fixInDate($trx, $summary);
//                    $summary->balance -= $amount_diff;
//                    if($trx->source == 'REFUND'){
//                        $summary->credit_amount = $new_amount;
//                    }else {
//                        $summary->debit_amount = $new_amount;
//                    }
//                    $summary->for_date = $trx->for_date;
//                    $summary->save();
//                    // Adjust all later summaries for this client
//                    $later = EcsClientAccountSummary::where('client_id', $trx->client_id)
//                        ->where('for_date', '>=', $trx->for_date)
//                        ->where('id', '>', $summary->id)
//                        ->orderBy('id', 'asc')->get();
//                    foreach ($later as $item) {
//                        $item->balance -= $amount_diff;
//                        $item->save();
//                    }
//                    $client = EcsClient::find($trx->client_id);
//                    if ($client) {
//                        $client->current_balance += $amount_diff;
//                        $client->save();
//                    }
                }
            }
        }
    }

    private function fixInDate(EcsFlightTransaction $trx, EcsClientAccountSummary $summary)
    {
        $new_amount = $trx->totalAmount();
        $summary->for_date = $trx->for_date;
        if($trx->source == 'REFUND'){
            $summary->credit_amount = $new_amount;
        }else {
            $summary->debit_amount = $new_amount;
        }

        $summary->save();
        // Add to new client
        $latest_before = EcsClientAccountSummary::where('client_id', $trx->client_id)
            ->where('for_date', '<=', $trx->for_date)
            ->where('id', '!=', $summary->id)
            ->orderBy('id', 'DESC')
            ->orderBy('for_date', 'DESC')
            ->orderBy('updated_at', 'DESC')
            ->first();
        if($latest_before) {
            $client_prev_balance = $latest_before->balance;
            logger('$latest_before->id and details: '.$latest_before->id.' & '.$latest_before->details.' | $latest_before->balance: '.$latest_before->balance);
            $amount_offset = ($summary->credit_amount ?? 0) - ($summary->debit_amount ?? 0);
            $summary->balance = $client_prev_balance + $amount_offset;
            logger('Summary balance 411: '.$summary->balance);
        }else{
            $first_after = EcsClientAccountSummary::where('client_id', $trx->client_id)
                ->where('for_date', '>=', $trx->for_date)
                ->where('id', '!=', $summary->id)
                ->orderBy('id', 'asc')
                ->orderBy('for_date', 'ASC')
                ->orderBy('updated_at', 'ASC')
                ->first();
            if($first_after){
                $client_prev_balance = $first_after->balance;
                logger('$first_after->id and details: '.$first_after->id.' & '.$first_after->details.' | $first_after->balance: '.$first_after->balance);
                $amount_offset = ($first_after->credit_amount ?? 0) - ($first_after->debit_amount ?? 0);
                $summary->balance = $client_prev_balance - $amount_offset;
                logger('Amount offset: '.$amount_offset.' - Summary balance 422: '.$summary->balance);
            }
        }

        $new_later = EcsClientAccountSummary::where('client_id', $trx->client_id)
            ->where('for_date', '>', $trx->for_date)
            ->where('id', '!=', $summary->id)
//                        ->where('updated_at', '>', )
            ->orderBy('id', 'asc')->get();
        foreach ($new_later as $item) {
            logger('new_later item id: '.$item->id.' - Summary ID: '.$summary->id);
            $item->balance -= $new_amount;
            $item->save();
        }

        $client = EcsClient::find($trx->client_id);
        if ($client) {
            $summary_amount = ($summary->credit_amount ?? 0) - ($summary->debit_amount ?? 0);
            if(!isset($client_prev_balance)){
                logger('Set client balance to '.$client->current_balance);
                $client_prev_balance = $client->current_balance;
                $summary->balance = $client_prev_balance + $summary_amount;
                logger('Summary balance 444: '.$summary->balance);
            }
//                    logger('$summary_amount: '.$summary_amount .' | $client_prev_balance: '.$client_prev_balance. ' -- New Client: '.$client->name_and_balance);

            $client->current_balance += $summary_amount;
            $client->save();
        }

        $summary->save();

        return $summary;
    }
}
