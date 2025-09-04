<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Erp;
use App\Models\Logkeep;

class ErpsController extends Controller
{
    public function erps(){
        return view('frontend.erps.index')->with([
            'erps' => Erp::latest()->get()
        ]);
    }

    public function store(Request $request){
        $arr['user_id'] = auth()->id();
        $erp = Erp::create(array_merge($request->all(), $arr));

        return redirect()->route('frontend.log_keeping.show.erp', $erp)->withFlashSuccess('Erp Saved');
    }

    public function update(Erp $erp, Request $request){
        $columns_to_watch = [
            'title',
        'purpose',
        'remarks',
        ];

        $changed = [];
        $changes = '';
         foreach($columns_to_watch as $column){
            if($request->input($column) != $erp->$column){
                $changes .= $changed[] = $column.' edited to "'.$request->input($column).' "';
            }
         }


        $erp->update($request->all());
        if(sizeof($changed)){
            $changes = auth()->user()->name.' changed the following: '.$changes;

        $log = new Logkeep([
            'erp_id' => $erp->id,
            'message_from' => 'System',
            'message_to' => 'Erp details update log',
            'event_summary' => $changes,
            'entered_by_user_id' => auth()->id(),
        ]);
        }

        $log->save();
        return redirect()->back()->withFlashSuccess('Erp Updated');
    }

    public function show(Erp $erp)
    {
        $logkeeps = Logkeep::where('erp_id', $erp->id)->get();

        return view('frontend.erps.log_keeping.show-erp')->with([
            'erp' => $erp,
            'logkeeps' => $logkeeps->reverse()
        ]);
    }

    public function destroy(Erp $erp)
    {
        Logkeep::where('erp_id', $erp->id)->delete();
        $erp->delete();

        return redirect()->route('frontend.log_keeping.erps');
    }
}
