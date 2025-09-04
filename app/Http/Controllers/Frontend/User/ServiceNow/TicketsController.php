<?php

namespace App\Http\Controllers\Frontend\User\ServiceNow;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\ServiceNowGroup;
use App\Models\ServiceNowGroupViewer;
use App\Models\Staff;
use App\Models\StaffMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceNowTicket;
use App\Models\ServiceNowTicketLog;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;

class TicketsController extends Controller
{
    use OutgoingMessagesTrait;


    public function getGroupAgentsDepartments(ServiceNowGroup $group)
    {
        $departments = [];

        foreach($group->departments as $department){
            $departments[] = $department->name;
        }
        return $departments;
    }

    public function getGroupAgents()
    {
//
    }

    private $user_is_service_now_agent;
    private function getServiceNowStaffStatus(ServiceNowGroup $group)
    {
        $this->user_is_service_now_agent = null;
        $user = auth()->user();
        $staff_member = $user->staff_member;
        if(($staff_member
                && in_array($staff_member->department_name, $this->getGroupAgentsDepartments($group))
                || $user->isAdmin())
            || $group->agents()->where('staff_member_details.staff_ara_id', $staff_member->staff_ara_id)->exists()
            || !is_null($this->allowedViewing($group, null, $staff_member))
        )
            $this->user_is_service_now_agent = true;

        return $staff_member;
    }

    public function allowedViewing(ServiceNowGroup $group, User $user = null, StaffMember $staffMember = null)
    {
        if(isset($user))
            return $user->serviceNowViewables->where('service_now_group_id', $group->id)->first();

        if(isset($staffMember))
            return $staffMember->serviceNowViewables->where('service_now_group_id', $group->id)->first();

        return null;
    }

    private function allowedUser(ServiceNowGroup $group, StaffMember $staffMember){
        if($this->user_is_service_now_agent)
            return true;

        if(in_array($group->name, ['I.T. ServiceNow', 'Admin ServiceNow']))
            return true;

        if($group->name == 'Finance ServiceNow' && $staffMember->department_name == 'FINANCE')
            return true;

        if($group->name == 'PSS ServiceNow' && in_array($staffMember->department_name, ['COMMERCIAL', 'OCC']))
            return true;

        return false;
    }

    public function index(Request $request, ServiceNowGroup $group)
    {
        $staff_member = $this->getServiceNowStaffStatus($group);
        if(!$this->user_is_service_now_agent && !isset($staff_member))
            return back();

        $earliest_ticket = ServiceNowTicket::orderBy('created_at', 'ASC')->first();
        $earliest_date = $earliest_ticket->created_at;
        if($request->filled('from_date')){
            $validated = $request->validate([
                'from_date' => ['before:today']
            ]);
            $from_date_temp = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
        } else {
            $from_date_temp = Carbon::now()->subWeek()->startOfDay();
        }


        if($request->filled('to_date')){
            $validated = $request->validate([
                'to_date' => ['before:tomorrow', 'after:from_date']
            ]);
            $to_date_temp = Carbon::createFromFormat('Y-m-d', $request->to_date)->startOfDay();
        } else {
            $to_date_temp = Carbon::today();
        }


        $from_date = $from_date_temp->copy();
        $to_date = $to_date_temp->copy();

        $user = auth()->user();
//        $user_group = DB::table('service_now_group_agents')
//            ->select('service_now_group_id')
//            ->where('user_id', $user->id)
//            ->first();

//        if($user->can('supervise service now group')){
//            $tickets = ServiceNowTicket::where('group_id', $user_group->service_now_group_id)->get();
//        }else{
//            $tickets = ServiceNowTicket::where('assigned_to_agent_user_id', $user->id)->get();
//        }

        $to_date_temp->addDay();
        $tickets = ServiceNowTicket::query();
        $tickets = $tickets->where('group_id', $group->id);

        $allowed_viewer = $this->allowedViewing($group, null, $staff_member);
//        dd(!$this->user_is_service_now_agent);

        if(!$this->user_is_service_now_agent && isset($staff_member) && (!isset($allowed_viewer) || !$allowed_viewer->can_view_all_tickets)) {
            $tickets = $tickets->where('concerned_staff_ara_id', $staff_member->staff_ara_id);
        }
        $tickets = $tickets->whereBetween('created_at', [$from_date_temp, $to_date_temp])->orderBy('created_at', 'DESC')->get();

        // temporary code to add ticket_id_number to all tickets TODO: remove by march 1st, 2024
//        foreach ($tickets->whereNull('ticket_id_number') as $ticket){
//            $ticket->ticket_id_number = $ticket->concerned_staff_ara_id.'-'.Carbon::parse($ticket->created_at)->format('Ymd').'-'.$ticket->id;
//            $ticket->save();
//        }
        // Commented out: Jan 29th, 2025

        $form_values = $this->getFormValues($group);


        foreach ($form_values['priorities'] as $priority) {
            $stats[$priority] = $tickets->where('priority', $priority)->count();
        }

        foreach ($form_values['statuses'] as $status) {
            $stats[$status] = $tickets->where('status', $status)->count();
        }
        $form_values = ['priorities' => $form_values['priorities'], 'statuses' => $form_values['statuses']];
        $charts = $this->setChartsData($tickets);

        return view('frontend.service_now.tickets.index')->with([
            'tickets' => $tickets,
            'stats' => $stats,
            'form_values' => $form_values,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'params' => $request->query(),
            'earliest_date' => $earliest_date,
            'user_is_service_now_agent' => $this->user_is_service_now_agent,
            'logged_in_staff_member' => $staff_member,
            'charts' => $charts,
            'group' => $group
        ]);
    }

