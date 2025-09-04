<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ManagerAbsenceLatenessAuthorization;
use App\Models\StaffAttendance;
use App\Models\StaffMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffAttendanceAltController extends Controller
{
    public function index()
    {
        $todays_attendances = StaffAttendance::select(['direction', 'created_at'])->whereDate('created_at', Carbon::today())->get();
        $stats['ins'] = $todays_attendances->where('direction', 'in')->count();
        $stats['outs'] = $todays_attendances->where('direction', 'out')->count();
        $stats['on_prem'] = $stats['ins'] - $stats['outs'];

//        dd($stats);
        return view('frontend.staff_attendance.index')->with([
            'stats' => $stats
        ]);
    }

    public function fetchStaffAttendanceE(Request $request)
    {
        // Retrieve the start and end dates from the request
        if($request->filled('from_date')){
            $startDate = Carbon::parse($request->from_date);
        }else{
            $startDate = Carbon::now()->startOfMonth();
        }

        if($request->filled('to_date')){
            $endDate = Carbon::parse($request->to_date);
        }else{
            $endDate = Carbon::now();
        }

        // Fetch all staff members
        $staffMembers = StaffMember::all();

        // Initialize an array to store the attendance data
        $attendanceData = [];

        foreach ($staffMembers as $staffMember) {
            // Fetch the attendance records for the staff member within the date range
            $query = StaffAttendance::where('staff_ara_id', $staffMember->staff_ara_id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at');

            // If "filter_late" field is passed and set to true, fetch only staff members who were late at least once
            if ($request->filled('filter_late') && $request->boolean('filter_late')) {
                $query->where('direction', 'in')
                    ->where('hour', '>=', 9);
            }

            $attendances = $query->get();


            // Initialize an array to store the attendance data for the staff member
            $staffAttendanceData = [
                'serial_number' => $staffMember->id,
                'name' => $staffMember->other_names . ' ' . $staffMember->surname,
                'staff_ara_id' => $staffMember->staff_ara_id,
            ];

            // Iterate through the attendance records and populate the attendance data array
            foreach ($attendances as $attendance) {
                $date = $attendance->created_at->toDateString();
                $direction = $attendance->direction;

                // Check if the date exists in the attendance data array, if not, create it
                if (!isset($staffAttendanceData[$date])) {
                    $staffAttendanceData[$date] = [];
                }

                // Store the direction (in/out) for the date
                $staffAttendanceData[$date]['direction'] = $direction;

                // Store the time (hour:minutes) for the date based on the direction
                if ($direction === 'in') {
                    $staffAttendanceData[$date]['time_in'] = $attendance->hour . ':' . $attendance->minutes;
                } elseif ($direction === 'out') {
                    $staffAttendanceData[$date]['time_out'] = $attendance->hour . ':' . $attendance->minutes;
                }
            }

            // If "filter_late" field is passed and set to true, check if the staff member was late at least once
            if ($request->filled('filter_late') && $request->boolean('filter_late') && !empty($staffAttendanceData)) {
                $attendanceData[] = $staffAttendanceData;
            } elseif (!$request->filled('filter_late')) {
                // If "filter_late" field is not passed, add the staff member's attendance data to the main attendance data array
                $attendanceData[] = $staffAttendanceData;
            }
        }

//        return $attendanceData;
        // Pass the attendance data to the view and display the HTML table
        return view('frontend.staff_attendance.all_staff_attendance')->with([
            'attendanceData' => $attendanceData,
            'from_date' => $startDate,
            'to_date' => $endDate
        ]);
    }

    public function fetchStaffAttendance(Request $request)
    {
        $auth_user = auth()->user();
        // Retrieve the start and end dates from the request
        if ($request->filled('from_date')) {
            $from_date = Carbon::parse($request->from_date);
            $from_date_temp = $from_date->copy();
        } else {
            $from_date = Carbon::now()->startOfMonth();
            $from_date_temp = $from_date->copy();
        }

        if($request->filled('to_date')){
            $to_date = Carbon::parse($request->to_date);
        } else {
            $to_date = Carbon::now();
        }

        $dates = [];
        // form a dates array to use in the view
        for($date = $from_date_temp; $date <= $to_date; $date->addDay()){
            $temp_date = $date->copy();
            $dates[] = ['str' => $date->toDateString(), 'week_day' => $temp_date->dayName];
        }

        $staff_ara_id = $request->staff_ara_id;
        $department = $request->department;
        $staff_name = $request->staff_name;


        if($auth_user->can('manage all staff attendance')){
            $staffMembers = StaffMember::select(['surname', 'other_names', 'staff_ara_id', 'department_name'])
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
                })->orderBy('staff_ara_id', 'ASC')
                ->get();

        }else if($auth_user->can('manage own unit info')){
            $staffMembers = StaffMember::select(['surname', 'other_names', 'staff_ara_id', 'department_name'])
                ->where('department_name', $auth_user->department_name)
                ->orderBy('staff_ara_id', 'ASC')
                ->get();
        }else{
            $staffMembers = StaffMember::select(['surname', 'other_names', 'staff_ara_id', 'department_name'])->where('email', $auth_user->email)
                ->orderBy('staff_ara_id', 'ASC')
                ->get();
        }

        // Initialize an array to store the attendance data
        $attendanceData = [];

        foreach ($staffMembers as $staffMember) {
            $staff_ara_id = $staffMember->staff_ara_id;
            $attendances[$staff_ara_id]['lateness']['last'] = DB::table('attendance_email_logs')
                ->where('staff_ara_id', $staff_ara_id)
                ->where('type', 'lateness')
                ->orderBy('created_at', 'DESC')
                ->first();

            if(!is_null($attendances[$staff_ara_id]['lateness']['last'])){
                $tym = Carbon::parse($attendances[$staff_ara_id]['lateness']['last']->created_at);
                $attendances[$staff_ara_id]['lateness']['last'] = $tym->diffForHumans();
            }else{
                $attendances[$staff_ara_id]['lateness']['last'] = 'none';
            }

            $attendances[$staff_ara_id]['lateness']['emails_count'] = DB::table('attendance_email_logs')
                ->where('staff_ara_id', $staff_ara_id)
                ->where('type', 'lateness')
                ->orderBy('created_at', 'DESC')
                ->count();

            $attendances[$staff_ara_id]['absence']['last'] = DB::table('attendance_email_logs')
                ->where('staff_ara_id', $staff_ara_id)
                ->where('type', 'absence')
                ->orderBy('created_at', 'DESC')
                ->first();

            if(!is_null($attendances[$staff_ara_id]['absence']['last'])){
                $tym = Carbon::parse($attendances[$staff_ara_id]['absence']['last']->created_at);
                $attendances[$staff_ara_id]['absence']['last'] = $tym->diffForHumans();
            }else{
                $attendances[$staff_ara_id]['absence']['last'] = 'none';
            }

            $attendances[$staff_ara_id]['absence']['emails_count'] = DB::table('attendance_email_logs')
                ->where('staff_ara_id', $staff_ara_id)
                ->where('type', 'absence')
                ->orderBy('created_at', 'DESC')
                ->count();


            foreach($dates as $date){

                $date_string = $date['str'];
                $date_day = $date['week_day'];

                $attendances[$staff_ara_id][$date_string]['movements'] = StaffAttendance::where('staff_ara_id', $staff_ara_id)->whereBetween('created_at', [$date_string.' 00:00:00', $date_string.' 23:59:59'])->get();
                $attendances[$staff_ara_id][$date_string]['hours on prem'] = 0;

                $count_attendance[$staff_ara_id][$date_string] = isset($attendances[$staff_ara_id][$date_string]['movements']) ? count($attendances[$staff_ara_id][$date_string]['movements']) : 0;

                if($count_attendance[$staff_ara_id][$date_string] >= 1) {
                    $first_in = $attendances[$staff_ara_id][$date_string]['movements']
                        ->where('direction', 'in')
                        ->sortBy('id')
                        ->first();
                    $last_out = $attendances[$staff_ara_id][$date_string]['movements']
                        ->where('direction', 'out')
                        ->sortByDesc('id')
                        ->first();
                    $attendances[$staff_ara_id][$date_string]['schedule'] = $this->checkDaySchedule($staff_ara_id, $date_string, $count_attendance[$staff_ara_id][$date_string], $first_in->hour);

                    $attendances[$staff_ara_id][$date_string]['hours on prem'] = $this->calcHoursInPrem($staff_ara_id, $date_string);
                    if(!$last_out){
                        $attendances[$staff_ara_id][$date_string]['day_attendance'] = [
                            'date' => $date_string,
                            'resumed' => $first_in->hour . ':' . $first_in->minutes,
                            'closed' => '-',
                            'hours' => 0,
                            'weekday' => $date_day,
                            'day_schedule' => $this->checkDaySchedule($staff_ara_id, $date_string, count($attendances[$staff_ara_id][$date_string]['movements']), $first_in->hour),
                            'status' => $this->lateOrAbsentStatus($first_in->hour),
                        ];
                    } else {
                        $attendances[$staff_ara_id][$date_string]['day_attendance'] = [
                            'date' => $date_string,
                            'resumed' => $first_in->hour . ':' . $first_in->minutes,
                            'closed' => $last_out->hour . ':' . $last_out->minutes,
                            'hours' => $attendances[$staff_ara_id][$date_string]['hours on prem'],
                            'weekday' => $first_in->week_day,
                            'day_schedule' => $this->checkDaySchedule($staff_ara_id, $date_string, count($attendances[$staff_ara_id][$date_string]['movements']), $first_in->hour),
                            'status' => $this->lateOrAbsentStatus($first_in->hour),
                        ];
                    }
                }else{
                    $attendances[$staff_ara_id][$date_string]['day_attendance'] = [
                        'date' => $date_string,
                        'resumed' => '-',
                        'closed' => '-',
                        'hours' => 0,
                        'weekday' => $date_day,
                        'day_schedule' => $this->checkDaySchedule($staff_ara_id, $date_string, count($attendances[$staff_ara_id][$date_string]['movements'])),
                            'status' => 'absent',
                    ];
                }

                if($attendances[$staff_ara_id][$date_string]['day_attendance']['status'] == 'late' || $attendances[$staff_ara_id][$date_string]['day_attendance']['status'] == 'absent' && !in_array($date['week_day'], ['Saturday', 'Sunday'])) {
                    $attendances[$staff_ara_id][$date_string]['day_attendance']['manager_auth'] = $this->findAuthorizationForDate($date_string, $staff_ara_id);

                }

            }
        }

        if(!$staffMembers->count())
            return redirect()->route('frontend.user.dashboard')->withErrors('No records found');

        // Pass the attendance data to the view and display the HTML table
        return view('frontend.staff_attendance.all_staff_attendance')->with([
            'staffMembers' => $staffMembers,
            'attendances' => $attendances,
            'from_date' => $from_date,
            'from_date_temp' => $from_date_temp,
            'to_date' => $to_date,
            'dates' => $dates
        ]);
    }

    public static function findAuthorizationForDate($dateToCheck, $staff_ara_id)
    {
        // Find the first authorization where the date is between start_date and end_date (inclusive)
        $authorization = ManagerAbsenceLatenessAuthorization::where('start_date', '<=', $dateToCheck)
            ->where('end_date', '>=', $dateToCheck)
            ->where('staff_ara_id', $staff_ara_id)
            ->first();

        // If not found, check if the date is greater than or equal to start_date when is_indefinite is 1
        if (!$authorization) {
            $authorization = ManagerAbsenceLatenessAuthorization::where('is_indefinite', 1)
                ->where('start_date', '<=', $dateToCheck)
                ->where('staff_ara_id', $staff_ara_id)
                ->first();
        }

        return $authorization;
    }

    public function lateOrAbsentStatus($firstIn = null)
    {
        if(is_null($firstIn)){
            return 'absent';
        }

        if($firstIn >= 9){
            return 'late';
        }

        return 'early';
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
            'On duty - late' => '#F07166',
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
            $attendance->operator_user_id = $request->operator_user_id;
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

            $previous_attendance_for_today = StaffAttendance::where('staff_ara_id', $staff->staff_ara_id)->whereDate('created_at', Carbon::today())->latest()->first();
            if (!$previous_attendance_for_today) {
                $attendance->direction = 'in';
            } else if ($previous_attendance_for_today->direction == 'in') {
                $attendance->direction = 'out';
            } else {
                $attendance->direction = 'in';
            }

            $attendance->save();
            $attendance_entered = true;

            $todays_attendances = StaffAttendance::select(['direction', 'created_at'])->whereDate('created_at', Carbon::today())->get();
            $stats['ins'] = $todays_attendances->where('direction', 'in')->count();
            $stats['outs'] = $todays_attendances->where('direction', 'out')->count();
            $stats['on_prem'] = $stats['ins'] - $stats['outs'];


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

}
