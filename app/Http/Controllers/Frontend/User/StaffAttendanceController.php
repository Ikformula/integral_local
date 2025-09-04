<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use App\Models\StaffMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;

class StaffAttendanceController extends Controller
{
    use OutgoingMessagesTrait;
    public function index()
    {
        $todays_attendances = StaffAttendance::select(['direction', 'created_at'])
            ->whereDate('created_at', Carbon::today())
            ->distinct('direction')
            ->get();

        $stats['ins'] = $todays_attendances->where('direction', 'in')->count();
        $stats['outs'] = $todays_attendances->where('direction', 'out')->count();
        $stats['on_prem'] = $stats['ins'] - $stats['outs'];

        return view('frontend.staff_attendance.index')->with([
            'stats' => $stats,
            'outstation' => null
        ]);
    }


    public function outstation()
    {
        return view('frontend.staff_attendance.index')->with([
            'outstation' => 1
        ]);
    }



    public function checkStaffInfo(Request $request)
    {
        $staff = StaffMember::where('staff_ara_id', $request->staff_ara_id)->first();
        if(!$staff){
            return [
                'msg' => 'No Staff with matching ARA number found',
                'attendance_entered' => false
            ];
        }

        $todays_attendances = StaffAttendance::where('staff_ara_id', $staff->staff_ara_id)->whereDate('created_at', Carbon::today())->get();

        return [
            'todays_attendances' => $todays_attendances,
            'staff_member' => $staff
        ];
    }

    public function checkARANumber(Request $request)
    {
        $staff = StaffMember::where('staff_ara_id', $request->staff_ara_id)->first();
        if(!$staff){
            return [
                'msg' => 'No Staff with matching ARA number found',
                'attendance_entered' => false
            ];
        }


        $now = Carbon::now();
        //check status
        $attendance_entered = false;
        if($staff->status == 'active' && is_null($staff->restrict_access_from)) {
            $attendance = new StaffAttendance();
            $attendance->operator_user_id = auth()->id();
            $attendance->staff_ara_id = $staff->staff_ara_id;
            $attendance->ip_address = $request->getClientIp();
            $attendance->meridien = date('A');
            $attendance->seconds = date('s');
            $attendance->minutes = date('i');
            $attendance->hour = date('H');
            $attendance->day = date('d');
            $attendance->week_day = date('l');
            $attendance->month = date('F');
            $attendance->year = date('Y');
            $attendance->temperature = $request->temperature;

            $minutes_wait = 2;

            $previous_attendance = StaffAttendance::where('staff_ara_id', $staff->staff_ara_id)->latest()->first();
            if (!$previous_attendance) {
                $attendance->direction = $request->filled('direction') ? $request->direction : 'in';
            }else if($previous_attendance->created_at >= now()->subMinutes($minutes_wait)){
                return [
                    'attendance_entered' => $attendance_entered,
                    'msg' => 'Allow at least '.$minutes_wait.' minutes wait time before marking this staff again',
                ];
            } else if($request->filled('direction')){
                $attendance->direction = $request->direction;
            } else if ($previous_attendance->direction == 'in') {
                $attendance->direction = 'out';
            } else {
                $attendance->direction = 'in';
            }

            $attendance->save();
            $attendance_entered = true;

            $todays_attendances = StaffAttendance::select(['direction', 'created_at'])->whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])->get();
            $stats['ins'] = $todays_attendances->where('direction', 'in')->count();
            $stats['outs'] = $todays_attendances->where('direction', 'out')->count();
            $stats['on_prem'] = $stats['ins'] - $stats['outs'];

