<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\BscPeriodicDataPoint;
use App\Models\BscPeriodicTarget;
use App\Models\BusinessArea;
use App\Models\DataPoint;
use App\Models\ScoreCardFormField;
use App\Models\WeekRange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\WeekRangeService;
use Illuminate\Support\Facades\Log;

class BusinessGoalsController extends Controller
{
    public function index()
    {
        $accessible_business_areas = $this->accessibleBusinessSectors();
        $week_checker = $this->lastWeekGetter();
        $weeks = WeekRange::orderBy('id', 'DESC')->get();
        $week_range_id = $week_checker->id;
        return view('frontend.business_goals.quadrants.index', compact('accessible_business_areas', 'week_range_id', 'weeks'));
    }

    public function create(Request $request)
    {
        $accessible_business_areas = $this->accessibleBusinessSectors();
        $week_checker = $this->lastWeekGetter();
        $weeks = WeekRange::orderBy('to_date', 'DESC')->get();
        $business_area_id = $request->filled('business_area_id') ? $request->business_area_id :
            (count($accessible_business_areas) ? $accessible_business_areas[1]->id : 3);

        $business_area = BusinessArea::find($business_area_id);
        $form_fields = ScoreCardFormField::where('business_area_id', $business_area_id)->get();
        $data_points = null;
        if ($request->filled('week_range_id')) {
            $data_points = DataPoint::where('week_range_id', $request->week_range_id)
                ->where('business_area_id', $business_area_id)
                ->where('time_title', 'week_range_id')
                ->get();
            $week_range_id = $request->week_range_id;
        } else {
            $week_range_id = $week_checker->id;
            $data_points = DataPoint::where('week_range_id', $week_range_id)
                ->where('business_area_id', $business_area_id)
                ->where('time_title', 'week_range_id')
                ->get();
        }

        $selected_week = $weeks->where('id', $week_range_id)->first();

        $recent_filled_week = $most_recent_data = null;
        if (is_null($data_points) || !$data_points->count()) {
            $recent_filled_week = DataPoint::where('week_range_id', '<', $week_range_id)
                ->where('time_title', 'week_range_id')
                ->where('business_area_id', $business_area_id)
                ->latest()
                ->first();

            if($recent_filled_week) {
                if ($request->filled('prefill') && $request->prefill == 1) {
                    // Get most recent data

                    $older_data_points = DataPoint::where('week_range_id', $recent_filled_week->week_range_id)
                        ->where('business_area_id', $business_area_id)
                        ->where('time_title', 'week_range_id')
                        ->get();

                    $new_data_points = $older_data_points->map(function ($dataPoint) use ($selected_week) {
                        return [
                            // Copy all attributes except id and timestamps
                            'week_range_id' => $selected_week->id,
                            'business_area_id' => $dataPoint->business_area_id,
                            'score_card_form_field_id' => $dataPoint->score_card_form_field_id,
                            'name' => $dataPoint->name,
                            'data_value' => $dataPoint->data_value,
                            'is_computed' => $dataPoint->is_computed,
                            'presenter_staff_ara_id' => $dataPoint->presenter_staff_ara_id,
                            'for_date' => $selected_week->to_date,
                            'time_title' => $dataPoint->time_title,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    });

                    DataPoint::insert($new_data_points->toArray());
                    $data_points = DataPoint::where('week_range_id', $selected_week->id)
                        ->where('business_area_id', $business_area_id)
                        ->where('time_title', 'week_range_id')
                        ->get();
                }
            }
        }

        return view('frontend.business_goals.add-report', compact(
            'business_area',
            'form_fields',
            'weeks',
            'accessible_business_areas',
            'data_points',
            'selected_week',
            'most_recent_data',
            'recent_filled_week',
        ))->with([
            'week_range_id' => $week_range_id
        ]);
    }

    public function createForSingleDay(Request $request)
    {
        $accessible_business_areas = $this->accessibleBusinessSectors();
//        $week_checker = $this->lastWeekGetter();
        $weeks = WeekRange::orderBy('to_date', 'DESC')->get();
        $business_area_id = $request->filled('business_area_id') ? $request->business_area_id :
            (count($accessible_business_areas) ? $accessible_business_areas[1]->id : 3);

        $business_area = BusinessArea::find($business_area_id);
        $form_fields = ScoreCardFormField::where('business_area_id', $business_area_id)->get();
        $data_points = $for_date = null;
        if ($request->filled('for_date')) {
            $data_points = DataPoint::where('for_date', $request->for_date)
                ->where('time_title', 'for_date')
                ->where('business_area_id', $business_area_id)
                ->get();
            $for_date = $request->for_date;
        }

        $most_recent_data = $most_recent_filled_day = null;

        if ((is_null($data_points) || !$data_points->count()) && isset($for_date)) {
            $most_recent_filled_day = DataPoint::where('for_date', '<', $for_date)
                ->where('time_title', 'for_date')
                ->where('business_area_id', $business_area_id)
                ->latest()
                ->first();

            if ($most_recent_filled_day) {
                if($request->filled('prefill') && $request->prefill == 1) {
                    $older_data_points = DataPoint::where('for_date', $most_recent_filled_day->for_date)
                        ->where('business_area_id', $business_area_id)
                        ->where('time_title', 'for_date')
                        ->get();

                    $week_range = WeekRange::where('from_date', '<=', $for_date)->where('to_date', '>=', $for_date)->first();

                    $new_data_points = $older_data_points->map(function ($dataPoint) use ($for_date, $week_range) {
                        return [
                            // Copy all attributes except id and timestamps
                            'week_range_id' => $week_range->id,
                            'business_area_id' => $dataPoint->business_area_id,
                            'score_card_form_field_id' => $dataPoint->score_card_form_field_id,
                            'name' => $dataPoint->name,
                            'data_value' => $dataPoint->data_value,
                            'is_computed' => $dataPoint->is_computed,
                            'presenter_staff_ara_id' => $dataPoint->presenter_staff_ara_id,
                            'for_date' => $for_date,
                            'time_title' => $dataPoint->time_title,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    });

                    DataPoint::insert($new_data_points->toArray());
                    $data_points = DataPoint::where('for_date', $for_date)
                        ->where('business_area_id', $business_area_id)
                        ->where('time_title', 'for_date')
                        ->get();
                }
            }
        }


        return view('frontend.business_goals.add-single-day-report', compact(
            'business_area',
            'form_fields',
            'weeks',
            'accessible_business_areas',
            'data_points',
            'for_date',
            'most_recent_data',
            'most_recent_filled_day',
        ));
    }

    public function accessibleBusinessSectors($params = [])
    {
        if(request()->getHost() == 'localhost' && array_key_exists('pdf', $params)){
            $accessible_business_areas = [];
            $count = 1;
            foreach (BusinessArea::all() as $businessArea){
                $accessible_business_areas[$count] = $businessArea;
                $count++;
            }
            return $accessible_business_areas;
        }

        $user = auth()->user();
        return $user->accessibleBusinessAreas();
    }

    private function lastWeekGetter()
    {
        $current_week = $this->currentWeekChecker();
        $last_week = WeekRange::where('id', '<', $current_week->id)->orderBy('id', 'DESC')->first();
        return $last_week;
    }

    public function currentWeekCheckerR()
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

            return $newWeekRange;
        } else {
//            return "Week range already exists: " . $existingWeek->from_date . " to " . $existingWeek->to_date;
            return $existingWeek;
        }
    }

