<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CsvUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showUploadForm()
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        return view('csv_upload.csv-upload', compact('tables'));
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'table' => 'required|string',
        ]);

        $table = $request->input('table');
        $columns = Schema::getColumnListing($table);
        $columnTypes = $columnMatches = [];

        $csvFile = $request->file('csv_file');
        $csvData = array_map('str_getcsv', file($csvFile->getPathname()));
        $csvHeaders = array_shift($csvData);
        $sortedHeaders = $csvHeaders;
        foreach ($columns as $column) {
            $columnTypes[$column] = DB::getSchemaBuilder()->getColumnType($table, $column);
        }

        $columnMatches = [
            'date_f' => 'date',
            'charter' => 'charter',
            'cargo_docum' => 'cargo docum.',
            'cargo' => 'cargo',
            'corpgrpcall_centre_sales' => 'corp/grp/call centre sales',
            'travel_agents' => 'travel agents',
            'paystack' => 'paystack',
            'interswitch_sales' => 'interswitch sales',
            'web_salesteflon' => 'web sales-teflon',
            'boh' => 'boh',
            'mobile_sales' => 'mobile sales',
            'gtpayment' => 'gt-payment',
            'gladepay' => 'gladepay',
            'excess_bag' => 'excess bag',
            'penalty' => 'penalty',
            'domestic' => 'domestic'
        ];

        sort($sortedHeaders);

        return view('csv_upload.map-columns', compact('table', 'columns', 'columnTypes', 'csvHeaders', 'sortedHeaders', 'csvData', 'columnMatches'));
    }

    public function importData(Request $request)
    {
        $table = $request->input('table');
        $mapping = $request->input('mapping');
        $conversions = $request->input('conversions', []);
        $csvData = json_decode($request->input('csv_data'), true);

        $inserted = 0;
        $failed = 0;

        foreach ($csvData as $row) {
            $data = [];
            foreach ($mapping as $dbColumn => $csvColumn) {
                if ($csvColumn !== null) {
                    $value = $row[$csvColumn];
                    echo $csvColumn.': '.$value.' | ';
                    if (isset($conversions[$dbColumn])) {
                        $value = $this->convertValue($value, $conversions[$dbColumn]);
                    }
                    $data[$dbColumn] = $value;
                }
            }
            echo '<br>';
            try {
//                DB::table($table)->insert($data);
                $inserted++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        die();

        return response()->json([
            'inserted' => $inserted,
            'failed' => $failed,
        ]);
    }

    private function convertValue($value, $type)
    {
        switch ($type) {
            case 'date':
                return date('Y-m-d', strtotime($value));
            case 'timestamp':
                return date('Y-m-d H:i:s', strtotime($value));
            default:
                return $this->isNumberFormat($value);
        }
    }

    private function isNumberFormat($value) {
        // Regular expression pattern to match number_format style numbers
        $pattern = '/^\d{1,3}(,\d{3})*(\.\d{2})?$/';

        // Check if the string matches the pattern
        if (preg_match($pattern, $value)) {
            return str_replace(',', '', $value);
        } else {
            return $value;
        }
    }

    public function findBestMatch($headers, $string)
    {
        $bestMatch = null;
        $highestSimilarity = 0;

        foreach ($headers as $header) {
            similar_text($string, $header, $similarity);

            if ($similarity > $highestSimilarity) {
                $highestSimilarity = $similarity;
                $bestMatch = $header;
            }
        }

        return $bestMatch;
    }
}
