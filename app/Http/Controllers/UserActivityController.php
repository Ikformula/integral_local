<?php

namespace App\Http\Controllers;

use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function index()
    {
        $logs = UserActivityLog::with('user')
            ->where('url', '!=', route('user.activity.duration'))
            ->where('user_id', '!=', 1)
        ->orderBy('accessed_at', 'desc')
            ->paginate(200);

        return view('frontend.access_logs.monitor', compact('logs'));
    }

    public function storeDuration(Request $request)
    {
        // Update duration for the latest activity
        UserActivityLog::where('user_id', auth()->id())
            ->where('url', $request->input('url'))
            ->latest()
            ->update(['duration' => $request->input('duration')]);

        return response()->json(['status' => 'success']);
    }
}
