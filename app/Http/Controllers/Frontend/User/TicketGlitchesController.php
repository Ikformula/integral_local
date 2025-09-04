<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\TicketGlitchPnr;
use App\Models\TicketGlitchPnrHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketGlitchesController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->filled('from_date')
            ? Carbon::parse($request->from_date)
            : Carbon::now()->subMonth();

        $to_date = $request->filled('to_date')
            ? Carbon::parse($request->to_date)
            : Carbon::now();

        $bookings = TicketGlitchPnr::query();
        $bookings = $bookings->whereBetween('operation_day', [$from_date, $to_date]);

        if($request->filled('exclude_ticketed')){
            $bookings = $bookings->where('ticket_status', '!=', 'TK');
        }

        $bookings = $bookings->orderBy('operation_day', 'desc')
            ->get();

        $stats = $this->calculateStats($bookings);
        $stats['Days'] = [
            'title' => 'Days from '.$from_date->toDateString() .' to '. $to_date->toDateString(),
            'value' => $from_date->diffInDays($to_date),
            'icon' => 'calendar-days'
        ];

        return view('frontend.ticket_glitches.index')->with([
            'bookings' => $bookings,
            'stats' => $stats,
            'stats_col_class' => 'col-md-3',
            'from_date' => $from_date,
            'to_date' => $to_date,
            'params' => $request->query()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $csvFile = $request->file('csv_file');

        if (!$csvFile || !$csvFile->isValid()) {
            return redirect()->back()->withErrors('Invalid or missing CSV file.');
        }

        DB::beginTransaction();

        try {
            $this->processCsvFile($csvFile);
            DB::commit();
            return redirect()->back()->withFlashSuccess('CSV file processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CSV processing error: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error processing CSV file: ' . $e->getMessage());
        }
    }

    private function processCsvFile($csvFile)
    {
        $filePath = $csvFile->getRealPath();

        if (!$filePath) {
            $filePath = $csvFile->getPathname();
        }

        if (!$filePath || !file_exists($filePath)) {
            throw new \Exception('Unable to get the file path. File details: ' . json_encode($csvFile->getAttributes()));
        }

        $file = fopen($filePath, 'r');

        if ($file === false) {
            throw new \Exception('Unable to open the file at path: ' . $filePath);
        }

        fgetcsv($file); // Skip headers

        $rowCount = 0;
        while (($data = fgetcsv($file)) !== false) {
            $rowCount++;
            if (count($data) < 5) {
                Log::warning("Row $rowCount has insufficient data. Skipping.");
                continue; // Skip rows with insufficient data
            }

            $pnr = $data[0];
            $existingRecord = TicketGlitchPnr::where('pnr', $pnr)->first();

            if ($existingRecord) {
                $this->backupExistingRecord($existingRecord);
                $this->updateExistingRecord($existingRecord, $data);
            } else {
                $this->createNewRecord($data);
            }
        }

        fclose($file);
        Log::info("Processed $rowCount rows from CSV file.");
    }

    private function backupExistingRecord($record)
    {
        $arr['ticket_glitch_pnr_id'] = $record->id;
        TicketGlitchPnrHistory::create(array_merge($record->toArray(), $arr));
    }

    private function updateExistingRecord($record, $data)
    {
        $record->update([
            'user' => $data[1],
            'vpos' => $data[2],
            'operation_date' => $data[3],
            'operation_day' => $this->convertDateFormat($data[3]),
            'order_id' => $data[4],
            'departure_date' => $this->convertDateFormat($data[5]),
            'ticket_status' => $data[6],
            'ticketed_date' => $this->convertDateFormat($data[7]),
        ]);
    }

    private function createNewRecord($data)
    {
        TicketGlitchPnr::create([
            'pnr' => $data[0],
            'user' => $data[1],
            'vpos' => $data[2],
            'operation_date' => $data[3],
            'operation_day' => $this->convertDateFormat($data[3]),
            'order_id' => $data[4],
            'departure_date' => $this->convertDateFormat($data[5]),
            'ticket_status' => $data[6],
            'ticketed_date' => $this->convertDateFormat($data[7]),
        ]);
    }

    private function convertDateFormat($inputDate)
    {
        $dateTimeArray = explode(' ', $inputDate);
        if (count($dateTimeArray) == 2) {
            list($day, $month, $year) = explode('-', $dateTimeArray[0]);
            return "{$year}-{$month}-{$day}";
        }
        return null;
    }

    private function calculateStats($bookings)
    {
        $totalTicketed = $bookings->where('ticket_status', 'TK')->count();
        $totalNonTicketed = $bookings->where('ticket_status', '!=', 'TK')->count();
        return [
            'Total PNRs' => [
                'title' => 'Total PNRs',
                'value' => $bookings->count(),
                'icon' => 'ticket'
            ],
            'Ticketed' => [
                'title' => 'Ticketed',
                'value' => $totalTicketed,
                'icon' => 'ticket'
            ],
            'Non Ticketed' => [
                'title' => 'Non Ticketed',
                'value' => $totalNonTicketed,
                'icon' => 'ticket'
            ],
            'Days' => [
                'title' => 'Non Ticketed',
                'value' => $totalNonTicketed,
                'icon' => 'ticket'
            ],
        ];
    }

    public function show($day)
    {
        $bookings = TicketGlitchPnr::where('operation_day', $day)->get();
        return view('frontend.ticket_glitches.day_glitches')->with([
            'day' => $day,
            'bookings' => $bookings
        ]);
    }

    public function updatePNR(Request $request)
    {
        $pnr = TicketGlitchPnr::where('pnr', $request->pnr)->first();
        if($pnr) {
            $pnr->update($request->all());

            return [
                'message' => 'PNR saved',
                'status' => 'success',
                'pnr' => $pnr
            ];
        }
        return [
            'message' => 'PNR not found',
            'status' => 'failed'
        ];
    }
}
