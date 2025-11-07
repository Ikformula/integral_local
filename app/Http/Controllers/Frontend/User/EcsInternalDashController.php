<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\EcsClient;
use App\Models\EcsClientAccountSummary;
use App\Models\EcsReconciliation;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Traits\EcsClientTransactionsTrait;

class EcsInternalDashController extends Controller
{
    use EcsClientTransactionsTrait;

    public function index(Request $request)
    {
        $request->validate([
            'from_date' => ['date', 'before:tomorrow'],
            'to_date' => ['date', 'after_or_equal:from_date']
        ]);
        $permission = Permission::where('name', 'manage ecs processes')->first();
        $agents = User::where('id', 1)->get();
        if ($permission)
            $agents = $permission->users;
        $clients = EcsClient::all();

        $params = $request->toArray();
        $sales = $this->totalSalesStat($params);
        $numbers = $this->numSummaries($params);

        $stats_sales = ['total_credit', 'total_debit'];
        $stats_numbers = ['total_approved', 'total_pending', 'total_disputed'];

        $reconciliations = EcsReconciliation::orderBy('for_date', 'DESC')->take(5)->get();

        return view('frontend.ecs_internal.index', compact('agents', 'clients', 'sales', 'numbers', 'stats_numbers', 'stats_sales', 'reconciliations', 'params'));
    }

    public function ecsManual()
    {
        return view('frontend.ecs_internal.ecs-manual');
    }

    public function ecsActivitiesLog()
    {
        $logs = UserActivityLog::with('user')
            ->where('url', 'LIKE', '%ecs%')
            ->where('url', '!=', route('user.activity.duration'))
            ->where('user_id', '!=', 1)
            ->orderBy('accessed_at', 'desc')
            ->paginate(200);
        return view('frontend.access_logs.monitor', compact('logs'));
    }

    public function timelyReports(Request $request)
    {
        // Default date range: last week to yesterday
        $to_date = $request->get('to_date', now()->subDay()->toDateString());
        $from_date = $request->get('from_date', now()->subWeek()->toDateString());

        // First report: CLIENT, ACCOUNT TYPE, NO OF TKTS, VALUE OF TRANSACTIONS, NO OF TKT REFUNDED, REFUND AMOUNT, ACCOUNT BALANCE
        $clients = EcsClient::with(['summaries' => function ($q) use ($from_date, $to_date) {
            $q->whereBetween('for_date', [$from_date, $to_date]);
        }, 'summaries.summarisable'])
            ->get();

        $client_report = $clients->map(function ($client) use ($from_date, $to_date) {
            $summaries = $client->summaries->whereBetween('for_date', [$from_date, $to_date]);
            $no_of_tkts = $summaries->where('debit_amount', '>', 0)->count();
            $value_of_trx = $summaries->where('debit_amount', '>', 0)->sum('debit_amount');
            $refunds = $summaries->where('credit_amount', '>', 0)->where('source', 'REFUND');
            $no_of_tkt_refunded = $refunds->count();
            $refund_amount = $refunds->sum('credit_amount');
//            $last_summary = $client->summaries->where('for_date', '<=', $to_date)->sortByDesc('id')->first();
            $last_summary = EcsClientAccountSummary::where('client_id', $client->id)->where('for_date', '<=', $to_date)->orderBy('for_date', 'DESC')->orderBy('id', 'desc')->latest()->first();
//            $balance_as_of_last_day = $last_summary ? $last_summary->balance : $client->current_balance;
            $balance_as_of_last_day = $last_summary ? $last_summary->balance : null;
            return [
                'client' => $client->name,
                'account_type' => $client->account_type ?? '',
                'no_of_tkts' => $no_of_tkts,
                'value_of_trx' => $value_of_trx,
                'no_of_tkt_refunded' => $no_of_tkt_refunded,
                'refund_amount' => $refund_amount,
                'account_balance' => $client->current_balance,
                'balance_as_of_last_day' => $balance_as_of_last_day,
            ];
        });

        // Totals for the first report
        $client_report_total = [
            'client' => 'TOTAL',
            'account_type' => '',
            'no_of_tkts' => $client_report->sum('no_of_tkts'),
            'value_of_trx' => $client_report->sum('value_of_trx'),
            'no_of_tkt_refunded' => $client_report->sum('no_of_tkt_refunded'),
            'refund_amount' => $client_report->sum('refund_amount'),
            'account_balance' => '',
            'balance_as_of_last_day' => '',
        ];

        // Second report: STAFF NAME, VALUE OF TICKETS, VALUE OF REFUND
        $staff_report = EcsClientAccountSummary::with('agent')
            ->whereBetween('for_date', [$from_date, $to_date])
            ->get()
            ->groupBy('agent_user_id')
            ->map(function ($summaries, $agent_id) {
                $agent = $summaries->first()->agent;
                $value_of_tickets = $summaries->where('summarisable_type', 'App\Models\EcsFlightTransaction')->sum('debit_amount');
                $value_of_refund = $summaries->where('summarisable_type', 'App\Models\App\Models\EcsRefund')->sum('credit_amount');
                return [
                    'staff_name' => $agent ? $agent->full_name : 'N/A',
                    'value_of_tickets' => $value_of_tickets,
                    'value_of_refund' => $value_of_refund,
                ];
            })->values();

        return view('frontend.ecs_internal.weekly-report', compact('client_report', 'staff_report', 'from_date', 'to_date', 'client_report_total'));
    }
}