            if(isset($staff->email)) {
                unset($data);
                $data['subject'] = "Attendance Marked";
                $data['greeting'] = "Dear " . $staff->name;
                $data['line'][] = "Your attendance was just marked; details below:";
                $hour = $attendance->hour > 12 ? $attendance->hour - 12 : $attendance->hour;
                $data['line'][] = "* Time: " . $hour.':'.$attendance->minutes.' '. $attendance->meridien;
                $data['line'][] = "* Date: " .$attendance->week_day.', '.$attendance->month.' '.$attendance->day.' '.$attendance->year;
                $data['line'][] = "* Direction: " . $attendance->direction;
                if($attendance->temperature) {
                    $data['line'][] = "* Temperature: " . $attendance->temperature;
                }
                $data['line'][] = " ";
                $data['action_url'] = route('frontend.attendance.my.attendance').'?staff_ara_id='.$staff->staff_ara_id;
                $data['action_text'] = "View Attendance Chart";
                $data['to'] = $staff->email;
                $data['to_name'] = $staff->name;

                $this->storeMessage($data, null);
            }

            return [
                'attendance_entered' => $attendance_entered,
                'nows_attendance' => $attendance,
                'staff_member' => $staff,
                'stats' => $stats
            ];
        }

        $todays_attendances = StaffAttendance::select(['direction', 'created_at'])->whereDate('created_at', Carbon::today())->get();
        $stats['ins'] = $todays_attendances->where('direction', 'in')->count();
        $stats['outs'] = $todays_attendances->where('direction', 'out')->count();
        $stats['on_prem'] = $stats['ins'] - $stats['outs'];


        return [
            'attendance_entered' => $attendance_entered,
            'staff_member' => $staff,
            'stats' => $stats
        ];

    }


    public function viewMultipleStaffAttendance(Request $request)
    {
        $auth_user = auth()->user();
        // define date range
        if($request->filled('from_date')){
            $from_date = Carbon::parse($request->from_date);
        }else{
            $from_date = Carbon::now()->startOfMonth();
        }

        if($request->filled('to_date')){
            $to_date = Carbon::parse($request->to_date);
        }else{
            $to_date = Carbon::now();
        }

        $dates = [
            'from_date' => $from_date,
            'to_date' => $to_date,
        ];

        $staff_ara_id = $request->staff_ara_id;
        $department = $request->department;
        $staff_name = $request->staff_name;
        $filter_late = $request->filter_late;


        if($auth_user->can('manage all staff attendance')){
            $staff_members = StaffMember::select(['surname', 'other_names', 'staff_ara_id', 'department_name'])
                ->when($staff_ara_id, function ($query, $staff_ara_id) {
                    return $query->where('staff_ara_id', $staff_ara_id);
                })
                ->when($department, function ($query, $department) {
                    return $query->where('department_name', $department);
                })
                ->when($staff_name, function ($query, $staff_name) {
                    $search_words = explode(' ', $staff_name);
                    foreach ($search_words as $word) {
                        $query->where(function ($query) use ($word) {
                            $query->where('surname', 'LIKE', '%' . $word . '%')
                                ->orWhere('other_names', 'LIKE', '%' . $word . '%');
                        });
                    }
                    return $query;
                })
                ->when($filter_late, function ($query) use ($dates) {
                    $startDateTime = $dates['from_date']->format('Y-m-d') . ' 00:00:00';
                    $endDateTime = $dates['to_date']->format('Y-m-d') . ' 23:59:59';
                    $query->join('staff_attendances', 'staff_member_details.staff_ara_id', '=', 'staff_attendances.staff_ara_id')
                        ->where('staff_attendances.direction', 'in')
                        ->whereBetween('staff_attendances.created_at', [$startDateTime, $endDateTime])
                        ->whereRaw('(SELECT COUNT(*) FROM staff_attendances AS sa WHERE sa.staff_ara_id = staff_member_details.staff_ara_id AND sa.direction = "in" AND sa.hour >= 9) = 0');
                })
                ->orderBy('staff_ara_id', 'ASC')
//                ->get();
                ->paginate(60000);


        }else if($auth_user->can('manage own unit info')){
            $staff_members = StaffMember::select(['surname', 'other_names', 'staff_ara_id', 'department_name'])
                ->where('department_name', $auth_user->department_name)
                ->orderBy('staff_ara_id', 'ASC')
                ->paginate(20);
//                ->get();
        }else{
            $staff_members = StaffMember::select(['surname', 'other_names', 'staff_ara_id', 'department_name'])->where('email', $auth_user->email)
                ->orderBy('staff_ara_id', 'ASC')
                ->paginate(20);
//                ->get();
        }


        $st_m = $staff_members;
        $stj = json_encode($staff_members);
        $st = json_decode($stj, true);
        return $st['data'];
        // testing purposes
//        $staff_members = StaffMember::select(['surname', 'other_names', 'staff_ara_id', 'department_name'])->take(20)->get();

        return view('frontend.staff_attendance.multiple_staff_attendance_records')->with([
            'staff_members' => $st_m,
            'staff_members_json' => json_encode($st['data']),
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
    }

    public function viewIndividualStaffAttendance(Request $request)
    {
        $validated = $request->validate([
            'staff_ara_id' => [
                'required',
                'numeric',
                'exists:staff_member_details,staff_ara_id'
            ],
            'from_date' => [
                'date',
                'before:to_date'
            ],
            'to_date' => [
                'date',
                'after:from_date'
            ],
        ]);

        $auth_user = auth()->user();

        if($auth_user->can('manage all staff attendance') || $auth_user->can('manage own unit info')){
            // TODO: separate the above condition for unit and for the whole firm
            $staff_ara_id = $request->staff_ara_id;
        }else{
            $staff_ara_id = $auth_user->staff_member->staff_ara_id;
        }

        $staff = StaffMember::where('staff_ara_id', $staff_ara_id)->first();

        if($request->filled('from_date')){
            $from_date = Carbon::createFromFormat('Y-m-d',$request->from_date)->format('Y-m-d');
            $from_date_temp = Carbon::createFromFormat('Y-m-d', $request->from_date)->format('Y-m-d');
        }else{
            $from_date = Carbon::now()->startOfMonth();
            $from_date_temp = Carbon::now()->startOfMonth();
        }

        if($request->filled('to_date')){
            $to_date = $request->to_date;
        }else{
            $to_date = Carbon::now();
        }

        for($date = $from_date_temp; $date <= $to_date; $date->addDay()){
            $attendances[$staff_ara_id][$date->toDateString()]['movements'] = StaffAttendance::where('staff_ara_id', $staff_ara_id)->whereBetween('created_at', [$date->startOfDay()->toDateTimeString(), $date->endOfDay()->toDateTimeString()])->get();
            $attendances[$staff_ara_id][$date->toDateString()]['hours on prem'] = 0;
//            dd($attendances[$staff_ara_id][$date->toDateString()]['movements']);
            if(count($attendances[$staff_ara_id][$date->toDateString()]['movements']) >= 2) {

                $first_in = $attendances[$staff_ara_id][$date->toDateString()]['movements']
                    ->where('direction', 'in')
                    ->sortBy('id')
                    ->first();
                $last_out = $attendances[$staff_ara_id][$date->toDateString()]['movements']
                    ->where('direction', 'out')
                    ->sortByDesc('id')
                    ->first();

//                $attendances[$staff_ara_id][$date->toDateString()]['hours on prem'] = $last_out->created_at->diffInHours($first_in->created_at);
                $attendances[$staff_ara_id][$date->toDateString()]['hours on prem'] = $this->calcHoursInPrem($staff_ara_id, $date->toDateString());
                $days_attendance[] = [
                    'date' => $date->toDateString(),
                    'resumed' => $first_in->hour.':'.$first_in->minutes,
                    'closed' => $last_out->hour.':'.$last_out->minutes,
                    'hours' => $attendances[$staff_ara_id][$date->toDateString()]['hours on prem'],
                    'weekday' => $first_in->week_day
                ];
            }else{
                $days_attendance[] = [
                    'date' => $date->toDateString(),
                    'resumed' => '-',
                    'closed' => '-',
                    'hours' => 0,
                    'weekday' => $date->dayName
                ];
            }
        }


        return view('frontend.staff_attendance.individual_staff_attendance_records')
            ->with([
                'staff' => $staff,
                'attendances' => $days_attendance,
                'from_date' => $from_date,
                'to_date' => $to_date
            ]);
    }

    public function getIndividualStaffAttendance(Request $request)
    {
        $validated = $request->validate([
            'staff_ara_id' => [
                'required',
                'numeric',
                'exists:staff_member_details,staff_ara_id'
            ],
            'from_date' => [
                'date',
                'before:to_date'
            ],
            'to_date' => [
                'date',
                'after:from_date'
            ],
        ]);

        $staff_ara_id = $request->staff_ara_id;
        $staff = StaffMember::where('staff_ara_id', $staff_ara_id)->first();
        $last_email_time = DB::table('attendance_email_logs')
            ->where('staff_ara_id', $staff_ara_id)
            ->orderBy('created_at', 'DESC')
            ->first();
        if($last_email_time){
            $tym = Carbon::parse($last_email_time->created_at);
            $last_email_time = $tym->toDayDateTimeString();
        }else {
            $last_email_time = 'never';
        }

        // define date range
        if($request->filled('from_date')){
            $from_date = Carbon::parse($request->from_date);
            $from_date_temp = Carbon::parse($request->from_date);
        }else{
            $from_date = Carbon::now()->startOfMonth();
            $from_date_temp = Carbon::now()->startOfMonth();
        }

        if($request->filled('to_date')){
            $to_date = Carbon::parse($request->to_date);
        }else{
            $to_date = Carbon::now();
        }

        for($date = $from_date_temp; $date <= $to_date; $date->addDay()){
            $attendances[$staff_ara_id][$date->toDateString()]['movements'] = StaffAttendance::where('staff_ara_id', $staff_ara_id)->whereBetween('created_at', [$date->startOfDay()->toDateTimeString(), $date->endOfDay()->toDateTimeString()])->get();
            $attendances[$staff_ara_id][$date->toDateString()]['hours on prem'] = 0;

            $count_attendance = isset($attendances[$staff_ara_id][$date->toDateString()]['movements']) ? count($attendances[$staff_ara_id][$date->toDateString()]['movements']) : 0;

            $attendances[$staff_ara_id][$date->toDateString()]['schedule'] = $this->checkDaySchedule($staff_ara_id, $date->toDateString(), $count_attendance);
            if($count_attendance >= 1) {
                $first_in = $attendances[$staff_ara_id][$date->toDateString()]['movements']
                    ->where('direction', 'in')
                    ->sortBy('id')
                    ->first();
                $last_out = $attendances[$staff_ara_id][$date->toDateString()]['movements']
                    ->where('direction', 'out')
                    ->sortByDesc('id')
                    ->first();

//                $attendances[$staff_ara_id][$date->toDateString()]['hours on prem'] = $last_out->created_at->diffInHours($first_in->created_at);
                $attendances[$staff_ara_id][$date->toDateString()]['hours on prem'] = $this->calcHoursInPrem($staff_ara_id, $date->toDateString());
                if(!$last_out){
                    $days_attendance[] = [
                        'date' => $date->toDateString(),
                        'resumed' => $first_in->hour . ':' . $first_in->minutes,
                        'closed' => '-',
                        'hours' => 0,
                        'weekday' => $date->dayName,
                        'day_schedule' => $this->checkDaySchedule($staff_ara_id, $date, count($attendances[$staff_ara_id][$date->toDateString()]['movements'])),
                    ];
                } else {
                    $days_attendance[] = [
                        'date' => $date->toDateString(),
                        'resumed' => $first_in->hour . ':' . $first_in->minutes,
                        'closed' => $last_out->hour . ':' . $last_out->minutes,
                        'hours' => $attendances[$staff_ara_id][$date->toDateString()]['hours on prem'],
                        'weekday' => $first_in->week_day,
                        'day_schedule' => $this->checkDaySchedule($staff_ara_id, $date, count($attendances[$staff_ara_id][$date->toDateString()]['movements']), $first_in->hour),
                    ];
                }
            }else{
                $days_attendance[] = [
                    'date' => $date->toDateString(),
                    'resumed' => '-',
                    'closed' => '-',
                    'hours' => 0,
                    'weekday' => $date->dayName,
                    'day_schedule' => $this->checkDaySchedule($staff_ara_id, $date, count($attendances[$staff_ara_id][$date->toDateString()]['movements']))
                ];
            }
        }

        return response()->json([
            'staff' => $staff,
            'attendances' => $days_attendance,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'last_email_time' => $last_email_time
        ], 200);
    }

    public function hoursOnPrem(Request $request)
    {
        $validated = $request->validate([
            'staff_ara_id' => ['required'],
            'date' => ['required', 'date'],
        ]);

        $arr['hours'] = $this->calcHoursInPrem($validated['staff_ara_id'], $validated['date']);
        return array_merge($validated, $arr);
    }

    public function calcHoursInPrem($staffAraId, $date)
    {
        $date = Carbon::parse($date);
// Retrieve the staff's attendance records for the given date
        $attendances = DB::table('staff_attendances')
            ->where('staff_ara_id', $staffAraId)
            ->whereDate('created_at', $date)
            ->orderBy('created_at')
            ->get();

        $inTime = null;
        $outTime = null;
        $totalHours = 0;

// Loop through the attendance records
        foreach ($attendances as $attendance) {
            if ($attendance->direction === 'in') {
                $inTime = Carbon::parse($attendance->created_at);
            } elseif ($attendance->direction === 'out') {
                $outTime = Carbon::parse($attendance->created_at);

                // Calculate the duration between the in and out times
                $duration = $inTime->diffInMinutes($outTime) / 60.0;
                $totalHours += $duration;

                $inTime = null;
                $outTime = null;
            }
        }

        return number_format($totalHours);
    }

    public function checkDayScheduletest(Request $request)
    {
        return $this->checkDaySchedule($request->ara, $request->date, $request->ct);
    }

    public function checkDaySchedule($staffAraId, $date, $attendance_count = null, $first_in_hour = null)
    {
        $date = Carbon::parse($date);
        $schedule = DB::table('staff_remote_schedules')
            ->where('staff_ara_id', $staffAraId)
            ->where('week_day', strtolower($date->format('l')))
            ->first();
        $show = 'show';
        if(is_null($attendance_count) || $attendance_count == 0)
            $show = 'no show';

        $workdays = [
            'monday',
            'tuesday',
            'wednesday',
            'friday',
            'thursday',
        ];

        if(!in_array(strtolower($date->format('l')), $workdays)){
            $location = 'Remote';
            $show = 'weekend';
        }else if(!$schedule || !is_object($schedule) || $schedule->location == 'On duty'){
            $location = 'On duty';
        }else{
            $location = $schedule->location;
        }


        $colors = [
            'On duty - no show' => '#FFADAD',
            'On duty - show' => '#FFFFFF',
//          'Remote - no show' => '#4cb944',
            'Remote - no show' => '#F5F5F5',
            'Remote - show' => '#FDFFB6',
            'Remote - weekend' => '#CAFFBF',
//            'On duty - late' => '#dc2f02',
            'On duty - late' => '#EA4234',
        ];

//        $location = is_object($schedule) ? $schedule->location : 'none';

        // if($staffAraId == '4534')
        // Log::info($staffAraId.': '.$location.' - '.$show.', $date: '.$date.', day: '.strtolower($date->format('l')).', $attendance_count: '.$attendance_count);

        if(!is_null($first_in_hour) && $first_in_hour >= 9){
            $color = $colors['On duty - late'];
        }
        $data = [
            'location' => $location,
            'colour' => isset($color) ? $color : ($colors[$location.' - '.$show] ?? '#ffb563'),
            'weekday' => strtolower($date->format('l')),
            'schedule' => $schedule
        ];

        return $data;
    }

    public function markExemption(Request $request)
    {

    }
}
