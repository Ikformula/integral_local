<?php


namespace App\Http\Controllers\Traits;


use App\Models\StaffAttendanceDailySummary;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait AttendanceTrait
{
    public function dailySummaries(Request $request)
    {
        $request->validate([
            'from_date' => 'date|before:to_date',

        ]);

        if($request->filled(['from_date', 'to_date'])){
            $from_date = Carbon::parse($request->from_date);
            $to_date = Carbon::parse($request->to_date);
            $summaries = StaffAttendanceDailySummary::whereBetween('days_date', [$from_date->toDateString(), $to_date->toDateString()])->get();
        }else {
            $summaries = StaffAttendanceDailySummary::where('year', now()->year)->get();
        }

        $flattened = $summaries->map(function ($attendance){
            $staff = $attendance->staff_member;
            $attendance->staff_name = $staff->full_name;
            $attendance->department = $staff->department_name;
            $attendance->shift_status = $staff->shift_nonshift;
            $attendance->employment_category = $staff->employment_category;
            return $attendance;
        });

        return $flattened;
    }
}
