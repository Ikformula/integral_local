<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ManagerAbsenceLatenessAuthorization;
use App\Models\StaffAttendance;
use App\Models\StaffMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StaffAttendanceArchiverController extends Controller
{
    public function previousDayArchiver(Request $request)
    {
        $currentDate = Carbon::now();
        $from_date = $currentDate->subDays(2);
        $to_date = Carbon::now()->subDay();
        $page = $request->filled('page') ? $request->page : 1;

        // Define the URL to call
        $url = route('frontend.archive.staff.attendance');

// Define the parameters to pass
        $params = array(
            "from_date" => $from_date->toDateString(),
            "to_date" => $to_date->toDateString(),
            "page" => $page,
            "mode" => "nightly"
        );

// Append the parameters to the URL as a query string
        $url .= "?" . http_build_query($params);

// Initialize a curl session
        $curl = curl_init();

// Set the curl options
        curl_setopt($curl, CURLOPT_URL, $url); // Set the URL to call
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL verification

// Execute the curl request
        $response = curl_exec($curl);

// Close the curl session
        curl_close($curl);

// Display the response
        echo $response;

    }

    public function archiveStaffAttendance(Request $request)
    {
        $earliest_start_date = '2025-06-24';
        $latest_end_date = '2025-06-26';
        $page = $request->filled('page') ? $request->page : 1;
        // Retrieve the start and end dates from the request
        if ($request->filled('from_date')) {
            $from_date = Carbon::parse($request->from_date);
        } else {

            // Get most recent done date from DB
            $most_recent_done = DB::table('attendance_bulk_archiving_tracker')
                ->select('last_date')
                ->where([
                    'page_number' => $page
                ])->first();

            $from_date_sub_one = Carbon::parse($most_recent_done->last_date);
            $from_date = $from_date_sub_one->addDay();
        }

        $from_date_temp = $from_date->copy();
        $from_date_temp_2 = $from_date->copy();

        if($request->filled('to_date')){
            $to_date = Carbon::parse($request->to_date);
        } else {
            $from_date_bkp = $from_date->copy();
            $to_date = $from_date_bkp->addWeek();
        }

        $latest_end_day = Carbon::parse($latest_end_date);
        if($from_date <= $latest_end_day && $latest_end_day <= $to_date){
            return 'Archive Completed already: '.now()->toDayDateTimeString();
        }

        $to_date_temp_2 = $to_date->copy();
        $dates = [];
        // form a dates array to use in the view
        for($date = $from_date_temp; $date <= $to_date; $date->addDay()){
            $temp_date = $date->copy();
            $dates[] = ['str' => $date->toDateString(), 'week_day' => $temp_date->dayName];
        }

        $staff_ara_id = $request->staff_ara_id;
        $department = $request->department;
        $staff_name = $request->staff_name;
        $auth_perm = 0;
        $per_page = 250;
            $auth_perm = 1;
            $staffMembers = StaffMember::select(['surname', 'other_names', 'staff_ara_id', 'department_name',  'job_title', 'shift_nonshift'])
                ->when($staff_ara_id, function ($query, $staff_ara_id) {
                    return $query->where('staff_ara_id', $staff_ara_id);
                })
                ->when($department, function ($query, $department) {
                    return $query->where('department_name', 'LIKE', '%'.$department.'%')->orWhere('department_name_2', 'LIKE', '%'.$department.'%');
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
//                ->take(2)
                ->paginate($per_page);



        if(!$staffMembers->count())
            return redirect()->route('frontend.user.dashboard')->withErrors('No records found');


        // Initialize an array to store the attendance data
        unset($attendanceData);
        $attendanceData = [];
        $attendance_to_archive = [];

        $AllattendanceEmailLogs = DB::table('attendance_email_logs')
            ->whereIn('staff_ara_id', $staffMembers->pluck('staff_ara_id'))
            ->orderBy('created_at', 'DESC')
            ->get();

        $allStaffSchedules = DB::table('staff_remote_schedules')
            ->whereIn('staff_ara_id', $staffMembers->pluck('staff_ara_id'))
            ->get();

        $allAuthorizations = ManagerAbsenceLatenessAuthorization::all();

        $allStaffAttendances = StaffAttendance::select([
                'staff_ara_id',
                'week_day',
                'hour',
                'minutes',
                'meridien',
                'day',
                'direction',
                'created_at'
            ])
        ->whereBetween('created_at', [$from_date_temp_2, $to_date_temp_2->addDay()])->get();

        $attendance_archives = DB::table('staff_attendance_archives')
            ->select([
                'staff_ara_id',
                'date_string',
                'json_data'
            ])
            ->whereBetween('created_at', [$from_date_temp_2, $to_date_temp_2->addDay()])->get();

        $todays_date = now()->toDateString();

        foreach ($staffMembers as $staffMember) {
            $staff_ara_id = $staffMember->staff_ara_id;
            $staffAttendances[$staff_ara_id] = $allStaffAttendances->where('staff_ara_id', $staff_ara_id);
            unset($attendanceEmailLogs);
            $attendanceEmailLogs = $AllattendanceEmailLogs
                ->where('staff_ara_id', $staff_ara_id)
                ->whereIn('type', ['lateness', 'absence']);

//            $attendanceEmailLogs = DB::table('attendance_email_logs')
//                ->where('staff_ara_id', $staff_ara_id)
//                ->whereIn('type', ['lateness', 'absence'])
//                ->orderBy('created_at', 'DESC')
//                ->get();

            if(!is_null($attendanceEmailLogs)){
                $attendances[$staff_ara_id]['lateness']['last'] = $attendanceEmailLogs->where('type','lateness')->first();
                $attendances[$staff_ara_id]['lateness']['emails_count'] = $attendanceEmailLogs->where('type','lateness')->count();
                $attendances[$staff_ara_id]['absence']['last'] = $attendanceEmailLogs->where('type','absence')->first();
                $attendances[$staff_ara_id]['absence']['emails_count'] = $attendanceEmailLogs->where('type','absence')->count();
            }else{
                $attendances[$staff_ara_id]['lateness']['last'] = null;
                $attendances[$staff_ara_id]['lateness']['emails_count'] = 0;
                $attendances[$staff_ara_id]['absence']['last'] = null;
                $attendances[$staff_ara_id]['absence']['emails_count'] = 0;
            }


            if(!is_null($attendances[$staff_ara_id]['lateness']['last'])){
                $tym = Carbon::parse($attendances[$staff_ara_id]['lateness']['last']->created_at);
                $attendances[$staff_ara_id]['lateness']['last'] = $tym->diffForHumans();
            }else{
                $attendances[$staff_ara_id]['lateness']['last'] = 'none';
            }

            if(!is_null($attendances[$staff_ara_id]['absence']['last'])){
                $tym = Carbon::parse($attendances[$staff_ara_id]['absence']['last']->created_at);
                $attendances[$staff_ara_id]['absence']['last'] = $tym->diffForHumans();
            }else{
                $attendances[$staff_ara_id]['absence']['last'] = 'none';
            }

            $schedule[$staff_ara_id] = $allStaffSchedules
                ->where('staff_ara_id', $staff_ara_id);

            $authorizations[$staff_ara_id] = $allAuthorizations->where('staff_ara_id', $staff_ara_id);
            foreach($dates as $date){

                $date_string = $date['str'];

                $date_day = $date['week_day'];

                if($attendance_archives->where('staff_ara_id', $staff_ara_id)->where('date_string', $date_string)->first()){
                    unset($attendance_archive);
                    $attendance_archive = $attendance_archives->where('staff_ara_id', $staff_ara_id)->where('date_string', $date_string)->first();
                    $attendances[$staff_ara_id][$date_string] = json_decode($attendance_archive->json_data, true);
//                    dd($attendances[$staff_ara_id][$date_string]);
                }else {

                    $attendances[$staff_ara_id][$date_string]['movements'] = $staffAttendances[$staff_ara_id]->whereBetween('created_at', [$date_string . ' 00:00:00', $date_string . ' 23:59:59']);
                    $attendances[$staff_ara_id][$date_string]['hours on prem'] = 0;

                    $count_attendance[$staff_ara_id][$date_string] = isset($attendances[$staff_ara_id][$date_string]['movements']) ? count($attendances[$staff_ara_id][$date_string]['movements']) : 0;
                    if ($count_attendance[$staff_ara_id][$date_string] >= 1) {
                        $first_in = $attendances[$staff_ara_id][$date_string]['movements']
                            ->where('direction', 'in')
                            ->sortBy('id')
                            ->first();

                        $last_in = $attendances[$staff_ara_id][$date_string]['movements']
                            ->where('direction', 'in')
                            ->sortByDesc('id')
                            ->first();
                        $last_out = $attendances[$staff_ara_id][$date_string]['movements']
                            ->where('direction', 'out')
                            ->sortByDesc('id')
                            ->first();

                        if (!$first_in && !is_null($last_out)) {
                            $attendances[$staff_ara_id][$date_string]['schedule'] = $this->checkDaySchedule($schedule[$staff_ara_id], $date_string, $count_attendance[$staff_ara_id][$date_string], $last_out->hour, false);
                            $lateOrAbsentStatus = 'Not marked in';
                        } else {

                            $attendances[$staff_ara_id][$date_string]['schedule'] = $this->checkDaySchedule($schedule[$staff_ara_id], $date_string, $count_attendance[$staff_ara_id][$date_string], $first_in->hour);
                            $lateOrAbsentStatus = $this->lateOrAbsentStatus($first_in->hour);
                        }

                        $attendances[$staff_ara_id][$date_string]['hours on prem'] = $this->calcHoursInPrem($attendances[$staff_ara_id][$date_string]['movements']);

                        if ($first_in && !$last_out) {
                            $attendances[$staff_ara_id][$date_string]['day_attendance'] = [
                                'date' => $date_string,
                                'resumed' => $this->resolveTo12HourClock($first_in->hour) . ':' . $first_in->minutes . $first_in->meridien,
                                'closed' => '-',
                                'hours' => 0,
                                'weekday' => $date_day,
                                'day_schedule' => $this->checkDaySchedule($schedule[$staff_ara_id], $date_string, count($attendances[$staff_ara_id][$date_string]['movements']), $first_in->hour),
                                'status' => $lateOrAbsentStatus,
                                'closing_status' => $lateOrAbsentStatus == 'absent' ? ' ' : $this->leftEarlyStatus(null, $first_in->created_at, $last_in),
                            ];
                        } else if ($first_in && $last_out) {
                            $attendances[$staff_ara_id][$date_string]['day_attendance'] = [
                                'date' => $date_string,
                                'resumed' => $this->resolveTo12HourClock($first_in->hour) . ':' . $first_in->minutes . $first_in->meridien,
                                'closed' => $this->resolveTo12HourClock($last_out->hour) . ':' . $last_out->minutes . $last_out->meridien,
                                'hours' => $attendances[$staff_ara_id][$date_string]['hours on prem'],
                                'weekday' => $first_in->week_day,
                                'day_schedule' => $this->checkDaySchedule($schedule[$staff_ara_id], $date_string, count($attendances[$staff_ara_id][$date_string]['movements']), $first_in->hour),
                                'status' => $lateOrAbsentStatus,
                                'closing_status' => $lateOrAbsentStatus == 'absent' ? ' ' : $this->leftEarlyStatus($last_out, $first_in->created_at, $last_in),
                            ];
                        } else if (!$first_in && $last_out) {
                            $attendances[$staff_ara_id][$date_string]['day_attendance'] = [
                                'date' => $date_string,
                                'resumed' => ' ',
                                'closed' => $this->resolveTo12HourClock($last_out->hour) . ':' . $last_out->minutes . $last_out->meridien,
                                'hours' => $attendances[$staff_ara_id][$date_string]['hours on prem'],
                                'weekday' => $last_out->week_day,
                                'day_schedule' => $this->checkDaySchedule($schedule[$staff_ara_id], $date_string, count($attendances[$staff_ara_id][$date_string]['movements']), $last_out->hour, false),
                                'status' => $lateOrAbsentStatus,
                                'closing_status' => $lateOrAbsentStatus == 'absent' ? ' ' : $this->leftEarlyStatus($last_out, $last_out->created_at, $last_in),
                            ];
                        }
                    } else {
                        $attendances[$staff_ara_id][$date_string]['day_attendance'] = [
                            'date' => $date_string,
                            'resumed' => ' ',
                            'closed' => ' ',
                            'hours' => 0,
                            'weekday' => $date_day,
                            'day_schedule' => $this->checkDaySchedule($schedule[$staff_ara_id], $date_string, count($attendances[$staff_ara_id][$date_string]['movements'])),
                            'status' => 'absent',
                            'closing_status' => ' ',
                        ];
                    }


                    if ($attendances[$staff_ara_id][$date_string]['day_attendance']['status'] == 'late' || $attendances[$staff_ara_id][$date_string]['day_attendance']['status'] == 'absent' && !in_array($date['week_day'], ['Saturday', 'Sunday'])) {
                        $attendances[$staff_ara_id][$date_string]['day_attendance']['manager_auth'] = $this->findAuthorizationForDate($date_string, $authorizations[$staff_ara_id]);

                    }

                    if($todays_date != $date_string) {
                        $attendance_to_archive[] = [
                            'staff_ara_id' => $staff_ara_id,
                            'date_string' => $date_string,
                            'json_data' => json_encode($attendances[$staff_ara_id][$date_string]),
                            'created_at' => $date_string . ' 00:00:00',
                            'updated_at' => $date_string . ' 00:00:00'
                        ];
                    }
                }
            }
            unset($staffAttendances);
        }

        if(sizeof($attendance_to_archive)){
            DB::table('staff_attendance_archives')
                ->insert($attendance_to_archive);

            if(!$request->has('mode') && $request->mode != 'nightly') {
                DB::table('attendance_bulk_archiving_tracker')
                    ->where('page_number', $page)
                    ->update([
                        'last_date' => $to_date->toDateString()
                    ]);
            }
        }



        // Pass the attendance data to the view and display the HTML table
//        return view('frontend.staff_attendance.all_staff_attendance')->with([
//            'staffMembers' => $staffMembers,
//            'attendances' => $attendances,
//            'from_date' => $from_date,
//            'from_date_temp' => $from_date_temp,
//            'to_date' => $to_date,
//            'dates' => $dates,
//            'auth_perm' => $auth_perm,
//            'params' => $request->query()
//        ]);

        return [
            'from_date' => $from_date,
            'from_date_temp' => $from_date_temp,
            'to_date' => $to_date,
            'dates' => $dates,
        ];

        // Encode the array into a JSON string with pretty print
        $json = json_encode([
            'staffMembers' => $staffMembers,
            'attendances' => $attendances,
            'from_date' => $from_date,
            'from_date_temp' => $from_date_temp,
            'to_date' => $to_date,
            'dates' => $dates,
            'auth_perm' => $auth_perm,
            'params' => $request->query()
        ], JSON_PRETTY_PRINT);


//       Storage::disk('public')->put($request->from_date."_to_".$request->to_date."_".time()."_".now()->toDateTimeString().".json", $json);

       return $request->from_date."_to_".$request->to_date."_".time()."_".now()->toDateTimeString().".json";
    }

    public function resolveTo12HourClock($hour)
    {
        return $hour > 12 ? ($hour - 12) : $hour;
    }

    public static function findAuthorizationForDate($dateToCheck, $authorizations)
    {
        if(is_null($authorizations))
            return null;

        // Find the first authorization where the date is between start_date and end_date (inclusive)
        $authorization = $authorizations->where('start_date', '<=', $dateToCheck)
            ->where('end_date', '>=', $dateToCheck)
            ->first();

        // If not found, check if the date is greater than or equal to start_date when is_indefinite is 1
        if (!$authorization) {
            $authorization = $authorizations->where('is_indefinite', 1)
                ->where('start_date', '<=', $dateToCheck)
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

    public function leftEarlyStatus($lastOut = null, $created_at = null, $last_in = null)
    {
        if(!is_null($lastOut) && !is_null($last_in) && $last_in->created_at > $lastOut->created_at){
            return 'wasn\'t clocked out';
        }

        if(is_null($lastOut)){
            if(is_null($created_at) || substr(now(), 0, 10) == substr($created_at, 0, 10)) {
                return ' ';
            }else{
                return 'wasn\'t clocked out';
            }
        }

        if($lastOut->hour < 17){
            return 'closed early';
        }

        return '';
    }

    public function calcHoursInPrem($attendances)
    {
       if(is_null($attendances))
           return 0;

        $inTime = null;
        $outTime = null;
        $totalHours = 0;

// Loop through the attendance records
        foreach ($attendances as $attendance) {
            if ($attendance->direction === 'in') {
                $inTime = Carbon::parse($attendance->created_at);
            } elseif ($attendance->direction === 'out') {
                $outTime = Carbon::parse($attendance->created_at);

                if($inTime) {
                    // Calculate the duration between the in and out times
                    $duration = $inTime->diffInMinutes($outTime) / 60.0;
                    $totalHours += $duration;
                }

                $inTime = null;
                $outTime = null;
            }
        }

        return number_format($totalHours);
    }

    public function checkDaySchedule($schedule_all, $date, $attendance_count = null, $first_in_hour = null, $valid_first_in = true)
    {
        $date = Carbon::parse($date);
        if(!is_null($schedule_all)) {
//            $schedule = $schedule_all
//                ->where('week_day', strtolower($date->format('l')))
//                ->where(function ($query) use ($date) {
//                    $query->where('commenced_on', '<=', $date)
//                        ->where(function ($query) use ($date) {
//                            $query->whereNull('ended_on')
//                                ->orWhere('ended_on', '>=', $date);
//                        });
//                })
//                ->first();

            // Retrieve all schedules for the given week_day
            $schedules = $schedule_all
                ->where('week_day', strtolower($date->format('l')));

            // Filter the results in PHP
            $schedule = $schedules->first(function ($schedule) use ($date) {
                return ($schedule->commenced_on <= $date && ($schedule->ended_on === null || $schedule->ended_on >= $date));
            });


        }else{
            $schedule = null;
        }

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
        }else if(!is_null($schedule) && !empty($schedule->location)){
            $location = $schedule->location;
        }else{
            $location = 'On duty';
        }


        $colors = [
            'On duty - no show' => '#FFADAD',
            'On duty - show' => '#FFFFFF',
            'Remote - no show' => '#F5F5F5',
            'Remote - show' => '#FDFFB6',
            'Remote - weekend' => '#CAFFBF',
            'On duty - late' => '#F07166',
            'On duty - not marked in' => '#6FE3E1',
        ];


        if(!is_null($first_in_hour) && $first_in_hour >= 9){
            $color = $colors['On duty - late'];
        }

        if(isset($location) && $location == 'Remote' && !is_null($first_in_hour)){
            $color = $colors['Remote - show'];
        }

        if($location == 'On duty' && !$valid_first_in && $first_in_hour){
            // not marked in but marked out
            $color = $colors['On duty - not marked in'];
        }

//        if(strtolower($date->format('l')) == 'tuesday')
//            dd($color);

        $data = [
            'location' => $location,
            'colour' => isset($color) ? $color : ($colors[$location.' - '.$show] ?? '#ffb569'),
            'weekday' => strtolower($date->format('l')),
            'schedule' => $schedule
        ];

        return $data;
    }


}
