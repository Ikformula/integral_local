<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\OutgoingMessageRecipient;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
//        return view('frontend.user.dashboard');
        $user = auth()->user();
        if($user->isEcsClient)
            return redirect()->route('frontend.ecs_client_portal.accountSummaries');

        return view('frontend.index');
    }

    public function outgoingMessages()
    {
        $message_recipients = OutgoingMessageRecipient::latest()->paginate(200);
        return view('frontend.user.outgoing-messages', compact('message_recipients'));
    }
}
