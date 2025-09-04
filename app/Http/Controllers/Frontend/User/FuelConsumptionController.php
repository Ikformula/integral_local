<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\FuelConsumptionReport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class FuelConsumptionController extends Controller
{
    public $columns;
    public function __construct()
    {
        $this->columns = [
//            'date_f',
            'vendors',
            'nf',
            'location',
            'destination',
            'flight_no',
            'adi_no',
            'invoiced',
            'calibration',
            'ac_reg',
            'mtr_after',
            'mtr_before',
            'uplifts',
            'unit_price',
            'debit',
        ];
    }

    public function index()
    {
        $fuel_consumption_reports = FuelConsumptionReport::orderBy('id', 'DESC')->take(100)->get()->reverse();
        $columns = $this->columns;

        $locations = $destinations = [
            "LOS",
            "ABV",
            "PHC",
            "BNI",
            "ESC",
            "ILR",
            "JOS",
            "MIU",
            "QRW",
            "SKO",
            "YOL"
        ];

        $vendor_names = [
            "11 PLC",
            "AFLOAT",
            "ARDOVA",
            "ASHARAMI",
            "CITA BFSL",
            "CIVIC",
            "CLEANSERVE",
            "CONOIL",
            "FORTE OIL",
            "GEOMETRICS ENERGY",
            "JUSHAD",
            "MRS OIL",
            "NEPAL",
            "OCTAVUS",
            "OVH ENERGY",
            "RAVEN ENERGY LTD",
            "REEVE ENERGY",
            "STAR ORIENT",
            "TOTAL NIG PLC",
            "KEROJET"
        ];

        $ac_reg = [
            "MJF",
            "BKW",
            "BKX",
            "MJQ",
            "BXV",
            "LY-CCK",
            "BKU"
        ];

        return view('frontend.fuel_consumption_reports.index', compact('fuel_consumption_reports', 'columns', 'locations', 'destinations', 'vendor_names', 'ac_reg'));
    }

    public function store(Request $request)
    {
        $log = FuelConsumptionReport::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Record created successfully',
            'new_log' => $log
        ], 201);
    }

    public function update(Request $request)
    {
        // Retrieve the original data
        $log = FuelConsumptionReport::findOrFail($request->log_id);
        $originalData = $log->getOriginal();

        // Update the record
        $log->update($request->all());

        // Compare the original data with the updated data
        $updatedData = $log->getAttributes();
        $changedColumns = array_diff_assoc($updatedData, $originalData);

        // Record the history of each changed column
        foreach ($changedColumns as $column => $newValue) {
            if ($column !== 'updated_at') {  // Skip the updated_at column
                DB::table('fuel_consumption_report_histories')->insert([
                    'column_name' => $column,
                    'fuel_consumption_report_id' => $log->id,
                    'former_value' => $originalData[$column] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully',
            'log' => $log
        ], 200);
    }

    public function destroy($id)
    {
        $log = FuelConsumptionReport::findOrFail($id);
        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully'
        ], 200);
    }

}
