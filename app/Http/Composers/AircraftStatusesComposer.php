<?php

namespace App\Http\Composers;

use App\Models\Aircraft;
use App\Models\AircraftChecklistItem;
use App\Models\AircraftStatusSubmission;
use App\Models\WeekRange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AircraftStatusesComposer
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose(View $view)
    {
        $request = $this->request;
        $date = $this->request->input('for_date', Carbon::yesterday()->toDateString());

        if($request->filled('week_range_id')){
            $to_date = WeekRange::find($request->week_range_id);
            if($to_date) {
                $submissions_count = AircraftStatusSubmission::where('for_date', $to_date->to_date)->count();
                if($submissions_count) {
                    $date = $to_date->to_date;
                }
            }
        }

        if($date > now()){
            $date = now()->subDay();
        }

        // Fetch the aircraft checklist items for each fleet
        $b737ChecklistItems = AircraftChecklistItem::where('fleet', 'B-737')->get();
        $q400ChecklistItems = AircraftChecklistItem::where('fleet', 'Q-400')->get();

        // Fetch the aircraft for each fleet
        $b737Aircraft = Aircraft::where('fleet', 'B-737')->get();
        $q400Aircraft = Aircraft::where('fleet', 'Q-400')->get();

        $fleets = [
            'B-737' => ['checklist' => $b737ChecklistItems, 'aircrafts' => $b737Aircraft],
            'Q-400' => ['checklist' => $q400ChecklistItems, 'aircrafts' => $q400Aircraft]
        ];

        // cGPT Sol: https://chatgpt.com/g/g-QSh6KHL3S-pdf-reader/c/671e80b6-d0ac-8005-8c80-525bfe463c86 10:47am, 31st Oct, 2024 - start
        // Fetch existing status submissions for the given date
        $submissions = AircraftStatusSubmission::where('for_date', $date)->get()->keyBy(function ($item) {
            return $item->aircraft_id . '_' . $item->checklist_id;
        });

// If no submissions exist for the given date, find the most recent submissions before that date
        if ($submissions->isEmpty()) {
            $latestPreviousSubmissions = AircraftStatusSubmission::where('for_date', '<', $date)
                ->orderBy('for_date', 'asc')
                ->get()
                ->keyBy(function ($item) {
                    return $item->aircraft_id . '_' . $item->checklist_id;
                });


            // If there are previous submissions, use them for the given date
            if ($latestPreviousSubmissions->isNotEmpty()) {
                // Clone each submission to a new record for the given date
                foreach ($latestPreviousSubmissions as $submission) {
                    $newSubmission = $submission->replicate(); // Create a copy of the submission
                    $newSubmission->for_date = $date;          // Set the new date
                    $newSubmission->save();                    // Save as a new record
                }

                // Refetch the submissions for the given date to proceed with the updated dataset
                $submissions = AircraftStatusSubmission::where('for_date', $date)->get()->keyBy(function ($item) {
                    return $item->aircraft_id . '_' . $item->checklist_id;
                });
            }
        }

        // cGPT Sol: https://chatgpt.com/g/g-QSh6KHL3S-pdf-reader/c/671e80b6-d0ac-8005-8c80-525bfe463c86  - end


        // Share data with the view
        $view->with([
            'date' => $date,
            'b737ChecklistItems' => $b737ChecklistItems,
            'q400ChecklistItems' => $q400ChecklistItems,
            'b737Aircraft' => $b737Aircraft,
            'q400Aircraft' => $q400Aircraft,
            'submissions' => $submissions,
            'fleets' => $fleets
        ]);
    }
}
