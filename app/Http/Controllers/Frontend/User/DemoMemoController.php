<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\StaffMember;
use App\Models\Workflow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;

class DemoMemoController extends Controller
{
    use OutgoingMessagesTrait;
    public function index()
    {
        $workflows = Workflow::latest()->get();
        return view('frontend.demo_memo.index', compact('workflows'));
    }

    public function create()
    {
        $approvers = StaffMember::where('employment_category', 'full staff')->get();
        return view('frontend.demo_memo.create', compact('approvers'));
    }

    public function store(Request $request)
    {
        $workflow = new Workflow();
        $workflow->type = $request->type;
        $workflow->title = $request->title;
        $workflow->approver_staff_ara_id = $request->approver;
        $auth_user = auth()->user();
        $workflow->originator_staff_ara_id = $auth_user->staff_member->staff_ara_id;
        if($request->hasFile('file')){
            $file = $request->file('file');
            // Generate a unique filename
            $workflow->file_path = time().'-'.uniqid() .'-ARA'. $request->approver .'.' . $file->getClientOriginalExtension();
            // Move the file to the public folder
            $file->move('workflow_files', $workflow->file_path);
        }
        $workflow->save();

        unset($data);
        $data['subject'] = config('app.name') . " - New Workflow for Approval";
        $data['greeting'] = "Dear " . $workflow->approver()->other_names;
        $data['line'][] = "A new ".$workflow->type." has been uploaded for your perusal:";
        $data['line'][] = "- Title: " . $workflow->title;
        $data['line'][] = "- Originator: " . $workflow->originator()->other_names;
        $data['action_url'] = route('frontend.work_flows.workflow.show', $workflow);
        $data['action_text'] = "View";
        $data['to'] = $workflow->approver()->email;
        $data['to_name'] = $workflow->approver()->name;
        $data['from'] = $workflow->originator()->email;
        $data['from_name'] = $workflow->originator()->name;
        $data['cc'] = [$workflow->originator()->email];

        $this->storeMessage($data, $workflow->approver()->user->id);

        return redirect()->route('frontend.work_flows.workflow.index')->withFlashSuccess('Workflow created and email notification sent');
    }

    public function responseStore(Request $request, Workflow $workflow)
    {
        $now = Carbon::now();
        if($request->response_type == 'approved'){
            $workflow->approved_at = $now;
        }else{
            $workflow->rejected_at = $now;
        }
        $workflow->save();


        // Send a message to the originator and cc approver
        unset($data);
        $data['subject'] = $workflow->type . " - Workflow ".$request->response_type;
        $data['greeting'] = "Dear " . $workflow->originator()->other_names;
        $data['line'][] = "The ".$workflow->type." has been ".$request->response_type." by ".$workflow->approver()->name;
        $data['line'][] = "- Title: " . $workflow->title;
        $data['line'][] = "- Originator: " . $workflow->originator()->other_names;
        $data['action_url'] = route('frontend.work_flows.workflow.show', $workflow);
        $data['action_text'] = "View";
        $data['to'] = $workflow->originator()->email;
        $data['to_name'] = $workflow->originator()->name;
        $data['cc'] = [$workflow->approver()->email];

        $this->storeMessage($data, $workflow->originator()->user->id);

        return back()->withFlashInfo('Response submitted');
    }

    public function show(Workflow $workflow)
    {
        return view('frontend.demo_memo.show', compact('workflow'));
    }
}
