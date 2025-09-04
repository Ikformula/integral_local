<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\AirlineFare;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\AcfaTrait;

class AcfaPublicController extends Controller
{
    use AcfaTrait;

    public function cronJobSingleAirline(Request $request)
    {
        $validated = $request->validate([
            'a_id' => ['required', 'numeric', 'exists:airlines,id']
        ]);

        $max_days = 10;
        $locations = $this->locations;

        $today = Carbon::today();
        // we are checking for each airline, route, and departure date, twice a day
        // In the morning and afternoon
//        dd(
//            [$today->startOfDay()->toDateTimeString(), $today->midDay()->toDateTimeString(), $today->endOfDay()->toDateTimeString()]
//        );

        $session_array = now()->hour < 12 ? [$today->startOfDay(), $today->midDay()] : [$today->midDay(), $today->endOfDay()];
//        dd($session_array);

        $last_fare = AirlineFare::where('airline_id', $validated['a_id'])
            ->whereBetween('created_at', $session_array)
            ->latest()
            ->first();

        dd($last_fare);

        $params['departureDate'] = isset($last_fare) ? $last_fare->departure_date->addDay() : now();
        if($params['departureDate']->diffInDays(now()) > $max_days){
            return [
              'Max number of future fares reached'
            ];
        }

        $params['airline_id'] = $request->a_id;
        $locations_temp = $locations;
        foreach ($locations as $location_code => $location) {
            array_shift($locations_temp);
            foreach ($locations_temp as $location_temp_code => $location_temp) {
                $params['depart_from_port'] = $location_code;
                $params['arrive_at_port'] = $location_temp_code;
                $params['direction'] = 'outbound';
                echo $this->processFaresRequest($request, $params) . '<br><hr><br>';

                $params['depart_from_port'] = $location_temp_code;
                $params['arrive_at_port'] = $location_code;
                $params['direction'] = 'inbound';
                echo $this->processFaresRequest($request, $params) . '<br><hr><br>';
            }
        }

        return '<br>done' . PHP_EOL;
    }

}
