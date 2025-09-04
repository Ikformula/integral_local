<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class BSCWorkerController extends Controller
{
    /**
     * Get the recipients for each business area.
     */
    private function BSCEmailRecipients()
    {
        return [
            1 => [
                'to' => ['tochukwu.echesi@arikair.com', 'idaresit.essien@arikair.com'],
                'cc' => ['ijeoma.ike-okereke@arikair.com'],
            ],
            2 => [
                'to' => ['najeeb.raji@arikair.com', 'theodora.ijeh@arikair.com'],
                'cc' => ['rasheed.lawal@arikair.com'],
            ],
            3 => [
                'to' => ['wasiu.okedeyi@arikair.com', 'smith.jamaho@arikair.com'],
                'cc' => ['augustine.addy-lamptey@arikair.com'],
            ],
            4 => [
                'to' => ['prince.ahuchaogu@arikair.com', 'babajide.adebomi@arikair.com'],
                'cc' => ['henry.ejiogu@arikair.com'],
            ],
            5 => [
                'to' => ['prince.ahuchaogu@arikair.com', 'uchechukwu.ojukwu@arikair.com', 'babajide.adebomi@arikair.com'],
                'cc' => ['henry.ejiogu@arikair.com'],
            ],
            6 => [
                'to' => ['prince.ahuchaogu@arikair.com', 'samson.oniye@arikair.com', 'babajide.adebomi@arikair.com'],
                'cc' => [],
            ],
            7 => [
                'to' => ['ifeoma.omeogu@arikair.com', 'prince.ahuchaogu@arikair.com', 'babajide.adebomi@arikair.com'],
                'cc' => ['henry.ejiogu@arikair.com'],
            ],
            8 => [
                'to' => ['gafar.sokunle@arikair.com', 'abiola.ola-adigun@arikair.com'],
                'cc' => ['jonathan.sani@arikair.com'],
            ],
        ];
    }

    /**
     * Send reminder emails with the missing data for each business area.
     */
    public function sendReminderEmails()
    {
        // Get the start of the year for current year
        $currentYear = Carbon::now()->year;
        $startOfYear = Carbon::create($currentYear, 1, 1);

        // Get all week ranges for the current year
        $missingEntries = DB::table('week_ranges')
            ->leftJoin('data_points', function ($join) {
                $join->on('week_ranges.id', '=', 'data_points.week_range_id');
            })
            ->whereNull('data_points.id') // No data point exists
            ->where('week_ranges.in_year', '=', $currentYear) // Only current year's weeks
            ->get(['week_ranges.*', 'data_points.business_area_id'])
            ->groupBy('data_points.business_area_id'); // Group by business area

        dd($missingEntries);
        // Recipients list for business areas
        $emailRecipients = $this->BSCEmailRecipients();

        foreach ($missingEntries as $businessAreaId => $missingWeeks) {
            if (isset($emailRecipients[$businessAreaId])) {
                // Sort weeks by most recent
                $sortedWeeks = $missingWeeks->sortByDesc('from_date');

                // Prepare week details for the email
                $weekDetails = $sortedWeeks->map(function ($week) {
                    $weekNumber = $week->week_number;
                    $weekRangeId = $week->id;
                    $weekRangeLink = url("/data-entry/$weekRangeId"); // Assuming data entry route

                    return "Week $weekNumber: <a href='$weekRangeLink'>$weekRangeLink</a>";
                })->implode('<br>');

                // Send the email to the business area recipients
                $this->sendEmail($businessAreaId, $weekDetails);
            }
        }
    }

    /**
     * Helper function to send the email to the respective recipients.
     */
    private function sendEmail($businessAreaId, $weekDetails)
    {
        return [
            $businessAreaId,
            $weekDetails
        ];

        $recipients = $this->BSCEmailRecipients()[$businessAreaId];

        Mail::send('emails.reminder', ['weekDetails' => $weekDetails], function ($message) use ($recipients) {
            $message->to($recipients['to'])
                ->cc($recipients['cc'])
                ->subject('Weekly Reminder: Missing Data for Business Area');
        });
    }
}

