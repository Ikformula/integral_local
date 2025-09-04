<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\CallCenterLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class CallCenterController extends Controller
{

    public function index()
    {
        // fetch stats
        // *. number of calls today
        $stats['Calls Today']['title'] = 'Calls Today';
        $stats['Calls Today']['value'] = CallCenterLog::whereDate('created_at', Carbon::today())->count();
        $stats['Calls Today']['icon'] = 'phone-alt';

        // *. number of lifetime calls
        $stats['Total Calls']['title'] = 'Total Calls';
        $stats['Total Calls']['value'] = CallCenterLog::count();
        $stats['Total Calls']['icon'] = 'headset';

        // *. pie charts of calls by call_purpose and type_of_call

        $last_ten_logs = CallCenterLog::orderBy('id', 'DESC')->take(10)->get();
        return view('frontend.call_center.index')->with([
            'stats' => $stats,
            'logs' => $last_ten_logs
        ]);
    }

    public function logsAjax(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax()) {
//            if($user->can('view call log')){
//                $data = CallCenterLog::select(*);
//            }else{
//                $data = CallCenterLog::where('agent_user_id', $user->id)->select(*);
//            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function(CallCenterLog $log){
                    $btn = '<a href="'.route('frontend.call_center.view.log', $log).'" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users');
    }

    public function create()
    {
        $user = auth()->user();
        if(!$user->can('create call log'))
            return back()->withErrors('Action not authorized');

        $call_logs = CallCenterLog::take(10)->orderBy('id', 'DESC');

        return view('frontend.call_center.create')->with([
           'call_logs' => $call_logs
        ]);
    }

    public function store(Request $request)
    {
        $arr['supervisors'] = $request->supervisors;
        $user = auth()->user();
        $arr['agent_user_id'] =  $user->id;
        $call_log = CallCenterLog::create(array_merge($request->all(), $arr));
        session()->put('receiving_phone_number', $request->receiving_phone_number);
        session()->put('supervisors', $request->supervisors);
        return back()->withFlashSuccess('Call logged successfully');
    }

    public function logs()
    {
        $user = auth()->user();
        if($user->can('view call log')){
            $logs = CallCenterLog::orderBy('id', 'DESC')->get();
        }else{
            $logs = CallCenterLog::where('agent_user_id', $user->id)->orderBy('id', 'DESC')->get();
        }

        return view('frontend.call_center.logs')->with([
            'logs' => $logs
        ]);
    }

    public function show(CallCenterLog $call_center_log)
    {
        return view('frontend.call_center.show')->with([
            'log' => $call_center_log
        ]);
    }
}
