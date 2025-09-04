<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\SpiMetricEntry;
use App\Models\SpiMetricTarget;
use App\Models\SpiQuarterlyPerformance;
use App\Models\SpiSector;
use App\Models\SpiObjective;
use App\Models\SpiIndicator;
use App\Models\SpiMetric;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SRBController extends Controller
{

    public function index()
    {
        // Eager load sectors with objectives, indicators, and metrics to avoid N+1 query issues
        $sectors = SpiSector::with([
            'objectives.indicators.metrics'
        ])->get();

        return view('frontend.spi.index', compact('sectors'));
    }

    public function structureAssessor()
    {
        $sectors = SpiSector::with([
            'objectives.indicators.metrics'
        ])->get();

        return view('frontend.spi.sectors.index', compact('sectors'));
    }

    public function setPeriodTarget(Request $request)
    {
        $year = $request->input('year', now()->year);
        $sectors = SpiSector::with([
            'objectives.indicators.metrics.targets'
        ])->get();

        $target_labels = [
            'green_left_limit',
            'green_right_limit',
            'yellow_left_limit',
            'yellow_right_limit',
            'red_left_limit',
            'red_right_limit',
        ];

        $target_colours = [
            'success',
            'success',
            'warning',
            'warning',
            'danger',
            'danger',
        ];

        return view('frontend.spi.targets', compact('year', 'sectors', 'target_labels', 'target_colours'));
    }

    public function updateMetricTargets(Request $request)
    {
        $validated = $request->validate([
            'metric_id' => 'required|exists:spi_metrics,id',
            'year' => 'required|integer',
            'target_colour_direction' => 'required|string'
        ]);

        $targetFields = [
            'green_left_limit',
            'green_right_limit',
            'yellow_left_limit',
            'yellow_right_limit',
            'red_left_limit',
            'red_right_limit',
        ];

        $fromDate = "{$validated['year']}-01-01";
        $toDate = "{$validated['year']}-12-31";

        // Find or create a new target record for the metric and year
        $metricTarget = SpiMetricTarget::updateOrCreate(
            [
            'spi_metric_id' => $validated['metric_id'],
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'year' => $validated['year'],
            ],
            [
                $request->target_colour_direction => $request->input($request->target_colour_direction)
            ]
        );


        return response()->json([
            'success' => true,
            'message' => 'Metric target updated successfully!',
        ]);
    }

    public function setMetricFormulae(Request $request)
    {
        $sectors = SpiSector::with([
            'objectives.indicators.metrics'
        ])->get();

        return view('frontend.spi.metric_formulae', compact('sectors'));
    }

    public function updateMetricFormula(Request $request)
    {
        $validated = $request->validate([
            'metric_id' => 'required|exists:spi_metrics,id',
            'parameters' => 'required|array',
            'parameters.*.letter' => 'required|string|max:1',
            'parameters.*.title' => 'required|string|max:255',
            'operation' => 'required|string|max:255',
        ]);

        $metric = SpiMetric::find($validated['metric_id']);

        // Store the formula as a JSON structure
        $metric->metric_formula = json_encode([
            'parameters' => $validated['parameters'],
            'operation' => $validated['operation'],
        ]);

        $metric->save();

        return response()->json(['success' => true, 'message' => 'Formula updated successfully.']);
    }

    public function updateMetricCentrikStatus(Request $request)
    {
        $validated = $request->validate([
            'metric_id' => 'required|exists:spi_metrics,id'
            ]);

        $metric = SpiMetric::find($validated['metric_id']);
        $metric->is_centrik_item = !isset($metric->is_centrik_item) ? 1 : null;
        $metric->save();

        return response()->json(['success' => true, 'message' => 'Metric Centrik Status updated successfully.', 'is_centrik' => $metric->is_centrik_item]);

    }

    public function canAccessSector($user_id, $sector_id)
    {
        if(DB::table('spi_sector_user_permissions')->where('user_id', $user_id)->where('sector_id', $sector_id)->count() || auth()->user()->isAdmin())
            return true;
        return false;
    }

    public function reportEntry(Request $request)
    {
        $user = auth()->user();
        if(!$user->spi_sectors()->count() && !$user->isAdmin())
            return redirect()->back()->withErrors('Unauthorized');

        $request->validate([
            'for_date' => ['date']
        ]);

        $sector_ids = $user->spi_sectors()->toArray();
        $sectors = SpiSector::whereIn('id', $sector_ids)->with([
            'objectives.indicators.metrics'
        ])->get();

        $for_date = Carbon::parse($request->input('for_date', now()));

        $spi_metric_entries = SpiMetricEntry::whereHas('metric.indicator.objective.sector', function ($query) use ($sector_ids) {
            $query->whereIn('id', $sector_ids);
        })->where('for_date', $for_date)
            ->get();

        return view('frontend.spi.data_entry', compact('sectors', 'for_date', 'spi_metric_entries'));
    }

    public function storeMetricEntry(Request $request)
    {
        $validated = $request->validate([
            'metric_id' => 'required|exists:spi_metrics,id',
            'for_date' => 'required|date',
            'parameters' => 'required|array',
            'parameters.*' => 'nullable|string|max:192',
        ]);

        $metric = SpiMetric::findOrFail($validated['metric_id']);

        // Decode metric formula from the metric
        $formula = $metric->metric_formula ? json_decode($metric->metric_formula, true) : null;

        // Save raw entry data
        $entryData = $validated['parameters'];
        $rawJsonEntryData = json_encode($entryData);

        logger($entryData);
        // Calculate the amount based on the formula or default to first parameter
        $amount = $this->calculateAmount($formula, $entryData);

        $for_date = Carbon::parse($validated['for_date']);
        $metric_target = $metric->targets->where('year', $for_date->year)->first();
        $colour = null;
        if($metric_target){
            $colour = $this->computeColour($amount, $metric_target);
        }

        $previousRawData = null;
        $previousEntry = SpiMetricEntry::where('spi_metric_id', $metric->id)
            ->where('for_date', $validated['for_date'])
            ->first();

        if ($previousEntry) {
            $previousRawData = $previousEntry->entry_data; // JSON string of the previous entry data
        }

        // Save the entry, updating if it already exists for the same date
        $entry = SpiMetricEntry::updateOrCreate(
            [
                'spi_metric_id' => $metric->id,
                'for_date' => $validated['for_date'],
            ],
            [
                'entry_data' => $rawJsonEntryData,
                'amount' => $amount,
                'spi_metric_target_id' => $metric_target->id ?? 0,
                'user_id' => auth()->id(),
                'month' => $for_date->format('F'),
                'year' => $for_date->year,
                'quarter_number' => ceil($for_date->month / 3),
                'colour_flag' => $colour
            ]
        );

// Update quarterly performance
        $this->updateQuarterlyPerformance([
            'for_date' => $validated['for_date'],
            'entry_data' => $rawJsonEntryData,
            'old_entry_data' => $previousRawData ?? null, // Pass previous data if updating
            'is_update' => isset($previousRawData),
            'spi_metric_target_id' => $metric_target ? $metric_target->id : 0,
            'user_id' => auth()->id(),
        ], $metric);



        return response()->json(['success' => true, 'message' => 'Entry saved successfully! value: '.$amount, 'entry' => $entry, 'amount' => $amount]);
    }

    private function calculateAmount($formula, $entryData)
    {
        if (!$formula) {
            // If no formula, return the value of the first parameter
            $firstKey = array_key_first($entryData);
            return $entryData[$firstKey] ?? 0;
        }

        $parameters = $formula['parameters'] ?? [];
        $operation = $formula['operation'] ?? '';

        // Map parameters to their corresponding values from entryData
        $mappedVariables = [];
        foreach ($parameters as $parameter) {
            $letter = $parameter['letter'];
            $mappedVariables[$letter] = $entryData[$letter] ?? 0;
        }

        // Convert the operation to use PHP variable syntax
        foreach ($parameters as $parameter) {
            $letter = $parameter['letter'];
            $operation = str_replace($letter, "\${$letter}", $operation); // Replace 'a' with '$a', 'b' with '$b', etc.
        }

        // Use `extract` to define the variables for the operation
        extract($mappedVariables); // Creates variables $a, $b, $c, etc.

        try {
            // Safely evaluate the operation
            $result = eval("return {$operation};"); // Evaluate the operation using the extracted variables
            return round($result, 4);
        } catch (\Throwable $e) {
            // Handle any evaluation errors
            report($e);
            return 0; // Default to 0 on error
        }
    }


    private function updateQuarterlyPerformance($entry, $metric)
    {
        $forDate = Carbon::parse($entry['for_date']);
        $quarterNumber = ceil($forDate->month / 3);
        $year = $forDate->year;

        $quarter_entries = SpiMetricEntry::where('spi_metric_id', $metric->id)
            ->where('quarter_number', $quarterNumber)
            ->where('year', $year)
            ->get();
        $sum_amounts = $quarter_entries->sum('amount');
        $calculatedAmount = $sum_amounts;

        if(in_array($metric->unit, ['percentage', 'rate'])){
            $calculatedAmount = $sum_amounts / $quarter_entries->count();
        }

        // Retrieve the existing quarterly performance for the metric and period
        $quarterlyPerformance = SpiQuarterlyPerformance::firstOrNew([
            'spi_metric_id' => $metric->id,
            'year' => $year,
            'quarter_number' => $quarterNumber,
        ]);

        if($entry['spi_metric_target_id'] && $calculatedAmount){
            $metric_target = SpiMetricTarget::find($entry['spi_metric_target_id']);
            $colour = $this->computeColour($calculatedAmount, $metric_target);
        }

        // Update the quarterly performance record
//        $quarterlyPerformance->entry_data = json_encode($currentEntryData);
        $quarterlyPerformance->amount = $calculatedAmount;
        $quarterlyPerformance->spi_metric_target_id = $entry['spi_metric_target_id'] ?? 0;
        $quarterlyPerformance->user_id = $entry['user_id'];
        $quarterlyPerformance->from_date = $forDate->copy()->startOfQuarter()->toDateString();
        $quarterlyPerformance->to_date = $forDate->copy()->endOfQuarter()->toDateString();
        $quarterlyPerformance->colour_flag = $colour ?? null;
        $quarterlyPerformance->save();


        // Store percentage implementation
        $effective_implmntxn_metric = SpiMetric::find(82);
        $effective_implmntxn_metric_target = $effective_implmntxn_metric->targets->where('year', $year)->first();
        // Compute Performance for the quarter
        $num_metrics = SpiMetric::where('id', '!=', $effective_implmntxn_metric->id)->count();
        $num_recorded_for_quarter = SpiQuarterlyPerformance::where('year', $year)
            ->where('quarter_number', $quarterNumber)
            ->count();

                if($num_metrics){
            $effective_metric_target = $effective_implmntxn_metric->targets->where('year', $year)->first();
            $perc_effective_implmntxn = round(($num_recorded_for_quarter / $num_metrics) * 100, 2);

            $quarterly_perc_effective_implmntxn = SpiQuarterlyPerformance::updateOrCreate([
                'spi_metric_id' => $effective_implmntxn_metric->id,
                'year' => $year,
                'quarter_number' => $quarterNumber,
                'from_date' => $quarterlyPerformance->from_date,
                'to_date' => $quarterlyPerformance->to_date
            ], [
                'entry_data' => json_encode(['a' => $perc_effective_implmntxn]),
                'amount' => $perc_effective_implmntxn,
                'spi_metric_target_id' => $effective_implmntxn_metric_target->id,
                'user_id' => auth()->id(),
                'colour_flag' => $this->computeColour($calculatedAmount, $effective_metric_target)
            ]);
        }
    }


    private function computeColour($amount, $target)
    {
        if (!$amount || !is_numeric($amount) || !$target) {
            return null; // Return null for invalid inputs
        }

        // Define limits and their associated colors
        $colour_lims = [
            ['green_left_limit', 'green_right_limit'],
            ['yellow_left_limit', 'yellow_right_limit'],
            ['red_left_limit', 'red_right_limit'],
        ];

        $colours = [
            'green',
            'yellow',
            'red',
        ];

        foreach ($colour_lims as $index => $limits) {
            $left = $target->{$limits[0]};
            $right = $target->{$limits[1]};

            if($this->inRange($amount, $left, $right)){
                logger("Colour: $colours[$index]");
                return $colours[$index];
            }
        }

        return null; // Return null if no color band matches
    }

    public function inRange($value, $left, $right)
    {
        if(!is_numeric($value))
            return null;

        if(is_numeric($left) && is_numeric($right)){
            $min = min([$left, $right]);
            $max = max([$left, $right]);

            return (($min <= $value) && ($value <= $max));

        }elseif(in_array($left, ['LT', 'LTE', 'GT', 'GTE',]) && is_numeric($right)){
            return $this->computeColourLtGt($value, $right, $left);
        }elseif(in_array($right, ['LT', 'LTE', 'GT', 'GTE',]) && is_numeric($left)){
            return $this->computeColourLtGt($value, $left, $right);
        }
        return false;
    }

    public function computeColourLtGt($value, $number_limit, $sign)
    {
        if($sign == 'LT'){
            return $value < $number_limit;
        }elseif($sign == 'LTE'){
            return $value <= $number_limit;
        }elseif($sign == 'GT'){
            return $value > $number_limit;
        }elseif($sign == 'GTE'){
            return $value >= $number_limit;
        }
        return null;
    }

    public function viewReportForPeriod(Request $request)
    {
        $validated = $request->validate([
            'year' => ['required'],
            'prepared_by_name' => ['required', 'string'],
            'prepared_by_designation' => ['required', 'string'],
            'prepared_by_date' => ['required', 'string'],
            'prepared_by_sign' => ['nullable', 'file', 'image', 'max:2048'],
            'approved_by_name' => ['required', 'string'],
            'approved_by_designation' => ['required', 'string'],
            'approved_by_date' => ['required', 'string'],
            'approved_by_sign' => ['nullable', 'file', 'image', 'max:2048'],
            'corrective_action_plan' => ['required', 'string']
        ]);

        $data = $request->except(['prepared_by_sign', 'approved_by_sign']);

        // Convert prepared_by signature to base64
        if ($request->hasFile('prepared_by_sign')) {
            $preparedSignImage = $request->file('prepared_by_sign');
            $data['prepared_by_sign'] = 'data:image/' .
                $preparedSignImage->getClientOriginalExtension() . ';base64,' .
                base64_encode(file_get_contents($preparedSignImage));
        }

        // Convert approved_by signature to base64
        if ($request->hasFile('approved_by_sign')) {
            $approvedSignImage = $request->file('approved_by_sign');
            $data['approved_by_sign'] = 'data:image/' .
                $approvedSignImage->getClientOriginalExtension() . ';base64,' .
                base64_encode(file_get_contents($approvedSignImage));
        }

        $sectors = SpiSector::with('objectives.indicators.metrics')->get();
        $metrics_count = [];

        foreach ($sectors as $sector) {
            foreach ($sector->objectives as $objective) {
                $metricsCount = $objective->indicators
                    ->flatMap(fn($indicator) => $indicator->metrics)
                    ->count();
                $metrics_count[$objective->id] = $metricsCount;
            }
        }

        $performances = SpiQuarterlyPerformance::where('year', $request->year)->get();

        return view('frontend.spi.performance-report',
            compact('performances', 'sectors', 'metrics_count', 'data')
        )->with([
            'year' => $validated['year'],
            'corrective_action_plan' => $validated['corrective_action_plan']
        ]);
    }

    // Store a new Objective
    public function storeObjective(Request $request)
    {
        $validated = $request->validate([
            'sector_id' => 'required|exists:spi_sectors,id',
            'objective' => 'required|string',
        ]);

        $objective = SpiObjective::create([
            'spi_sector_id' => $validated['sector_id'],
            'objectives' => $validated['objective'],
        ]);

        return response()->json(['message' => 'Objective added successfully', 'data' => $objective], 201);
    }

    // Store a new Indicator
    public function storeIndicator(Request $request)
    {
        $validated = $request->validate([
            'objective_id' => 'required|exists:spi_objectives,id',
            'indicator' => 'required|string',
        ]);

        $indicator = SpiIndicator::create([
            'spi_objective_id' => $validated['objective_id'],
            'indicator' => $validated['indicator'],
        ]);

        return response()->json(['message' => 'Indicator added successfully', 'data' => $indicator], 201);
    }

    // Store a new Metric
    public function storeMetric(Request $request)
    {
        $validated = $request->validate([
            'indicator_id' => 'required|exists:spi_indicators,id',
            'metric' => 'required|string',
            'unit' => 'required|string',
        ]);

        $metric = SpiMetric::create([
            'spi_indicator_id' => $validated['indicator_id'],
            'metric' => $validated['metric'],
            'unit' => $validated['unit'],
        ]);

        return response()->json(['message' => 'Metric added successfully', 'data' => $metric], 201);
    }

    // Update Objective
    public function updateObjective(Request $request, SpiObjective $objective)
    {
        $validated = $request->validate([
            'objective' => 'required|string',
        ]);

        $objective->update(['objectives' => $validated['objective']]);

        return response()->json(['message' => 'Objective updated successfully', 'data' => $objective], 200);
    }

    // Update Indicator
    public function updateIndicator(Request $request, SpiIndicator $indicator)
    {
        $validated = $request->validate([
            'indicator' => 'required|string',
        ]);

        $indicator->update(['indicator' => $validated['indicator']]);

        return response()->json(['message' => 'Indicator updated successfully', 'data' => $indicator], 200);
    }

    // Update Metric
    public function updateMetric(Request $request, SpiMetric $metric)
    {
        $validated = $request->validate([
            'metric' => 'required|string',
            'unit' => 'required|string',
        ]);

        $metric->update(['metric' => $validated['metric'], 'unit' => $validated['unit']]);

        return response()->json(['message' => 'Metric updated successfully', 'data' => $metric], 200);
    }

    // Delete Objective
    public function destroyObjective(SpiObjective $objective)
    {
        $objective->delete();

        return response()->json(['message' => 'Objective deleted successfully'], 200);
    }

    // Delete Indicator
    public function destroyIndicator(SpiIndicator $indicator)
    {
        $indicator->delete();

        return response()->json(['message' => 'Indicator deleted successfully'], 200);
    }

    // Delete Metric
    public function destroyMetric(SpiMetric $metric)
    {
        $metric->delete();

        return response()->json(['message' => 'Metric deleted successfully'], 200);
    }

}