    public function create(ServiceNowGroup $group)
    {
        $form_values = $this->getFormValues($group);
        $staff_member = $this->getServiceNowStaffStatus($group);

        return view('frontend.service_now.tickets.create', $group)->with([
            'form_values' => $form_values,
            'user_is_service_now_agent' => $this->user_is_service_now_agent,
            'staff_member' => $staff_member,
            'group' => $group
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arr = $request->all();

//        $user_group_id = DB::table('service_now_group_agents')
//            ->select('id')
//            ->where('user_id', $request->assigned_to_agent_user_id)
//            ->first();
//
//        if ($user_group_id) {
//            $arr['group_id'] = $user_group_id->id;
//        } else {
//            $user_group_id = DB::table('service_now_groups')
//                ->select('id')
//                ->where('name', 'Undefined')
//                ->first();
//            $arr['group_id'] = $user_group_id->id;
//        }
        $arr['created_by_user_id'] = auth()->id();

        $ticket = ServiceNowTicket::create($arr);
        $ticket->ticket_id_number = $ticket->group->short_code.$request->concerned_staff_ara_id.'-'.date('Ymd').'-'.$ticket->id;
        $ticket->save();


        if ($request->filled('notify_agent') && !is_null($ticket->agent)) {
            $agent = $ticket->agent;
            unset($data);
            $data['subject'] = "Ticket/Task #" . $ticket->ticket_id_number . " Assigned to You";
            $data['greeting'] = "Dear " . $agent->full_name;
            $data['line'][] = "A ServiceNow ticket/issue has been assigned to you. Click on the button below to view it.";
            $data['line'][] = " ";
            $data['action_url'] = route('frontend.service_now.tickets.show', $ticket);
            $data['action_text'] = "View Ticket/Task";
            $data['to'] = $agent->email;
            $data['to_name'] = $agent->full_name;

            $this->storeMessage($data, null);
        }

        unset($data);
        $data['subject'] = "Ticket created - '".$ticket->title."', #" . $ticket->ticket_id_number;
        $data['greeting'] = "Dear " . $ticket->concernedStaff->name;
        if($ticket->assigned_to_agent_user_id) {
            $data['line'][] = "Your ServiceNow ticket/issue has been created and a support personnel has been assigned to attend to it. See details below.";
            $data['line'][] = "Agent: ".$ticket->agent->full_name;
        }else{
            $data['line'][] = "Your ServiceNow ticket/issue has been created and a support personnel will be assigned to attend to it. See details below.";
        }
        $data['line'][] = "Ticket reference number: ".$ticket->ticket_id_number;
        $data['line'][] = "Kindly use the reference number above to track this issue.";
        $data['line'][] = "Click on the button below to view more information about this ticket.";
        $data['action_url'] = route('frontend.service_now.tickets.show', $ticket);
        $data['action_text'] = "View Ticket/Task";
        $data['line'][] = "";
        $data['line'][] = "Thanks and regards.";
        $data['to'] = $ticket->concernedStaff->email;
        $data['to_name'] = $ticket->concernedStaff->full_name;

        $this->storeMessage($data, null);
        return redirect()->route('frontend.service_now.tickets.index', $ticket->group_id)->withFlashInfo('Ticket created');
    }

    private function getFormValues(ServiceNowGroup $group)
    {
        $form_values['groups'] = DB::table('service_now_groups')
            ->get();

        // $agent_ids = DB::table('service_now_group_agents')
        //     ->select('user_id')
        //     ->get();
        // $agent_ids = $agent_ids->pluck('user_id')->toArray();

        $form_values['staff_members'] = DB::table('staff_member_details')
            ->select(['staff_ara_id', 'surname', 'other_names', 'department_name', 'email'])
            ->whereNull('deleted_at')
            ->get();

        // Get agent staff
        $form_values['it_staff_members'] = $form_values['staff_members']->whereIn('department_name', $this->getGroupAgentsDepartments($group))->pluck('email');
//        dd($form_values['it_staff_members']);
        // TODO: add agents set in DB to this array
        $agent_staffs = $form_values['staff_members']->whereIn('staff_ara_id', $group->agents->pluck('staff_ara_id'))->pluck('email');

        $all_agents = $form_values['it_staff_members']->toBase()->merge($agent_staffs);

        $form_values['agents'] = User::whereIn('email', $all_agents)->get();

        $form_values['origin_types'] = [
            'walk in' => 'Walk in',
            'visit' => 'Visit',
            'phone call' => 'Phone call',
            'email' => 'Email',
            'project' => 'Project',
            'emergency' => 'Emergency',
            'update' => 'Update',
            'routine maintenance' => 'Routine maintenance'
        ];

        $form_values['priorities'] = [
            'low',
            'medium',
            'high',
        ];

        $form_values['statuses'] = [
            'pending',
            'open',
            'resolved',
//            'completed',
            'closed',
        ];

        $form_values['ticket_types'] = DB::table('service_now_ticket_types')
            ->where('group_id', $group->id)
            ->get();

        return $form_values;
    }

    public function show(ServiceNowTicket $ticket)
    {

        $staff_member = $this->getServiceNowStaffStatus($ticket->group);
        $form_values = $this->getFormValues($ticket->group);

        if($this->user_is_service_now_agent || (isset($staff_member) && $staff_member->staff_ara_id == $ticket->concerned_staff_ara_id)) {
            return view('frontend.service_now.tickets.show')->with([
                'ticket' => $ticket,
                'form_values' => $form_values,
                'user_is_service_now_agent' => $this->user_is_service_now_agent,
                'staff_member' => $staff_member
            ]);
        }

        return back()->withErrors('Unauthorized access');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function update(Request $request, ServiceNowTicket $ticket)
    {
        $originalValues = $ticket->getOriginal();
        if ($request->filled('assigned_to_agent_user_id') && $request->assigned_to_agent_user_id != $ticket->assigned_to_agent_user_id) {
            $assignee = User::find($request->assigned_to_agent_user_id);

            if ($assignee) {
                $log_info = [
                    'title' => 'Task Escalated to Staff',
                    'description' => 'Task/Issue #' . $ticket->ticket_id_number . ' has been assigned to ' . $assignee->full_name . ' on ' . now()->toDayDateTimeString(),
                    'type' => 'escalated'
                ];
                $this->addLog($log_info, $ticket);
            }
        }

        if ($request->status != $ticket->status) {
            $log_info = [
                'title' => 'Ticket status changed',
                'description' => 'Task/Issue #' . $ticket->ticket_id_number . '\'s status has been updated to <strong>' . $request->status . '</strong> on ' . now()->toDayDateTimeString(),
            ];
            $this->addLog($log_info, $ticket);
        }

        if ($request->rating != $ticket->rating) {
            $log_info = [
                'title' => 'Staff set a rating',
                'description' => 'Rating: ' . $request->rating,
            ];
            $this->addLog($log_info, $ticket);
        }

        $ticket->update($request->all());
        $updatedValues = $ticket->getAttributes();

        // Compare original and updated values to detect changes
        $changedColumns = Arr::where($updatedValues, function ($value, $key) use ($originalValues) {
            return $value !== $originalValues[$key];
        });

        // Log the changes for each modified column
        $logInfo = [];
        $logInfo['title'] = "Ticket details updated by ".auth()->user()->full_name;
        $logInfo['description'] = '';
        foreach ($changedColumns as $key => $value) {
            if(!in_array($key, ['rating', 'status', 'assigned_to_agent_user_id', 'created_at', 'updated_at', 'type_id']))
            $logInfo['description'] .= "<strong>{$key}</strong> value changed from '{$originalValues[$key]}' to '{$value}' <br>";
        }
        $this->addLog($logInfo, $ticket);
        return back()->withFlashSuccess('Ticket updated');
    }

    public function processAddLog(Request $request, ServiceNowTicket $ticket)
    {
        $log_info = [
            'title' => $request->title ?? '',
            'description' => $request->description ?? '',
        ];
        if ($this->addLog($log_info, $ticket)) {
            return back()->withFlashInfo('Log saved');
        }
        return back()->withFlashWarning('Log not saved');
    }

    public function addLog($log_info, ServiceNowTicket $ticket)
    {
        $log = new ServiceNowTicketLog();
        $log->service_now_ticket_id = $ticket->id;
        $log->title = $log_info['title'];
        $log->description = $log_info['description'] ?? $log_info['title'].' - no descr';
        $log->type = isset($log_info['type']) ? $log_info['type'] : 'note';
        $log->triggerer_user_id = auth()->id() ?? null;
        $log->save();

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        if(!$user->can('handle service now tickets'))
            redirect()->route('frontend.service_now.tickets.index')->withErrors('Unauthorized');
        ServiceNowTicketLog::where('service_now_ticket_id', $id)->delete();
        ServiceNowTicket::where('id', $id)->delete();
        return redirect()->route('frontend.service_now.tickets.index', 1)->withFlashSuccess('Ticket Deleted');
    }

    public function taskWatcher()
    {
        $consecutive_days = 3;
        $tasks = ServiceNowTicket::where('status', 'pending')
            ->whereRaw('DATEDIFF(NOW(), created_at) % ' . $consecutive_days . ' = 0')
            ->get();

        foreach ($tasks as $task) {
            unset($sent_to);
            unset($agingData);
            unset($percent_aged);

            $sent_to = [
                'assigned_to_agent_user_id' => 0,
                'escalate_to_user_id' => 0,
                'created_by_user_id' => 0,
            ];

            if ($task->notify_agent == 1 && isset($task->assigned_to_agent_user_id)) {
                $this->notifyOnAging($task, $task->assigned_to_agent_user_id);
            }
            $sent_to['assigned_to_agent_user_id'] = $task->assigned_to_agent_user_id;

            $agingData = $task->getPercentAged();

            extract($agingData);
            if ($task->notify_escalation_user == 1 && isset($task->notify_escalation_user) && $percent_aged >= 100 && !in_array($task->escalate_to_user_id, $sent_to)) {
                $this->notifyOnAging($task, $task->escalate_to_user_id);
            }

            $sent_to['escalate_to_user_id'] = $task->escalate_to_user_id;

            if (!in_array($task->created_by_user_id, $sent_to)) {
                $this->notifyOnAging($task, $task->created_by_user_id);
            }

        }
    }

    public function notifyAgentOnAssignment(ServiceNowTicket $ticket, User $user)
    {
        unset($data);
        $data['subject'] = "Ticket/Task #" . $ticket->ticket_id_number . " Assigned to You";
        $data['greeting'] = "Dear " . $user->full_name;
        $data['line'][] = "A ServiceNow ticket/issue has been assigned to you. Click on the button below to view it.";
        $data['line'][] = " ";
        $data['action_url'] = route('frontend.service_now.tickets.show', $ticket);
        $data['action_text'] = "View Ticket/Task";
        $data['to'] = $user->email;
        $data['to_name'] = $user->full_name;

        $this->storeMessage($data, null);
        $log_info = [
            'title' => 'Agent notified',
            'description' => 'Email to notify ' . $user->full_name . ' (' . $user->email . ') on this assignment has been sent on ' . now()->toDayDateTimeString() . '.'
        ];
        $this->addLog($log_info, $ticket);
        echo 'notifyAgentOnAssignment email sent to ' . $user->email . ' on ' . now()->toDayDateTimeString() . PHP_EOL;
        return true;
    }

    public function notifyOnAging(ServiceNowTicket $ticket, $user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            unset($data);
            $data['subject'] = "Ticket/Task #" . $ticket->ticket_id_number . " not yet resolved/completed";
            $data['greeting'] = "Dear " . $user->full_name;
            $data['line'][] = "The ServiceNow ticket/issue has not been resolved or completed; Created " . $ticket->created_at->diffForHumans() . ". Click on the button below to view it.";
            $data['line'][] = " ";
            $data['action_url'] = route('frontend.service_now.tickets.show', $ticket);
            $data['action_text'] = "View Ticket/Task";
            $data['to'] = $user->email;
            $data['to_name'] = $user->full_name;

            $this->storeMessage($data, null);
            $log_info = [
                'title' => 'Aging notification',
                'description' => 'Email to notify ' . $user->full_name . ' (' . $user->email . ') on the aging of this ticket/issue has been sent on ' . now()->toDayDateTimeString() . '.'
            ];

            $this->addLog($log_info, $ticket);
            echo 'notifyOnAging email sent to ' . $user->email . ' on ' . now()->toDayDateTimeString() . PHP_EOL;

            return true;
        }

        return false;
    }

    public function setChartsData($tickets)
    {
        $assignees = [];
        $origin_types = [];
        $statuses = [];
        foreach($tickets as $ticket){
            if(!is_null($ticket->assigned_to_agent_user_id) && array_key_exists($ticket->assigned_to_agent_user_id, $assignees)){
                $assignees[$ticket->assigned_to_agent_user_id]['num_tickets']++;
            }else if(!is_null($ticket->assigned_to_agent_user_id)){
                $assignees[$ticket->assigned_to_agent_user_id]['num_tickets'] = 1;
                $assignees[$ticket->assigned_to_agent_user_id]['name'] = $ticket->agent->first_name;
            }

            if(array_key_exists($ticket->origin_type, $origin_types)){
                $origin_types[$ticket->origin_type]++;
            }else{
                $origin_types[$ticket->origin_type] = 1;
            }

            if(array_key_exists($ticket->status, $statuses)){
                $statuses[$ticket->status]++;
            }else{
                $statuses[$ticket->status] = 1;
            }
        }

        $o_types = [];
        foreach($origin_types as $origin_type => $number){
            $o_types[$origin_type.' - '.$number] = $number;
        }

        return [
            'assignees' => $assignees,
            'origin_types' => $o_types,
            'statuses' => $statuses,
        ];
    }

    public function statsApi(Request $request, ServiceNowGroup $group)
    {
        $validated = $request->validate([
           'from_date',
           'to_date'
        ]);

        $tickets = ServiceNowTicket::where('group_id', $group->id)->whereBetween('created_at', [$validated['from_date'], $validated['to_date']])->get();
        $stats = [];
        $stats['total'] = $tickets->count();
        $stats['phone'] = $tickets->where('origin_type', 'phone call')->count();
        $stats['walk in'] = $tickets->where('origin_type', 'walk in')->count();
        $stats['email'] = $tickets->where('origin_type', 'email')->count();
        $stats['closed'] = $tickets->where('status', 'closed')->count();
        $stats['open'] = $tickets->where('status', 'pending')->count();

        return $stats;
    }
}
