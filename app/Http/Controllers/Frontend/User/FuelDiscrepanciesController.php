<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Aircraft;
use App\Models\DepartureArrivalLocation;
use App\Models\FuelRecords;
use App\Models\Pilot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FuelDiscrepanciesController extends Controller
{
    public function index(Request $request)
    {
        if($request->filled('date_range')){
            $date_broken = $this->dateBreaker($request->date_range);
            if($date_broken){
                $from_date = Carbon::parse($date_broken['from_date']);
                $to_date = Carbon::parse($date_broken['to_date']);
            }
        }else{
            $from_date = now()->subWeek();
            $to_date = now();
        }

        $pilot_id = $request->pilot_id;
        $aircraft_id = $request->aircraft_id;
        $departure_location_id = $request->departure_location_id;
        $arrival_location_id = $request->arrival_location_id;

        $fueling_reports = FuelRecords::whereBetween('flight_date', [$from_date, $to_date])
            ->when($pilot_id, function($query, $pilot_id){
                return $query->where('pilot_id', $pilot_id);
            })
            ->when($aircraft_id, function($query, $aircraft_id){
                return $query->where('aircraft_id', $aircraft_id);
            })
            ->when($departure_location_id, function($query, $departure_location_id){
                return $query->where('departure_location_id', $departure_location_id);
            })
            ->when($arrival_location_id, function($query, $arrival_location_id){
                return $query->where('arrival_location_id', $arrival_location_id);
            })
            ->latest()->get();
        return view('frontend.fuel-discrepancies.index')->with([
            'fueling_reports' => $fueling_reports,
            'pilots' => Pilot::all(),
            'aircrafts' => Aircraft::all(),
            'locations' => DepartureArrivalLocation::all(),
            'params' => $request->query(),
            'pilot' => $request->filled('pilot_id') ? Pilot::find($request->pilot_id) : null,
            'aircraft' => $request->filled('aircraft_id') ? Aircraft::find($request->aircraft_id) : null,
            'departure_location_id' => $request->filled('departure_location_id') ? DepartureArrivalLocation::find($request->departure_location_id) : null,
            'arrival_location_id' => $request->filled('arrival_location_id') ? DepartureArrivalLocation::find($request->arrival_location_id) : null,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
    }

    private function dateBreaker($date_range){
// Split the string using the "-" delimiter
        $dates = explode("-", $date_range);

// Trim any whitespace from the dates
        $from_date = trim($dates[0]);
        $to_date = trim($dates[1]);

// Check if the dates are valid
        if (!strtotime($from_date) || !strtotime($to_date)) {
            return false;
        } else {
            return ["from_date" => $from_date,
                "to_date" => $to_date];
        }
    }

    public function techLogDataEntry(){
        $pilots = Pilot::all();
        return view('frontend.fuel-discrepancies.tech-log-entry')->with([
            'pilots' => $pilots
        ]);
    }
}
