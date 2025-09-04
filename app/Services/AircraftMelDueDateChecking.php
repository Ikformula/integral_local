<?php


namespace App\Services;


use App\Models\Aircraft;
use App\Models\AircraftStatusSubmission;
use Carbon\Carbon;

trait AircraftMelDueDateChecking
{
    public static function checkAllMel()
    {
        $amber_alert_days = getSettingValue('mel_due_amber_alert_days');
        $red_alert_days = getSettingValue('mel_due_red_alert_days');

        $date_fields['B-737'] = [20, 22, 24, 26];
        $date_fields['Q-400'] = [48, 50, 52, 54];
        $due_date_fields = array_merge($date_fields['B-737'], $date_fields['Q-400']);
        $aircrafts = Aircraft::all();

        // Find the most recent 'for_date' for the specified checklist and aircraft IDs
        $latestForDate = AircraftStatusSubmission::whereIn('checklist_id', $due_date_fields)
            ->whereIn('aircraft_id', $aircrafts->pluck('id')->toArray())
            ->max('for_date');

// Retrieve records that match the most recent 'for_date'
        $due_dates = AircraftStatusSubmission::whereIn('checklist_id', $due_date_fields)
            ->whereIn('aircraft_id', $aircrafts->pluck('id')->toArray())
            ->where('for_date', $latestForDate)
            ->get();

        $due_date_statuses = [];

        foreach ($aircrafts as $aircraft){
            foreach ($date_fields[$aircraft->fleet] as $due_date_field){
                $mel_due_date = $due_dates->where('checklist_id', $due_date_field)
                    ->where('aircraft_id', $aircraft->id)
                    ->first();
                $due_date_statuses[$aircraft->id][$due_date_field]['due_date'] = null;
                $due_date_statuses[$aircraft->id][$due_date_field]['days between'] = null;
                $due_date_statuses[$aircraft->id][$due_date_field]['is in the future'] = null;
                $due_date_statuses[$aircraft->id][$due_date_field]['colour'] = '';
                if($mel_due_date) {
                    $due_date_statuses[$aircraft->id][$due_date_field]['due_date'] = Carbon::parse($mel_due_date->item_value);
                    $due_date_statuses[$aircraft->id][$due_date_field]['days between'] = $due_date_statuses[$aircraft->id][$due_date_field]['due_date']->diffInDays(now(), true);
                    $due_date_statuses[$aircraft->id][$due_date_field]['is in the future'] = $due_date_statuses[$aircraft->id][$due_date_field]['due_date'] > now() ? true : false;

                    if(!$due_date_statuses[$aircraft->id][$due_date_field]['is in the future'] || ($due_date_statuses[$aircraft->id][$due_date_field]['is in the future'] && $due_date_statuses[$aircraft->id][$due_date_field]['days between'] <= $amber_alert_days)){
                        $due_date_statuses[$aircraft->id][$due_date_field]['colour'] = $due_date_statuses[$aircraft->id][$due_date_field]['days between'] <= $red_alert_days ? 'danger' : 'warning';
                    }
                }

            }
        }

        return $due_date_statuses;
    }
}