    public function currentWeekChecker()
    {
        return WeekRangeService::currentWeekChecker();
    }

    public function store(Request $request)
    {
        if ($request->filled('for_date')) {
            $time_title = 'for_date';
            $time_value = $for_date = $request->for_date;
            // Find which week this falls in to
            $week_range = WeekRange::where('from_date', '<=', $request->for_date)->where('to_date', '>=', $request->for_date)->first();
            $week_range_id = $week_range->id;
        } else {
            $for_date = WeekRange::find($request->week_range_id)->to_date;
            $time_title = 'week_range_id';
            $time_value = $week_range_id = $request->week_range_id;
        }

        $user = auth()->user();
        $staff_ara_id = isset($user->staff_member) ? $user->staff_member->staff_ara_id : null;
        $data = [];

        $score_card_form_fields = ScoreCardFormField::where('business_area_id', $request->business_area_id)->get();

        $existingDataPoints = DataPoint::where('business_area_id', $request->business_area_id)
            ->where('time_title', $time_title)
            ->where('for_date', $for_date)
            ->get();

        foreach ($request->form_field as $key => $value) {
            // Prepare the data array
            $data[] = [
                'week_range_id' => $week_range_id,
                'score_card_form_field_id' => $key,
                'name' => $score_card_form_fields->where('id', $key)->first()->label,
                'business_area_id' => $request->business_area_id,
                'for_date' => $for_date,
                'presenter_staff_ara_id' => $staff_ara_id,
                'time_title' => $time_title,
                'data_value' => $value
            ];
        }

        // ISP Metrics
        if ($request->business_area_id == 9 && isset($request->isp_metrics) && is_array($request->isp_metrics) && count($request->isp_metrics)) {
            foreach ($request->isp_metrics as $key => $value) {
                $data[] = [
                    'week_range_id' => $week_range_id,
                    'score_card_form_field_id' => $key,
                    'name' => $score_card_form_fields->where('id', $key)->first()->label,
                    'business_area_id' => $request->business_area_id,
                    'for_date' => $for_date,
                    'presenter_staff_ara_id' => $staff_ara_id,
                    'time_title' => $time_title,
                    'data_value' => json_encode($value)
                ];
            }
        }

        if ($request->business_area_id == 3 && isset($request->delay_codes) && is_array($request->delay_codes) && count($request->delay_codes)) {
//            For flight operations delays chart data
            $delays_form_field = ScoreCardFormField::where('business_area_id', $request->business_area_id)
                ->where('label', 'Delays JSON')
                ->first();

            $delays = [];
            foreach ($request->delay_codes as $key => $value) {
                $delays[] = [$value => $request->delay_amounts[$key]];
            }

            $data[] = [
                'week_range_id' => $week_range_id,
                'score_card_form_field_id' => $delays_form_field->id,
                'name' => $delays_form_field->label,
                'business_area_id' => $request->business_area_id,
                'for_date' => $for_date,
                'presenter_staff_ara_id' => $staff_ara_id,
                'time_title' => $time_title,
                'data_value' => json_encode($delays)
            ];
        }

        if ($request->business_area_id == 10) {
            if ($request->filled('observation_completeds')) {
                $observation_completed_form_field = ScoreCardFormField::where('business_area_id', $request->business_area_id)
                    ->where('label', 'Observations Completed JSON')
                    ->first();

                $observations_completed = [];
                foreach ($request->observation_completeds as $key => $value) {
                    $observations_completed[] = [$value => $request->observation_completed_amounts[$key]];
                }

                $data[] = [
                    'week_range_id' => $week_range_id,
                    'score_card_form_field_id' => $observation_completed_form_field->id,
                    'name' => $observation_completed_form_field->label,
                    'business_area_id' => $request->business_area_id,
                    'for_date' => $for_date,
                    'presenter_staff_ara_id' => $staff_ara_id,
                    'time_title' => $time_title,
                    'data_value' => json_encode($observations_completed)
                ];
            }


            if ($request->filled('observation_ongoings')) {
                $observation_ongoing_form_field = ScoreCardFormField::where('business_area_id', $request->business_area_id)
                    ->where('label', 'Observations Ongoing JSON')
                    ->first();

                $observations_ongoing = [];
                foreach ($request->observation_ongoings as $key => $value) {
                    $observations_ongoing[] = [$value => $request->observation_ongoing_amounts[$key]];
                }

                $data[] = [
                    'week_range_id' => $week_range_id,
                    'score_card_form_field_id' => $observation_ongoing_form_field->id,
                    'name' => $observation_ongoing_form_field->label,
                    'business_area_id' => $request->business_area_id,
                    'for_date' => $for_date,
                    'presenter_staff_ara_id' => $staff_ara_id,
                    'time_title' => $time_title,
                    'data_value' => json_encode($observations_ongoing)
                ];
            }
        }


        foreach ($data as $data_point) {
            // Check if there's a matching record in data_points table
            $existingDataPoint = $existingDataPoints
                ->where('score_card_form_field_id', $data_point['score_card_form_field_id'])
                ->first();

            if ($existingDataPoint) {
                // Compare existing data point with the new data point
                $dataChanged = false;
                if ($existingDataPoint->data_value != $data_point['data_value']) {
                    $dataChanged = true;
                    // Store history if data has changed
                    if (isset($existingDataPoint->data_value) && !is_null($existingDataPoint->data_value)) {
                        DB::table('data_point_histories')->insert([
                            'data_point_id' => $existingDataPoint->id,
                            'week_range_id' => $existingDataPoint->week_range_id,
                            'name' => $existingDataPoint->name,
                            'data_value' => $existingDataPoint->data_value,
                            'is_computed' => 'no',
                            'presenter_staff_ara_id' => $existingDataPoint->presenter_staff_ara_id ?? '0000',
                            'for_date' => $existingDataPoint->for_date,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Update existing record
                    DB::table('data_points')
                        ->where('id', $existingDataPoint->id)
                        ->update(array_merge($data_point, ['created_at' => now(), 'updated_at' => now()]));
                }

            } else {
                // Insert new record
                DB::table('data_points')
                    ->insert(array_merge($data_point, ['created_at' => now(), 'updated_at' => now()]));
            }

            if(in_array($data_point['score_card_form_field_id'], $this->monitored_field_ids) && $time_title == 'week_range_id')
                $periodic = $this->processPeriodicalData($data_point['score_card_form_field_id'], $week_range_id);

            if($request->business_area_id == 4)
                $gross_sales = $this->storePeriodicTotalSales($week_range_id);
        }


//        $view = view('frontend.business_goals.quadrants._'.$request->business_area_id);
//        echo $view->render();

        return back()->withFlashSuccess('Records submitted successfully');

    }

    protected $monitored_field_ids = [62,
        50,
        66,
        58,
        55,
        2,
        28,
        4,
    ];

//    protected $fields_with_targets = [50, 52, 57, 58];
    protected $fields_with_targets = [
        2,
        28,
        4
    ];

    public function processPeriodicalData($field_id, $week_range_id)
    {
        // for storing monthly, quarterly and yearly data for a field
        $field = ScoreCardFormField::find($field_id);
        $week_range = WeekRange::find($week_range_id);
        $quarter_number = getQuarterNum($week_range->in_month_num);
        $year = $week_range->in_year;

        $data_points = DataPoint::where('score_card_form_field_id', $field_id)
            ->where('time_title', 'week_range_id')
            ->whereBetween('for_date', [$year.'-01-01', $year.'-12-31'])
            ->get();

        if(!$data_points)
            return null;

        $sum_year = $sum_quarter = $sum_month = 0;
        $count_year = $count_month = $count_quarter = 0;

        foreach($data_points as $data_point){
            if(!$data_point->data_value)
                continue;

            $sum_year += $data_point->data_value;
            $for_date = Carbon::parse($data_point->for_date);
            $count_year++;

            if($for_date->quarter == $quarter_number){
                $sum_quarter += $data_point->data_value;
                $count_quarter++;
            }

            if($data_point->weekRange->in_month_num == $week_range->in_month_num){
                $sum_month += $data_point->data_value;
                $count_month++;
            }
        }

        if($field->unit == '%' || $field_id == 58){
            $data_value['year'] = $sum_year/$count_year;
            $data_value['quarter'] = $sum_quarter/$count_quarter;
            $data_value['month'] = $sum_month/$count_month;
        }else{
            $data_value['year'] = $sum_year;
            $data_value['quarter'] = $sum_quarter;
            $data_value['month'] = $sum_month;
        }

        $quarter_dates = [
            1 => [$year.'-01-01', $year.'-03-31'],
            2 => [$year.'-04-01', $year.'-06-30'],
            3 => [$year.'-07-01', $year.'-09-30'],
            4 => [$year.'-10-01', $year.'-12-31'],
        ];

        // Year
        DB::table('bsc_periodic_data_points')
            ->updateOrInsert([
                'from_date' => $year.'-01-01',
                'to_date' => $year.'-12-31',
                'score_card_form_field_id' => $field_id,
                'business_area_id' => $field->business_area_id,
                'for_year' => $year,
                'time_title' => 'year',
            ], [
                'data_value' => $data_value['year']
            ]);


        // Quarter
        DB::table('bsc_periodic_data_points')
            ->updateOrInsert([
                'from_date' => $quarter_dates[$quarter_number][0],
                'to_date' => $quarter_dates[$quarter_number][1],
                'score_card_form_field_id' => $field_id,
                'business_area_id' => $field->business_area_id,
                'for_year' => $year,
                'for_quarter' => $quarter_number,
                'time_title' => 'quarter',
            ], [
                'data_value' => $data_value['quarter']
            ]);

        // Month
        $first_day_of_month = $year.'-'.(strlen($week_range->in_month_num) == 1 ? '0'.$week_range->in_month_num : $week_range->in_month_num).'-01';

        $end_of_month = Carbon::parse($first_day_of_month)->endOfMonth()->toDateString();
        DB::table('bsc_periodic_data_points')
            ->updateOrInsert([
                'from_date' => $first_day_of_month,
                'to_date' => $end_of_month,
                'score_card_form_field_id' => $field_id,
                'business_area_id' => $field->business_area_id,
                'for_year' => $year,
                'for_quarter' => $quarter_number,
                'for_month' => $week_range->in_month,
                'for_month_number' => $week_range->in_month_num,
                'time_title' => 'month',
            ], [
                'data_value' => $data_value['month']
            ]);

        if(in_array($field_id, $this->fields_with_targets)){

            $targets = BscPeriodicTarget::where('score_card_form_field_id', $field_id)->get();

            foreach($targets as $target){
                if($target->time_title == 'simple target'){
                    $time_titles = ['year', 'quarter', 'month'];
                    $date_ranges = [
                        'year' => [$year.'-01-01', $year.'-12-31'],
                        'quarter' => [$quarter_dates[$quarter_number][0], $quarter_dates[$quarter_number][1]],
                        'month' => [$first_day_of_month, $end_of_month]
                    ];
                }else{
                    $time_titles = [$target->time_title];
                    $date_ranges = [
                        'year' => [$target->from_date, $target->to_date],
                        'quarter' => [$target->from_date, $target->to_date],
                        'month' => [$target->from_date, $target->to_date]
                    ];
                }

                foreach ($time_titles as $time_title) {
                    $period_data[$time_title] = BscPeriodicDataPoint::where('score_card_form_field_id', $field_id)
                        ->where('time_title', $time_title)
                        ->where('from_date', $date_ranges[$time_title][0])
                        ->where('to_date', $date_ranges[$time_title][1])
                        ->first();

                    if ($period_data[$time_title]) {
                        $variance_res = calculateVarianceValueAndUI($period_data[$time_title]->data_value, $target->target_value);
                        $period_data[$time_title]->target_value = $target->target_value;
                        $period_data[$time_title]->target_variance_ui = $variance_res[0];
                        $period_data[$time_title]->target_variance_percentage = $variance_res[1];
                        $period_data[$time_title]->save();
                    } else {
                        Log::debug('No period data', [
                            'Field ID' => $field_id,
                            'time_title' => $time_title,
                            'target' => $target->toArray()
                        ]);
                    }
                }
            }
        }

        return true;
    }

    private function storePeriodicTotalSales($week_range_id)
    {
        $week_range = WeekRange::find($week_range_id);
        $quarter_number = getQuarterNum($week_range->in_month_num);
        $year = $week_range->in_year;

        $data_points = DataPoint::where('business_area_id', 4)
            ->where('time_title', 'week_range_id')
            ->where('score_card_form_field_id', '!=', 47)
            ->whereBetween('for_date', [$year.'-01-01', $year.'-12-31'])
            ->get();

        if(!$data_points)
            return null;

        $sum_year = $sum_quarter = $sum_month = 0;

        foreach($data_points as $data_point){
            if(!$data_point->data_value)
                continue;

            $sum_year += $data_point->data_value;
            $for_date = Carbon::parse($data_point->for_date);

            if($for_date->quarter == $quarter_number){
                $sum_quarter += $data_point->data_value;
            }

            if($for_date->month == $week_range->in_month_num){
                $sum_month += $data_point->data_value;
            }
        }


        $data_value['year'] = $sum_year;
        $data_value['quarter'] = $sum_quarter;
        $data_value['month'] = $sum_month;

        $quarter_dates = [
            1 => [$year.'-01-01', $year.'-03-31'],
            2 => [$year.'-04-01', $year.'-06-30'],
            3 => [$year.'-07-01', $year.'-09-30'],
            4 => [$year.'-10-01', $year.'-12-31'],
        ];

        // Year
        DB::table('bsc_periodic_data_points')
            ->updateOrInsert([
                'from_date' => $year.'-01-01',
                'to_date' => $year.'-12-31',
                'business_area_id' => 4,
                'for_year' => $year,
                'time_title' => 'year',
                'data_title' => 'Total Sales',
            ], [
                'data_value' => $data_value['year']
            ]);


        // Quarter
        DB::table('bsc_periodic_data_points')
            ->updateOrInsert([
                'from_date' => $quarter_dates[$quarter_number][0],
                'to_date' => $quarter_dates[$quarter_number][1],
                'business_area_id' => 4,
                'for_year' => $year,
                'for_quarter' => $quarter_number,
                'time_title' => 'quarter',
                'data_title' => 'Total Sales',
            ], [
                'data_value' => $data_value['quarter']
            ]);

        // Month
        $first_day_of_month = $year.'-'.(strlen($week_range->in_month_num) == 1 ? '0'.$week_range->in_month_num : $week_range->in_month_num).'-01';

        $end_of_month = Carbon::parse($first_day_of_month)->endOfMonth()->toDateString();
        DB::table('bsc_periodic_data_points')
            ->updateOrInsert([
                'from_date' => $first_day_of_month,
                'to_date' => $end_of_month,
                'business_area_id' => 4,
                'for_year' => $year,
                'for_quarter' => $quarter_number,
                'for_month' => $week_range->in_month,
                'for_month_number' => $week_range->in_month_num,
                'time_title' => 'month',
                'data_title' => 'Total Sales',
            ], [
                'data_value' => $data_value['quarter']
            ]);
        return true;

    }

    public function singleBusinessAreaTables(Request $request)
    {
        return view('frontend.business_goals.business_areas_groups.ground-ops');
    }

    public function multiBusinessAreaTables(Request $request)
    {
//        if(!(auth()->user()->can('see all business score cards')))
//            return redirect()->route('frontend.business_goals.group.business.areas');

        if($request->has('pdf')){
            if(!Auth::check()) {
                $user = User::findOrFail(1);
                Auth::login($user);
            }
            $accessible_business_areas_raw = $this->accessibleBusinessSectors(['pdf' => 'pdf']);
        }else {
            $accessible_business_areas_raw = $this->accessibleBusinessSectors();
        }

//        $all_business_areas = BusinessArea::where('id', '!=', 9)->get();
        $all_business_areas = BusinessArea::all();
        $week_checker = $this->lastWeekGetter();
        $week_range_id = $request->filled('week_range_id') ? $request->week_range_id : $week_checker->id;
        $weeks = WeekRange::orderBy('id', 'DESC')->get();
        $week_in_focus = $weeks->find($week_range_id);
        $multi_presentation_data = $all_business_area_array = [];

        foreach ($accessible_business_areas_raw as $business_area) {
            $accessible_business_areas[$business_area->id] = $business_area;
        }

        foreach ($all_business_areas as $business_area) {
            $business_area_id = $business_area->id;
            $all_business_area_array[$business_area_id] = $business_area;
            $multi_presentation_data[$business_area_id] = $this->getDataForKeyPeriods($business_area_id, $week_range_id);

            $multi_form_fields[$business_area_id] = DB::table('score_card_form_fields')->select('id', 'label', 'unit', 'form_type','placeholder')->where('business_area_id', $business_area_id)->get();
            $multi_form_fields_collection[$business_area_id] = $multi_form_fields[$business_area_id];
            $multi_form_fields[$business_area_id] = $multi_form_fields[$business_area_id]->toArray();
        }

        if((auth()->user() && auth()->user()->can('see all business score cards')) || $request->has('pdf')) {
            $business_areas_custom_order = [1, 8, 3, 4, 5, 7, 2, 6, 10, 9, 11];
        }else{
            $business_areas_custom_order = [6, 4, 5, 7, 1, 8, 3, 2, 10, 9, 11];
        }

        $currentWeekStart = Carbon::parse($week_in_focus->from_date);
        $previousWeekStart = $currentWeekStart->copy()->subWeek();
        $previousWeek = $weeks->where('from_date', $previousWeekStart)->first();
        $bsc_stats = [];
        $stats_indices = [
            68, // Available Aircraft
            62, // No. of Flights operated
            66, // Total PAX
            'Total Sales',
            56, // Average Fare
            55, // Revenue
            61, // OTP
            'Load Factor',
            'Completion Factor'
        ];

        foreach ($stats_indices as $index) {
            $bsc_stats[$index] = [];
        }

        $view_file = !$request->has('pdf') ? 'frontend.business_goals.ceo_combined_view.index' : 'frontend.business_goals.ceo_combined_view.toPDF';

        return view($view_file, compact('multi_form_fields', 'multi_form_fields_collection',
            'multi_presentation_data', 'weeks', 'accessible_business_areas', 'week_range_id', 'week_in_focus', 'business_areas_custom_order', 'previousWeek', 'bsc_stats', 'stats_indices', 'all_business_area_array'))->with([
                'pdf' => true
        ]);
    }

    public function groupBusinessAreaTables(Request $request)
    {
        $accessible_business_areas_raw = $this->accessibleBusinessSectors();
        if(!count($accessible_business_areas_raw))
            return redirect()->home()->withErrors('Unauthorized access');
        $week_checker = $this->lastWeekGetter();
        $week_range_id = $request->filled('week_range_id') ? $request->week_range_id : $week_checker->id;
        $weeks = WeekRange::orderBy('id', 'DESC')->get();
        $week_in_focus = $weeks->find($week_range_id);
        $multi_presentation_data = $accessible_business_areas = $business_areas_custom_order = [];
        foreach ($accessible_business_areas_raw as $business_area) {
            $accessible_business_areas[$business_area->id] = $business_area;
            $business_area_id = $business_areas_custom_order[] = $business_area->id;
            $multi_presentation_data[$business_area_id] = $this->getDataForKeyPeriods($business_area_id, $week_range_id);

            $multi_form_fields[$business_area_id] = DB::table('score_card_form_fields')->select('id', 'label', 'unit', 'form_type')->where('business_area_id', $business_area_id)->get();
            $multi_form_fields_collection[$business_area_id] = $multi_form_fields[$business_area_id];
            $multi_form_fields[$business_area_id] = $multi_form_fields[$business_area_id]->toArray();
        }

        $currentWeekStart = Carbon::parse($week_in_focus->from_date);
        $previousWeekStart = $currentWeekStart->copy()->subWeek();
        $previousWeek = $weeks->where('from_date', $previousWeekStart)->first();

        return view('frontend.business_goals.business_areas_groups.group-presentation', compact('multi_form_fields', 'multi_form_fields_collection',
            'multi_presentation_data', 'weeks', 'accessible_business_areas', 'week_range_id', 'week_in_focus', 'business_areas_custom_order', 'previousWeek'));
    }

    public function oneQuadrant(Request $request)
    {
        $accessible_business_areas = $this->accessibleBusinessSectors();
        $week_checker = $this->lastWeekGetter();
        $weeks = WeekRange::orderBy('id', 'DESC')->get();
        $business_area_id = $request->filled('business_area_id') ? $request->business_area_id :
            (count($accessible_business_areas) ? $accessible_business_areas[1]->id : 3);

        $business_area = BusinessArea::find($business_area_id);

        $week_range_id = $request->filled('week_range_id') ? $request->week_range_id : $week_checker->id;
        $presentation_data = $this->getDataForKeyPeriods($business_area_id, $week_range_id);

        if($business_area_id == 9){
            $form_fields = DB::table('score_card_form_fields')->select('id', 'label', 'unit', 'form_type', 'placeholder', 'target_value')->where('business_area_id', $business_area_id)
                ->whereNull('deleted_at')
                ->get();
        }else{
            $form_fields = DB::table('score_card_form_fields')->select('id', 'label', 'unit', 'form_type')
                ->where('business_area_id', $business_area_id)
                ->whereNull('deleted_at')
                ->get();
        }

        $form_fields_collection = $form_fields;
        $form_fields = $form_fields->toArray();
        $week_in_focus = $weeks->find($week_range_id);
        $currentWeekStart = Carbon::parse($week_in_focus->from_date);
        $previousWeekStart = $currentWeekStart->copy()->subWeek();
        $previousWeek = $weeks->where('from_date', $previousWeekStart)->first();
        $view_file = $request->has('embed') ? 'lay_out' : 'index';

        return view('frontend.business_goals.quadrants.' . $view_file, compact('form_fields', 'form_fields_collection',
            'presentation_data', 'weeks', 'business_area_id', 'accessible_business_areas', 'week_range_id', 'week_in_focus', 'business_area', 'previousWeek'));
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
                return is_numeric($value) ? (float)$value : 0; // Convert to float or set to 0 if not numeric
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
        $previousMonthEnd = $previousMonthStart->copy()->endOfMonth();

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
                'last week' => $previousWeekStart->format('l, F j, Y') . ' - ' . $previousWeekEnd->format('l, F j, Y'),
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
        $dataPoints = DataPoint::withOpenScoreCardFormField()->whereBetween('for_date', [$startDate, $endDate])
            ->where('business_area_id', $business_area_id)
            ->where('time_title', '!=', 'for_date') // Exclude daily data entries
            ->get();
        // Group by score_card_form_field_id
        $groupedDataPoints = $dataPoints->groupBy('score_card_form_field_id');

        $result = [];
        $score_card_form_fields = ScoreCardFormField::where('business_area_id', $business_area_id)->get();
        foreach ($groupedDataPoints as $fieldId => $group) {
            // Get the form field to determine the unit
            $formField = $score_card_form_fields->find($fieldId);

            // If formField is not found, continue to the next group
            if (!$formField) {
                continue;
            }

            if ($formField->form_type == 'text') {
                $result[$fieldId] = [
                    'field' => $formField->label,
                    'total' => $group->first()->data_value,
                ];
                continue;
            }

            $values = $group->pluck('data_value')->map(function ($value) {
                // Convert to numeric
                return is_numeric($value) ? (float)$value : 0;
            });

            if ($formField->unit === '%') {
                // Calculate the average for percentage-based fields
                $average = $values->avg();
                $result[$fieldId] = [
                    'field' => $formField->label,
                    'average' => round($average, 2)
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

    public function getSingleBSCDailyData(Request $request)
    {
        $validated = $request->validate([
            'business_area_id' => 'required|numeric'
        ]);

        return view('frontend.business_goals.dailies.index'); // Data is supplied by a Composer class

        $accessible_business_areas = $this->accessibleBusinessSectors();
        $weeks = WeekRange::orderBy('id', 'DESC')->get();
        $for_date = Carbon::yesterday();
        if ($request->filled('for_date')) {
            $for_date = Carbon::parse($request->for_date);
        }


        $temp_for_date = $for_date->copy();
        $end_date = $for_date->copy();
        $end_date = $end_date->subWeek(); // Get data for one week
        $business_area_id = $request->business_area_id;
        $business_area = BusinessArea::find($business_area_id);

        $data_points = $dates = [];
        $largest_value = [];
        $form_fields = ScoreCardFormField::where('business_area_id', $business_area_id)->get();
        for ($day = $temp_for_date; $day >= $end_date; $day->subDay()) {
            $date_string = $day->toDateString();
            $dates[$date_string] = $day;
            $data_points[$date_string]['data'] = $this->getDayData($business_area_id, $day);
            foreach ($form_fields->where('form_type', 'number') as $field) {
                $data_value = $data_points[$date_string]['data']->where('score_card_form_field_id', $field->id)->first();
                if (is_object($data_value) && (!isset($largest_value[$field->id]) || $largest_value[$field->id] < $data_value->data_value)) {
                    $largest_value[$field->id] = $data_value->data_value;
                }
            }
        }

//        dd($data_points['2024-09-29']['data']);

        return view('frontend.business_goals.dailies.index', compact(
            'business_area_id',
            'for_date',
            'data_points',
            'dates',
            'form_fields',
            'business_area',
            'accessible_business_areas',
            'weeks',
            'largest_value'
        ));
    }

    private function getDayData($business_area_id, $for_date)
    {
        $for_date = Carbon::parse($for_date);
        return DataPoint::where('business_area_id', $business_area_id)
            ->where('time_title', 'for_date')
            ->where('for_date', $for_date)
            ->get();
    }

}
