<?php

namespace App\Http\Controllers;

use App\Models\ScoreCardFormField;
use App\Models\StaffMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoughNotesController extends Controller
{
    private $dateFields = ['id_expiry_date', 'start_date'];
    private $timestampFields = ['deleted_at', 'resigned_on', 'restrict_access_from', 'staff_travel_blocked_at', 'stb_access_code_expires_at', 'created_at', 'updated_at', 'deactivated_at', 'deactivate_from'];

    // Function to convert '\N' to null
    private function convertToNull($value) {
        return $value === '\N' ? null : $value;
    }

    // Function to format date fields
    private function formatDate($value, $field) {
        if ($value === null || $value == '') return null;

        if (in_array($field, $this->dateFields)) {
            return Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d');
        } elseif (in_array($field, $this->timestampFields)) {
            return Carbon::createFromFormat('m/d/Y H:i', $value)->format('Y-m-d H:i:s');
        }

        return $value;
    }

    public function updateStaffData()
    {
        // Read the CSV file
//        $csvFile = storage_path('app/staff_data.csv');
        $csvData = staffDataAug();

        // Initialize arrays to store processed and new staff IDs
        $processedStaffIds = [];
        $newStaffIds = [];

        // Process each row in the CSV
        foreach ($csvData as $row) {
            $data = $row;

            // Convert '\N' to null and format date/timestamp fields
//            $data = array_map(function($value, $key) {
////                $value = $this->convertToNull($value);
//                return $this->formatDate($value, $key);
//            }, $data, array_keys($data));

            foreach($data as $key => $value){
                $data[$key] = $this->formatDate($value, $key);
            }

            if(array_key_exists('staff_ara_id', $data)) {

                $staffMember = StaffMember::where('staff_ara_id', $data['staff_ara_id'])->first();

                if (isset($staffMember)) {
                    // Update existing record
                    $staffMember->fill($data);
                    if ($staffMember->isDirty()) {
                        $staffMember->save();
                    }
                } else {
                    // Create new record
                    StaffMember::create($data);
                    $newStaffIds[] = $data['staff_ara_id'];
                }

                $processedStaffIds[] = $data['staff_ara_id'];
                echo $data['staff_ara_id'].'<br>';
            }
        }

        // Mark unmatched records as deleted and store them in a CSV
        $deletedStaff = StaffMember::whereNotIn('staff_ara_id', $processedStaffIds)->get();

        if ($deletedStaff->isNotEmpty()) {
            $deletedStaff->each(function ($staff) {
                $staff->deleted_at = now();
                $staff->save();
            });

            // Create CSV for deleted staff
            $deletedCsvPath = public_path('deleted_staff.csv');
            $deletedCsvFile = fopen($deletedCsvPath, 'w');

            // Write headers
            fputcsv($deletedCsvFile, array_keys($deletedStaff->first()->toArray()));

            // Write data
            foreach ($deletedStaff as $staff) {
                fputcsv($deletedCsvFile, $staff->toArray());
            }

            fclose($deletedCsvFile);
        }

        // Prepare response
        $response = [
            "message" => "Process completed.",
            "new_staff_count" => count($newStaffIds),
            "deleted_staff_count" => $deletedStaff->count(),
        ];

        if ($deletedStaff->isNotEmpty()) {
            $response["deleted_staff_csv_path"] = $deletedCsvPath;
        }

//        return response()->json($response);
    }

}
