<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Logkeep;
use App\Models\Erp;
use Illuminate\Http\Request;

class LogkeepsController extends Controller
{
    public function index()
    {
        $logs = Logkeep::all();
        return view('frontend.erps.log_keeping.show-erp')->with([
            'logkeeps' => $logs
        ]);
    }

    public function logstream(Erp $erp)
    {
        $logs = Logkeep::where('erp_id', $erp->id)->latest()->get();
        return view('frontend.erps.log_keeping.logstream')->with([
            'erp' => $erp,
            'logkeeps' => $logs->reverse(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'erp_id' => 'required',
            'message_from' => 'required',
            'message_to' => 'required',
            'event_summary' => 'required'
        ]);

        $log = new Logkeep([
            'erp_id' => $request->input('erp_id'),
            'message_from' => $request->input('message_from'),
            'message_to' => $request->input('message_to'),
            'event_summary' => $request->input('event_summary'),
            'entered_by_user_id' => auth()->id(),
        ]);

        $log->save();

        return response()->json([
            'message' => 'Log created successfully',
            'created_at' => $log->created_at,
            'log_id' => $log->id,
        ], 201);

    }

    public function destroy(Request $request)
    {
        $log = Logkeep::find($request->logkeep_id);
        if($log){
            $log->delete();
            return back()->withFlashInfo('Log deleted');
        }
        return back()->withErrors('Log not found');
    }

    public function getNewLogs(Erp $erp)
    {
        // Fetch logs that are newer than the ones already displayed on the page
        $last_log_id = request()->input('last_log_id');

        $logs = Logkeep::where('erp_id', $erp->id)
            ->where('id', '>', $last_log_id)
            ->get();
        return response()->json($logs);
    }
}
