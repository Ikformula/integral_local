<?php

namespace App\Http\Controllers;

use App\Models\StaffAttendanceWeeklySummary;
use App\Services\WeekRangeService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\WeekRange;
use App\Models\StaffMember;
use App\Models\StaffAttendance;
use App\Models\StaffAttendanceDailySummary;
use App\Models\ManagerAbsenceLatenessAuthorization;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\AttendanceTrait;


class AttendanceSummaryController extends Controller
{
    use AttendanceTrait;

    public function processHistoricalDailySummaries(Request $request)
    {
        // Get the earliest processed date
        $earliestDate = DB::table('staff_attendance_daily_summaries')
            ->min('days_date');

        // If no records exist, set to the most recent Saturday
        if (!$earliestDate) {
            $earliestDate = Carbon::now()->previous(Carbon::SATURDAY);
        } else {
            // Convert to Carbon instance and subtract one day
            $earliestDate = Carbon::parse($earliestDate)->subDay();
        }

        // Get the Monday before the earliest date
        $mondayBeforeEarliest = $earliestDate->copy()->previous(Carbon::MONDAY);

        $stats = [];
        // Process daily summaries until we reach the Monday before the earliest date
        while ($earliestDate >= $mondayBeforeEarliest) {
            // Skip Saturday (Carbon::SATURDAY) and Sunday (Carbon::SUNDAY)
            if (!$earliestDate->isWeekend()) {
                $dateToProcess = $earliestDate->format('Y-m-d');

                // Call the processDailySummary method with the date
                $stats[$dateToProcess] = $this->processDailySummary($request, $dateToProcess);
            }

            // Move to the previous day
            $earliestDate->subDay();
        }

        return $stats;
    }

    public function processHistoricalDaysFromDB(Request $request)
    {
        $day = DB::table('attendance_summary_days')->whereNull('completed_at')->first();
        $arr = [];
        $arr['started_at'] = now();
        if($day) {
            $stats = $this->processDailySummary($request, $day->summary_date);
            $arr['ended_at'] = now();

            DB::table('attendance_summary_days')
                ->where('id', $day->id)
                ->update([
                    'completed_at' => now(),
                    'updated_at' => now(),
                    'summary_details' => json_encode(array_merge($stats, $arr))
                ]);
            return $stats;
        }

        return 'done';
    }

    public function processDailySummary(Request $request, $date_to_check = null)
    {
        // Determine the date to process
        $date_to_process = $this->determineProcessDate($request, $date_to_check);

        // Skip weekends
        if (in_array($date_to_process->dayName, ['Saturday', 'Sunday'])) {
            return null;
        }

        $week_in_focus = WeekRangeService::getWeekRange($date_to_process);

        // Get all staff members
        $staffMembers = StaffMember::select(['surname', 'other_names', 'staff_ara_id', 'department_name', 'job_title', 'shift_nonshift'])->get();

        if (!$staffMembers->count()) {
            return redirect()->route('frontend.user.dashboard')->withErrors('No records found');
        }

        $all_staff_days_summary = StaffAttendanceDailySummary::where('days_date', $date_to_process->toDateString())->get();

        // Fetch all necessary data
        $allStaffSchedules = $this->fetchAllStaffSchedules($staffMembers);
        $allAuthorizations = $this->fetchAllAuthorizations($staffMembers);
        $allStaffAttendances = $this->fetchAllStaffAttendances($staffMembers, $date_to_process);

        $date_string = $date_to_process->toDateString();
        $stats = ['date' => $date_string, 'total staff' => $staffMembers->count(), 'summary_saved' => 0, 'checked' => 0];

        foreach ($staffMembers as $staffMember) {
            $staff_ara_id = $staffMember->staff_ara_id;

            // Skip if summary already exists
            if ($all_staff_days_summary->where('staff_ara_id', $staff_ara_id)->count()) {
                continue;
            }

            $stats['checked']++;

            $staffAttendances = $allStaffAttendances->where('staff_ara_id', $staff_ara_id);
            $schedule = $allStaffSchedules->where('staff_ara_id', $staff_ara_id);
            $authorizations = $allAuthorizations->where('staff_ara_id', $staff_ara_id);
            $movements = $staffAttendances->whereBetween('created_at', [$date_string . ' 00:00:00', $date_string . ' 23:59:59']);

            $daySchedule = $this->checkDaySchedule($schedule, $date_string, $movements->count());

            $attendanceData = $this->processDayAttendance($movements, $date_string, $date_to_process, $daySchedule);

            if ($attendanceData['status'] == 'late' || $attendanceData['status'] == 'absent') {
                $attendanceData['manager_auth'] = $this->findAuthorizationForDate($date_string, $authorizations);
            }

            $this->saveDailySummary($staff_ara_id, $week_in_focus, $date_to_process, $attendanceData);

            $stats['summary_saved']++;
        }

        return $stats;
    }

