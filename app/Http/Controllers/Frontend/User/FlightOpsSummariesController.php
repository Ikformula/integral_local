<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\FlightOpsSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightOpsSummariesController extends Controller
{
    public $columns;
    public function __construct()
    {
        $this->columns = [
            'airline' => 'text',
            'total_flights' => 'number',
            'cnx_flights' => 'number',
            'otp' => 'number',
            'load_factor' => 'number',
            'comp_factor' => 'number',
            'no_of_pax' => 'number',
            'ac_cap' => 'number',
            'no_of_ac_utilised' => 'number',
            'no_of_ac_available' => 'number'
        ];
    }

    public function index()
    {
        $logs = FlightOpsSummary::orderBy('id', 'DESC')->take(400)->get()->reverse();
        $columns = $this->columns;

        return view('frontend.flight_ops_summaries.index', compact('logs', 'columns'));
    }

    public function store(Request $request)
    {
        $log = FlightOpsSummary::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Record created successfully',
            'new_log' => $log
        ], 201);
    }

    public function update(Request $request)
    {
        // Retrieve the original data
        $log = FlightOpsSummary::findOrFail($request->log_id);
//        $originalData = $log->getOriginal();

        // Update the record
        $log->update($request->all());

        // Compare the original data with the updated data
//        $updatedData = $log->getAttributes();
//        $changedColumns = array_diff_assoc($updatedData, $originalData);

        // Record the history of each changed column
//        foreach ($changedColumns as $column => $newValue) {
//            if ($column !== 'updated_at') {  // Skip the updated_at column
//                DB::table('fuel_consumption_report_histories')->insert([
//                    'column_name' => $column,
//                    'fuel_consumption_report_id' => $log->id,
//                    'former_value' => $originalData[$column] ?? null,
//                    'created_at' => now(),
//                    'updated_at' => now(),
//                ]);
//            }
//        }

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully',
            'log' => $log
        ], 200);
    }

    public function destroy($id)
    {
        $log = FlightOpsSummary::findOrFail($id);
        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully'
        ], 200);
    }

}
