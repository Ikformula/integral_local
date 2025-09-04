<?php

namespace App\Services;

use App\Models\WeekRange;
use Carbon\Carbon;

class WeekRangeService
{
    public static function currentWeekChecker()
    {
        // Step 1: Calculate the start and end dates for the current year
        $startOfYear = Carbon::now()->startOfYear();
        $currentMonday = Carbon::now()->startOfWeek(Carbon::MONDAY); // Start of the current week (Monday)
        $currentSunday = Carbon::now()->endOfWeek(Carbon::SUNDAY); // End of the current week (Sunday)

        // Step 2: Find the last recorded week to start checking missing weeks
        $lastRecordedWeek = WeekRange::orderBy('from_date', 'desc')->first();

        if (!$lastRecordedWeek) {
            // If no weeks are recorded at all, create the weeks from the start of the year to the current week
            $date = $startOfYear;
        } else {
            // Otherwise, start from the week after the last recorded
            $startDate = Carbon::parse($lastRecordedWeek->from_date);
            $date = $startDate->addWeek();
        }

        // Step 3: Iterate from the start date to the current week
        while ($date->lte($currentSunday)) {
            $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
            $weekEnd = $date->copy()->endOfWeek(Carbon::SUNDAY);

            // Check if the week exists in the database
            $existingWeek = WeekRange::where('from_date', $weekStart->format('Y-m-d'))
                ->where('to_date', $weekEnd->format('Y-m-d'))
                ->first();

            if (!$existingWeek) {
                // Create the missing week record
                self::createWeekRecord($weekStart, $weekEnd);
            }

            $date->addWeek(); // Move to the next week
        }

        // Finally, return the record for the current week
        return WeekRange::where('from_date', $currentMonday->format('Y-m-d'))
            ->where('to_date', $currentSunday->format('Y-m-d'))
            ->first();
    }

    private static function createWeekRecord($start, $end)
    {
        $newWeekRange = new WeekRange();
        $newWeekRange->from_date = $start->format('Y-m-d');
        $newWeekRange->to_date = $end->format('Y-m-d');
        $weekNumber = $start->weekOfYear; // Calculate the week number
        $newWeekRange->week_number = $weekNumber;

        // Calculate dominant month
        $dayCount = [];
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $monthNumber = $date->month;
            $monthName = $date->format('F');
            if (!isset($dayCount[$monthNumber])) {
                $dayCount[$monthNumber] = [
                    'count' => 0,
                    'month' => $monthName,
                    'year' => $date->year,
                ];
            }
            $dayCount[$monthNumber]['count']++;
        }

        $dominantMonth = collect($dayCount)->sortByDesc('count')->first();
        $month = date_parse($dominantMonth['month']);
        $newWeekRange->in_month_num = $month['month'];
        $newWeekRange->in_month = $dominantMonth['month'];
        $newWeekRange->in_year = $dominantMonth['year'];

        $newWeekRange->save();

        return $newWeekRange;
    }

    public static function getWeekRange($date)
    {
        self::currentWeekChecker();
        $date = Carbon::parse($date);
        $week_range = WeekRange::whereDate('from_date', '<=', $date)
            ->whereDate('to_date', '>=', $date)
            ->first();

        return $week_range;
    }

    public static function serviceNowWeekStats($group_id, $from_date, $to_date)
    {
        $tickets = \App\Models\ServiceNowTicket::where('group_id', $group_id)->whereBetween('created_at', [$from_date, $to_date])->get();
        $stats = [];
        $stats['total'] = $tickets->count();
        $stats['phone'] = $tickets->where('origin_type', 'phone call')->count();
        $stats['walk in'] = $tickets->where('origin_type', 'walk in')->count();
        $stats['email'] = $tickets->where('origin_type', 'email')->count();
        $stats['closed'] = $tickets->where('status', 'closed')->count();
        $stats['closed'] += $tickets->where('status', 'resolved')->count();
        $stats['open'] = $tickets->where('status', 'open')->count();
        $stats['open'] += $tickets->where('status', 'pending')->count();

        return $stats;
    }

    public static function weekBeforeAWeek(WeekRange $week)
    {
        return WeekRange::where('id', '<', $week->id)
            ->latest()
            ->first();
    }
}