    private function determineProcessDate($request, $date_to_check)
    {
        if ($request->filled('date_to_process')) {
            return Carbon::parse($request->date_to_process);
        } elseif (!is_null($date_to_check)) {
            return Carbon::parse($date_to_check);
        } else {
            if (now()->subDay()->dayName == 'Saturday') {
                return now()->subDays(2)->startOfDay();
            }else if (now()->subDay()->dayName == 'Sunday'){
                return now()->subDays(3)->startOfDay();
            }
            return now()->subDay()->startOfDay();
        }
    }

    private function fetchAllStaffSchedules($staffMembers)
    {
        return DB::table('staff_remote_schedules')
            ->whereIn('staff_ara_id', $staffMembers->pluck('staff_ara_id'))
            ->get();
    }

    private function fetchAllAuthorizations($staffMembers)
    {
        return ManagerAbsenceLatenessAuthorization::whereIn('staff_ara_id', $staffMembers->pluck('staff_ara_id'))
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    private function fetchAllStaffAttendances($staffMembers, $date_to_process)
    {
        return StaffAttendance::select([
            'staff_ara_id',
            'week_day',
            'hour',
            'minutes',
            'meridien',
            'day',
            'direction',
            'created_at'
        ])
            ->whereIn('staff_ara_id', $staffMembers->pluck('staff_ara_id')->toArray())
            ->where('day', $date_to_process->day)
            ->where('month', $date_to_process->monthName)
            ->where('year', $date_to_process->year)
            ->get();
    }

    private function processDayAttendance($movements, $date_string, $date_to_process, $daySchedule)
    {
        $first_in = $movements->where('direction', 'in')->sortBy('id')->first();
        $last_out = $movements->where('direction', 'out')->sortByDesc('id')->first();

        $attendanceData = [
            'days_date' => $date_string,
            'resumed' => $first_in ? $this->resolveTo12HourClock($first_in->hour) . ':' . $first_in->minutes . $first_in->meridien : ' ',
            'minute_in' => $first_in ? $first_in->minutes : ' ',
            'hour_in' => $first_in ? $first_in->hour : ' ',
            'closed' => $last_out ? $this->resolveTo12HourClock($last_out->hour) . ':' . $last_out->minutes . $last_out->meridien : ' ',
            'minute_out' => $last_out ? $last_out->minutes : ' ',
            'hour_out' => $last_out ? $last_out->hour : ' ',
            'hours' => $last_out ? $this->calcHoursInPrem($movements) : null,
            'week_day' => $date_to_process->dayName,
            'day_date' => $date_to_process->day,
            'day_schedule' => $daySchedule,
            'status' => $this->determineStatus($first_in, $daySchedule),
            'closing_status' => $this->leftEarlyStatus($last_out, $first_in ? $first_in->created_at : null, $movements->where('direction', 'in')->sortByDesc('id')->first()),
        ];

        return $attendanceData;
    }


    private function determineStatus($first_in, $daySchedule)
    {
        if ($daySchedule['location'] == 'Remote') {
            return 'remote';
        }

        if (!$first_in) {
            return 'absent';
        }

        return $first_in->hour >= 9 ? 'late' : 'early';
    }

    private function saveDailySummary($staff_ara_id, $week_in_focus, $date_to_process, $attendanceData)
    {
        $days_summary = new StaffAttendanceDailySummary();
        $days_summary->staff_ara_id = $staff_ara_id;
        $days_summary->week_range_id = $week_in_focus->id;
        $days_summary->week_day = $attendanceData['week_day'];
        $days_summary->days_date = $attendanceData['days_date'];
        $days_summary->day = $date_to_process->day;
        $days_summary->week_day_number = $date_to_process->weekday();
        $days_summary->month = $date_to_process->monthName;
        $days_summary->year = $date_to_process->year;
        $days_summary->resumed = $attendanceData['resumed'];
        $days_summary->closed = $attendanceData['closed'];
        $days_summary->minute_in = $attendanceData['minute_in'];
        $days_summary->hour_in = $attendanceData['hour_in'];
        $days_summary->minute_out = $attendanceData['minute_out'];
        $days_summary->hour_out = $attendanceData['hour_out'];
        $days_summary->late = $attendanceData['status'] == 'late' ? 1 : null;
        $days_summary->absent = $attendanceData['status'] == 'absent' ? 1 : null;
        $days_summary->status = $attendanceData['status'];
        $days_summary->closing_status = $attendanceData['closing_status'];
        $days_summary->hours = $attendanceData['hours'];
        $days_summary->early_leaving = $attendanceData['closing_status'] == 'closed early' ? 1 : null;
        $days_summary->remarks_and_reasons = $attendanceData['manager_auth'] ?? ($attendanceData['status'] == 'remote' ? 'Work from home' : ' ');
        $days_summary->save();
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

    public function processWeeklySummary202409301217 (Request $request, $week_range_id = null)
    {
        // Determine the week to process
        $week_range = $this->determineWeekRange($request, $week_range_id);

        if (!$week_range) {
            return response()->json(['error' => 'No valid week range found'], 400);
        }

        $staffMembers = StaffMember::all();

        $stats = [
            'week' => $week_range,
            'total_staff' => $staffMembers->count(),
            'summaries_processed' => 0,
            'summaries_created' => 0,
            'summaries_updated' => 0,
            'time_of_processing' => now(),
        ];

        $dailySummaries = StaffAttendanceDailySummary::whereBetween('days_date', [$week_range->from_date, $week_range->to_date])
            ->get();

        foreach ($staffMembers as $staffMember) {
            $staff_daily_summaries = $dailySummaries->where('staff_ara_id', $staffMember->staff_ara_id);
            $weekly_summary = $this->processStaffWeeklySummary($staffMember, $week_range, $staff_daily_summaries);

            if ($weekly_summary) {
                $stats['summaries_processed']++;
                if ($weekly_summary->wasRecentlyCreated) {
                    $stats['summaries_created']++;
                } else {
                    $stats['summaries_updated']++;
                }
            }
        }

        return response()->json($stats);
    }

    public function processWeeklySummary(Request $request, $week_range_id = null)
    {
        // Determine the week to process
        $week_range = $this->determineWeekRange($request, $week_range_id);

        if (!$week_range) {
            return response()->json(['error' => 'No valid week range found'], 400);
        }

//        $staffMembers = StaffMember::chunk(100);

        $stats = [
            'week' => $week_range,
            'total_staff' => 0,
            'summaries_processed' => 0,
            'summaries_created' => 0,
            'summaries_updated' => 0,
            'time_of_processing' => now(),
        ];

        $dailySummaries = StaffAttendanceDailySummary::whereBetween('days_date', [$week_range->from_date, $week_range->to_date])
            ->get();
        StaffMember::chunk(100, function($staffMembers) use ($dailySummaries, $week_range, $stats) {
            foreach ($staffMembers as $staffMember) {
                $staff_daily_summaries = $dailySummaries->where('staff_ara_id', $staffMember->staff_ara_id);
                $weekly_summary = $this->processStaffWeeklySummary($staffMember, $week_range, $staff_daily_summaries);

                if ($weekly_summary) {
                    $stats['summaries_processed']++;
                    if ($weekly_summary->wasRecentlyCreated) {
                        $stats['summaries_created']++;
                    } else {
                        $stats['summaries_updated']++;
                    }
                }
            }
        });

        return response()->json($stats);
    }

    private function determineWeekRange(Request $request, $week_range_id)
    {
        if ($week_range_id) {
            return WeekRange::find($week_range_id);
        }

        if ($request->filled('week_range_id')) {
            return WeekRange::find($request->week_range_id);
        }

        // Get the most recent WeekRange that has ended
        return WeekRange::where('to_date', '<', Carbon::now()->startOfDay())
            ->orderByDesc('to_date')
            ->first();
    }

    public function processHistoricalWeeklySummary(Request $request)
    {
        // Check for the earliest week in the staff_attendance_weekly_summaries table
        $earliestSummary = DB::table('staff_attendance_weekly_summaries')
//            ->select('week_range_id')
            ->whereNotNull('week_range_id')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->orderBy('week_number_in_month', 'ASC')
            ->first();

        // If there's an entry, find the corresponding WeekRange
        if ($earliestSummary && $earliestSummary->week_range_id) {
            $earliest_week = WeekRange::find($earliestSummary->week_range_id);
            if ($earliest_week) {
                $week_range = WeekRange::where('week_number', '<', $earliest_week->week_number)
                    ->orderByDesc('to_date')
                    ->where('in_year', '2024')
                    ->first();
            }
        } else {
            // Process for the most recent week
            $current_week = WeekRangeService::currentWeekChecker();
            $week_range = WeekRange::where('week_number', '<', $current_week->week_number)->orderBy('week_number', 'DESC')->first();
        }

        if ($week_range)
            return $this->processWeeklySummary($request, $week_range->id);

        return [];
    }

    private function processStaffWeeklySummary(StaffMember $staffMember, WeekRange $weekRange, $dailySummaries)
    {
//        $dailySummaries = StaffAttendanceDailySummary::where('staff_ara_id', $staffMember->staff_ara_id)
//            ->whereBetween('days_date', [$weekRange->from_date, $weekRange->to_date])
//            ->get();

        if ($dailySummaries->isEmpty()) {
            return null;
        }

        $weeklySummary = StaffAttendanceWeeklySummary::updateOrCreate(
            [
                'staff_ara_id' => $staffMember->staff_ara_id,
                'week_range_id' => $weekRange->id
            ],
            [
                'week_number_in_month' => $this->getWeekNumberInMonth($weekRange->from_date),
                'month' => Carbon::parse($weekRange->from_date)->format('F'),
                'year' => Carbon::parse($weekRange->from_date)->year,
                'late' => $dailySummaries->sum('late'),
                'absent' => $dailySummaries->sum('absent'),
                'total_work_hours' => $dailySummaries->sum('hours'),
                'early_leaving' => $dailySummaries->sum(function ($summary) {
                    return $summary->closing_status == 'closed early' ? 1 : 0;
                }),
                'remarks_and_reasons' => $this->compileWeeklyRemarks($dailySummaries)
            ]
        );

        return $weeklySummary;
    }

    private function getWeekNumberInMonth($date)
    {
        $date = Carbon::parse($date);
        return ceil($date->day / 7);
    }

    private function compileWeeklyRemarks($dailySummaries)
    {
        $remarks = [];

        foreach ($dailySummaries as $summary) {
            if (!empty(trim($summary->remarks_and_reasons)) && $summary->remarks_and_reasons != 'Work from home') {
                $remarks[] = Carbon::parse($summary->days_date)->format('D, M d') . ': ' . $summary->remarks_and_reasons;
            }
        }

        return implode("\n", $remarks);
    }

    public function getAttendanceDailySummariesApi(Request $request)
    {
        return $this->dailySummaries($request);
    }

}
