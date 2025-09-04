<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\BusinessArea;
use App\Models\DataPoint;
use App\Models\ScoreCardFormField;
use App\Models\WeekRange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessGoalsController extends Controller
{
    public function index()
    {

    }

//    protected function arranger()

    public function create(Request $request)
    {
        $accessible_business_areas = $this->accessibleBusinessSectors();
        $week_checker = $this->currentWeekChecker();
        $weeks = WeekRange::orderBy('id', 'DESC')->get();
        $business_area_id = $request->filled('business_area_id') ? $request->business_area_id :
            (count($accessible_business_areas) ? $accessible_business_areas[1]->id : 3);

        $business_area = BusinessArea::find($business_area_id);
        $form_fields = ScoreCardFormField::where('business_area_id', $business_area_id)->get();

        return view('frontend.business_goals.add-report', compact(
            'business_area',
            'form_fields',
            'weeks',
            'accessible_business_areas'
        ));
    }

    public function accessibleBusinessSectors()
    {
        $user = auth()->user();
        $staff_ara_id = isset($user->staff_member) ? $user->staff_member->staff_ara_id : null;
        $business_areas = BusinessArea::all();
        $accessible_business_areas = [];
        $count = 1;

        foreach ($business_areas as $business_area) {
            if ($user->isAdmin() || $staff_ara_id == $business_area->presenter_staff_ara_id || in_array($staff_ara_id, $business_area->co_presenters->pluck('id')->toArray())) {
                $accessible_business_areas[$count] = $business_area;
                $count++;
            }
        }
        return $accessible_business_areas;
    }

    public function currentWeekChecker()
    {
        // Step 1: Calculate the start and end dates for the current week
        $monday = Carbon::now()->startOfWeek(Carbon::MONDAY); // Start of the week (Monday)
        $sunday = Carbon::now()->endOfWeek(Carbon::SUNDAY); // End of the week (Sunday)

        // Step 2: Check if a week range with these dates exists
        $existingWeek = WeekRange::where('from_date', $monday->format('Y-m-d'))
            ->where('to_date', $sunday->format('Y-m-d'))
            ->first();

        // Step 3: If not, insert a new record for the current week
        if (!$existingWeek) {
            $newWeekRange = new WeekRange();
            $newWeekRange->from_date = $monday->format('Y-m-d');
            $newWeekRange->to_date = $sunday->format('Y-m-d');

            // Calculate the week number in the current year
            $weekNumber = Carbon::now()->weekOfYear;
            $newWeekRange->week_number = $weekNumber;

            // Step 4: Determine which month has the majority of days in the week
            $dayCount = [];

            // Iterate through each day of the week and count the month occurrences
            for ($date = $monday; $date->lte($sunday); $date->addDay()) {
                $monthNumber = $date->month; // Numerical month (1-12)
                $monthName = $date->format('F'); // Full month name (January, February, etc.)

                // Increment the count for this month
                if (!isset($dayCount[$monthNumber])) {
                    $dayCount[$monthNumber] = [
                        'count' => 0,
                        'month' => $monthName,
                        'year' => $date->year,
                    ];
                }
                $dayCount[$monthNumber]['count']++;
            }

            // Determine which month has the most days in this week
            $dominantMonth = collect($dayCount)->sortByDesc('count')->first();
            $month = date_parse($dominantMonth['month']);;
            // Set the in_month_num, in_month, and in_year columns
            $newWeekRange->in_month_num = $month['month'];
            $newWeekRange->in_month = $dominantMonth['month'];
            $newWeekRange->in_year = $dominantMonth['year'];

            // Save the new week range
            $newWeekRange->save();

//            return "New week range added: " . $monday->format('Y-m-d') . " to " . $sunday->format('Y-m-d') . ", Week Number: " . $weekNumber . ", Dominant Month: " . $dominantMonth['month'];
            return $newWeekRange;
        } else {
//            return "Week range already exists: " . $existingWeek->from_date . " to " . $existingWeek->to_date;
            return $existingWeek;
        }
    }

    public function store(Request $request)
    {
        if ($request->filled('for_date')) {
            $time_title = 'for_date';
            $time_value = $for_date = $request->for_date;
        } else {
            $for_date = WeekRange::find($request->week_range_id)->to_date;
            $time_title = 'week_range_id';
            $time_value = $request->week_range_id;
        }

        $user = auth()->user();
        $staff_ara_id = isset($user->staff_member) ? $user->staff_member->staff_ara_id : null;
        $data = [];

        foreach ($request->form_field as $key => $value) {
//            echo $key . ' => ' . $value . '<br>';
            $data[] = [
              'week_range_id' => $request->week_range_id,
                'score_card_form_field_id' => $key,
                'name' => ScoreCardFormField::find($key)->label,
                'business_area_id' => $request->business_area_id,
                'for_date' => $for_date,
                'presenter_staff_ara_id' => $staff_ara_id,
                'time_title' => $time_title,
                'data_value' => $value
            ];
        }

        DB::table('data_points')
            ->insert($data);

        return back()->withFlashSuccess('Records added');
    }

    public function update(Request $request, DataPoint $dataPoint)
    {

    }

    public function fourQuadrants()
    {

    }

    public function oneQuadrant(Request $request)
    {
        $accessible_business_areas = $this->accessibleBusinessSectors();
        $week_checker = $this->currentWeekChecker();
        $weeks = WeekRange::orderBy('id', 'DESC')->get();
        $business_area_id = $request->filled('business_area_id') ? $request->business_area_id :
            (count($accessible_business_areas) ? $accessible_business_areas[1]->id : 3);

        $business_area = BusinessArea::find($business_area_id);

        $week_range_id = $request->filled('week_range_id') ? $request->week_range_id : $week_checker->id;
        $presentation_data = $this->getDataForKeyPeriods($business_area_id, $week_range_id);

        $form_fields = DB::table('score_card_form_fields')->select('id', 'label', 'unit', 'form_type')->where('business_area_id', $business_area_id)->get();
        $form_fields = $form_fields->toArray();
        $week_in_focus = $weeks->find($week_range_id);
//        dd($form_fields[8]->label);
        return view('frontend.business_goals.quadrants.index', compact('form_fields',
        'presentation_data', 'weeks', 'business_area_id', 'accessible_business_areas', 'week_range_id', 'week_in_focus', 'business_area'));
    }

    public function getWeeksData($business_area_id, $week_id)
    {
        // Step 1: Get all data points for the specified business area and week
        $data_points = DataPoint::where('week_range_id', $week_id)
            ->where('business_area_id', $business_area_id)
            ->get();

        // Step 2: Group by 'score_card_form_field_id'
        $grouped_data = $data_points->groupBy('score_card_form_field_id');

        // Step 3: Compute the average 'data_value' for each group
        $week_data = $grouped_data->map(function ($group, $field_id) {
            // Ensure 'data_value' is numerical for averaging
            $values = $group->pluck('data_value')->map(function ($value) {
                return is_numeric($value) ? (float) $value : 0; // Convert to float or set to 0 if not numeric
            });

            $average = $values->avg(); // Compute average

            // Create a simplified array to represent the distilled data
            return [
                'score_card_form_field_id' => $field_id,
                'name' => $group->first()->name, // Assuming all records have the same name for a specific field
                'average_data_value' => $average, // Average data value
                'is_computed' => $group->first()->is_computed, // Should be the same for all
                'presenter_staff_ara_id' => $group->first()->presenter_staff_ara_id,
                'for_date' => $group->first()->for_date,
                'time_title' => $group->first()->time_title,
            ];
        })->values(); // Reset indices for the returned collection

        return $week_data;
    }

    public function getDataForKeyPeriods($business_area_id, $week_range_id)
    {
        // Step 1: Retrieve the current week's range from the `week_ranges` table
        $currentWeekRange = WeekRange::find($week_range_id);

        if (!$currentWeekRange) {
            return response()->json([
                'error' => 'Week range not found.'
            ], 404);
        }

        $currentWeekStart = Carbon::parse($currentWeekRange->from_date);
        $currentWeekEnd = Carbon::parse($currentWeekRange->to_date);

        // Step 2: Calculate start and end dates for other periods
        $previousWeekStart = $currentWeekStart->copy()->subWeek();
        $previousWeekEnd = $currentWeekEnd->copy()->subWeek();

        $currentMonthStart = $currentWeekStart->copy()->startOfMonth();
        $currentMonthEnd = $currentWeekEnd->copy()->endOfMonth();

        $previousMonthStart = $currentMonthStart->copy()->subMonth();
        $previousMonthEnd = $currentMonthEnd->copy()->subMonth();

        $currentYearStart = $currentWeekStart->copy()->startOfYear();
        $currentYearEnd = $currentWeekEnd->copy()->endOfYear();

        $previousYearStart = $currentYearStart->copy()->subYear();
        $previousYearEnd = $currentYearEnd->copy()->subYear();

        // Step 3: Retrieve data for each period and calculate averages or totals
        $keyPeriodsData = [
            'current_week' => $this->calculateAveragesAndTotals($business_area_id, $currentWeekStart, $currentWeekEnd),
            'previous_week' => $this->calculateAveragesAndTotals($business_area_id, $previousWeekStart, $previousWeekEnd),
            'current_month' => $this->calculateAveragesAndTotals($business_area_id, $currentMonthStart, $currentMonthEnd),
            'previous_month' => $this->calculateAveragesAndTotals($business_area_id, $previousMonthStart, $previousMonthEnd),
            'current_year' => $this->calculateAveragesAndTotals($business_area_id, $currentYearStart, $currentYearEnd),
            'previous_year' => $this->calculateAveragesAndTotals($business_area_id, $previousYearStart, $previousYearEnd),
            'titles' => [
                'last week' => $previousWeekStart->format('l, F j, Y').' - '.$previousWeekEnd->format('l, F j, Y'),
                'this month' => $currentMonthStart->format('F Y'),
                'last month' => $previousMonthStart->format('F Y'),
                'this year' => $currentYearStart->year,
                'last year' => $previousYearStart->year,
            ]
        ];

        return $keyPeriodsData;
    }

    private function calculateAveragesAndTotals($business_area_id, Carbon $startDate, Carbon $endDate)
    {
        // Get all data points for the business area and date range
        $dataPoints = DataPoint::whereBetween('for_date', [$startDate, $endDate])
            ->where('business_area_id', $business_area_id)
            ->get();
        // Group by score_card_form_field_id
        $groupedDataPoints = $dataPoints->groupBy('score_card_form_field_id');

        $result = [];

        foreach ($groupedDataPoints as $fieldId => $group) {
            // Get the form field to determine the unit
            $formField = ScoreCardFormField::find($fieldId);

            // If formField is not found, continue to the next group
            if (!$formField) {
                continue;
            }

            if($formField->form_type == 'text'){
                $result[$fieldId] = [
                    'field' => $formField->label,
                    'total' => $group->first()->data_value,
                ];
                continue;
            }

            $values = $group->pluck('data_value')->map(function ($value) {
                // Convert to numeric
                return is_numeric($value) ? (float) $value : 0;
            });

            if ($formField->unit === 'percentage') {
                // Calculate the average for percentage-based fields
                $average = $values->avg();
                $result[$fieldId] = [
                    'field' => $formField->label,
                    'average' => $average,
                ];
            } else {
                // Calculate the total for non-percentage-based fields
                $total = $values->sum();
                $result[$fieldId] = [
                    'field' => $formField->label,
                    'total' => $total,
                ];
            }
        }

        return $result;
    }


    public function getDataForKeyPeriods01($business_area_id, $week_range_id)
    {
        // Step 1: Retrieve the current week's range from the `week_ranges` table
        $currentWeekRange = WeekRange::find($week_range_id);

        if (!$currentWeekRange) {
            return response()->json([
                'error' => 'Week range not found.'
            ], 404);
        }

        $currentWeekStart = Carbon::parse($currentWeekRange->from_date);
        $currentWeekEnd = Carbon::parse($currentWeekRange->to_date);

        // Step 2: Calculate the start and end dates for the previous week
        $previousWeekStart = $currentWeekStart->copy()->subWeek();
        $previousWeekEnd = $currentWeekEnd->copy()->subWeek();

        // Step 3: Calculate the start and end dates for the current and previous months
        $currentMonthStart = $currentWeekStart->copy()->startOfMonth();
        $currentMonthEnd = $currentWeekEnd->copy()->endOfMonth();

        $previousMonthStart = $currentMonthStart->copy()->subMonth();
        $previousMonthEnd = $currentMonthEnd->copy()->subMonth();

        // Step 4: Calculate the start and end dates for the current and previous years
        $currentYearStart = $currentWeekStart->copy()->startOfYear();
        $currentYearEnd = $currentWeekEnd->copy()->endOfYear();

        $previousYearStart = $currentYearStart->copy()->subYear();
        $previousYearEnd = $currentYearEnd->copy()->subYear();

        // Step 5: Retrieve data for each period
        $keyPeriodsData = [
            'current_week' => DataPoint::whereBetween('for_date', [$currentWeekStart, $currentWeekEnd])
                ->where('business_area_id', $business_area_id)
                ->get(),
            'previous_week' => DataPoint::whereBetween('for_date', [$previousWeekStart, $previousWeekEnd])
                ->where('business_area_id', $business_area_id)
                ->get(),
            'current_month' => DataPoint::whereBetween('for_date', [$currentMonthStart, $currentMonthEnd])
                ->where('business_area_id', $business_area_id)
                ->get(),
            'previous_month' => DataPoint::whereBetween('for_date', [$previousMonthStart, $previousMonthEnd])
                ->where('business_area_id', $business_area_id)
                ->get(),
            'current_year' => DataPoint::whereBetween('for_date', [$currentYearStart, $currentYearEnd])
                ->where('business_area_id', $business_area_id)
                ->get(),
            'previous_year' => DataPoint::whereBetween('for_date', [$previousYearStart, $previousYearEnd])
                ->where('business_area_id', $business_area_id)
                ->get(),
        ];

        return $keyPeriodsData;
    }

}
