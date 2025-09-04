<?php


namespace App\Http\Composers;


use App\Models\BusinessArea;
use App\Models\DataPoint;
use App\Models\ScoreCardFormField;
use App\Models\WeekRange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BscDailyReportComposer
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        // https://chatgpt.com/g/g-QSh6KHL3S-pdf-reader/c/671e80b6-d0ac-8005-8c80-525bfe463c86 - 01:40pm 11/11/2024
        // Specify the view name you want to exclude
        $excludedView = 'frontend.business_goals.dailies._table';
        // Check if the view name matches the excluded view and skip if it does
        if ($view->getName() === $excludedView) {
            return;
        }
        // ChatGPT - 01:40pm 11/11/2024



        $request = $this->request;
        $accessible_business_areas = auth()->user()->accessibleBusinessAreas();
        $weeks = WeekRange::orderBy('id', 'DESC')->get();
        $for_date = Carbon::yesterday();
        if ($request->filled('for_date')) {
            $for_date = Carbon::parse($request->for_date);
        } else if($request->filled('week_range_id')){
$week_in_focus = WeekRange::find($request->week_range_id);
if($week_in_focus){
    $for_date = $week_in_focus->to_date;
}
        }


        $temp_for_date = $for_date->copy();
        $end_date = $for_date->copy();
        $end_date = $end_date->subWeek(); // Get data for one week
        $end_date->addDay();
        $lastUnderscorePos = strrpos($view->name(), '_');
        $business_area_id = $request->input('business_area_id', substr($view->name(), $lastUnderscorePos + 1));
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
        $view->with([
            'business_area_id' => $business_area_id,
    'for_date' => $for_date,
    'end_date' => $end_date,
    'data_points' => $data_points,
    'dates' => $dates,
    'form_fields' => $form_fields,
    'business_area' => $business_area,
    'accessible_business_areas' => $accessible_business_areas,
    'weeks' => $weeks,
    'largest_value' => $largest_value
        ]);
    }

    private function getDayData($business_area_id, $for_date)
    {
        $for_date = Carbon::parse($for_date);
        return DataPoint::withOpenScoreCardFormField()->where('business_area_id', $business_area_id)
            ->where('time_title', 'for_date')
            ->where('for_date', $for_date)
            ->get();
    }
}
