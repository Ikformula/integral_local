<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsBooking;
use App\Models\EcsClient;
use App\Models\EcsClientAccountSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EcsClientTransactionsTrait;

class EcsExternalClientController extends Controller
{
    use EcsClientTransactionsTrait;
    private function getUserClient()
    {
        $user = auth()->user();
        $client_user = $user->isEcsClient;
        if(!$client_user)
            return redirect()->route('frontend.auth.login')->withErrors('No client account found. Contact Arik Air Corporate Sales team');
        $client = $client_user->client;

        return [
            'user' => $user,
            'client_user' => $client_user,
            'client' => $client
            ];
    }

    public function accountSummaries(Request $request)
    {
        extract($this->getUserClient());
        return $this->summariesList($request, ['client' => $client]);
    }

    public function approveTrx(EcsClientAccountSummary $ecs_summary)
    {
        extract($this->getUserClient());
        if($client->id != $ecs_summary->client_id)
            return redirect()->back()->withErrors('Unauthorized action');
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
        extract($this->getUserClient());
        if($client->id != $ecs_summary->client_id)
            return redirect()->back()->withErrors('Unauthorized action');

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

    public function clientProfile()
    {
        extract($this->getUserClient());

        return view('frontend.ecs_clients.show')->with([
            'ecs_client' => $client_user->client,
            'isClient' => true
        ]);
    }

    public function bookings()
    {
        extract($this->getUserClient());
        return view('frontend.ecs_bookings.index')->with([
           'items' => EcsBooking::where('client_id', $client->id)->get(),
            'isClient' => true
        ]);
    }

}
