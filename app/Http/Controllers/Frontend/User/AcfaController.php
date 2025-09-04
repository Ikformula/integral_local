<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use Illuminate\Http\Request;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

//use Illuminate\Support\Facades\Http;
use App\Models\AirlineFare;
use Carbon\Carbon;

class AcfaController extends Controller
{

    protected $locations = [
        'LOS' => 'Lagos',
        'ABV' => 'Abuja',
        'ABB' => 'Asaba',
        'BNI' => 'Benin City',
        'JOS' => 'Jos',
        'PHC' => 'Port Harcourt Int\'l',
        'QRW' => 'Warri'
    ];

    public function index()
    {
        $airlines = Airline::all();
        $locations = $this->locations;
        return view('frontend.acfa.index', compact('airlines', 'locations'));
    }

    public function processAcfaAirlines(Request $request)
    {
        $validated = $request->validate([
            'depart_from_port' => ['required', 'string', 'size:3', 'different:arrive_at_port'],
            'arrive_at_port' => ['required', 'string', 'size:3', 'different:depart_from_port'],
            'departureDate' => ['required', 'date', 'after:yesterday']
        ]);

        $params['departureDate'] = $request->departureDate;
        if (!count($request->airline_ids))
            return back()->withErrors('No airline selected');

        $params = $request->all();
        $params['direction'] = 'outbound';
        foreach ($request->airline_ids as $airline_id) {
            $params['airline_id'] = $airline_id;
            echo $this->processFaresRequest($request, $params) . PHP_EOL;
        }

        $params['depart_from_port'] = $request->arrive_at_port;
        $params['arrive_at_port'] = $request->depart_from_port;
        $params['direction'] = 'inbound';

        foreach ($request->airline_ids as $airline_id) {
            $params['airline_id'] = $airline_id;
            echo $this->processFaresRequest($request, $params) . PHP_EOL;
        }

        return '<br>done' . PHP_EOL;
    }

    public function bgProcessingACFA(Request $request)
    {
        $number_of_days = 10;
//        $airlines = Airline::all();
        $airlines = Airline::where('id', 1)->get();
        $locations = $this->locations;

//        for ($d = 1; $d <= $number_of_days; $d++) {
//            $params['departureDate'] = now()->addDays($d);
//        $latest_checking_date = AirlineFare::latest()->first()->departure_date;
//            $params['departureDate'] = $latest_checking_date->addDay();
            $params['departureDate'] = '2024-12-06';
            $locations_temp = $locations;
            foreach ($locations as $location_code => $location) {
                array_shift($locations_temp);
                foreach ($locations_temp as $location_temp_code => $location_temp) {

                    foreach ($airlines as $airline) {
                        $params['airline_id'] = $airline->id;

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
            }
//        }
        return '<br>done' . PHP_EOL;
    }


    public function airRmReqReport(Request $request)
    {
        $checked_at = $request->input('checked_at', now());
        $fares = AirlineFare::whereBetween('checked_at', [$checked_at->startOfDay(), $checked_at->endOfDay()])->get();

        return view('frontend.acfa.ten-days-report', compact('fares'));
    }

    public function processSingleAirlineFareRequest(Request $request)
    {
        // Predefined variables
        $depart_from_port = $request->depart_from_port;
        $arrive_at_port = $request->arrive_at_port;
        $direction = $request->direction;
        $now = Carbon::now();
        $airline_id = $request->airline_id;
    }


    public function processFaresRequest(Request $request, $params)
    {
        $airline = Airline::find($params['airline_id']);
        if (!$airline)
            return 'airline not found';

        if (!array_key_exists('checked_at', $params))
            $params['checked_at'] = now();

        $existing_fares_count = AirlineFare::where('depart_from_port', $params['depart_from_port'])
            ->where('arrive_at_port', $params['arrive_at_port'])
            ->where('direction', $params['direction'])
            ->whereBetween('checked_at', [$params['checked_at']->startOfDay(), $params['checked_at']->endOfDay()])
            ->count();

        if ($existing_fares_count)
            return "Fares already retrieved";

//        return arrayToHtmlTable($params);

        if ($airline->uses_new_crane_ibe == 1) {
            // Crane new IBE airlines -- Arik, Ibom, Air Peace
            $params['ibe_url'] = $airline->url_format . '/ibe/availability?tripType=ONE_WAY&returnDate=&passengerQuantities%5B0%5D%5BpassengerType%5D=ADULT&passengerQuantities%5B0%5D%5BpassengerSubType%5D=&passengerQuantities%5B0%5D%5Bquantity%5D=1&passengerQuantities%5B1%5D%5BpassengerType%5D=CHILD&passengerQuantities%5B1%5D%5BpassengerSubType%5D=&passengerQuantities%5B1%5D%5Bquantity%5D=0&passengerQuantities%5B2%5D%5BpassengerType%5D=INFANT&passengerQuantities%5B2%5D%5BpassengerSubType%5D=&passengerQuantities%5B2%5D%5Bquantity%5D=0&currency=&cabinClass=&lang=EN&nationality=&promoCode=&accountCode=&affiliateCode=&clickId=&withCalendar=&isMobileCalendar=&market=&isFFPoint=&_ga=&depPort=' . $params['depart_from_port'] . '&arrPort=' . $params['arrive_at_port'] . '&departureDate=' . Carbon::parse($params['departureDate'])->format('d.m.Y');

            switch ($airline->id) {
                case 1:
                    $fares = $this->getForArikAir($request, $params);
                    break;
                case 2:
                    $fares = $this->getForIbomAir($request, $params);
                    break;
                case 3:
                    $fares = $this->getForAirPeace($request, $params);
                    break;
                default:
                    return 'Airline not found';
            }

//        } else if (in_array($params['airline_id'], [3, 4])) {
//            // Crane old IBE airlines -- Aero
//            switch ($airline->id) {
//                case 3:
//                    $fares = $this->getForAero($request, $params);
//                    break;
//                default:
//                    return 'Airline not found';
//            }
        }

        if (!empty($fares) && count($fares)) {
            AirlineFare::insert($fares);
            return "Fares Stored for " . $airline->name . " for {$params['depart_from_port']} to {$params['arrive_at_port']} on {$params['departureDate']}. Checked at " . now()->toDateTimeString() . PHP_EOL;
        }

        return "No fares found for " . $airline->name . " for {$params['depart_from_port']} to {$params['arrive_at_port']} on {$params['departureDate']}. Checked at " . now()->toDateTimeString() . PHP_EOL;
    }

    public function getForArikAir(Request $request, $params)
    {
        $now = now();

// Send the request and initialize Crawler
        $client = new HttpBrowser(HttpClient::create());
        $crawler = $client->request('GET', $params['ibe_url']);

        $faresToInsert = [];
        // Loop through each selection item
        $crawler->filter('div.selection-item')->each(function (Crawler $item) use ($params, $now, &$faresToInsert) {
            // Extract details
            $flight_num = $item->filter('div.flight-no span')->first()->text();
            $departure_time = $item->filter('div.desktop-route-block span.time')->first()->text();
            $departure_date = Carbon::parse($item->filter('div.desktop-route-block span.date')->first()->text());


            $classes = ['ECONOMY', 'PREMIUM', 'BUSINESS'];
            $counter = 0;
            $amounts = $item->filter('div.desktop-fare-block div.fare-item')->each(function (Crawler $sub_item) use ($params, $departure_time, $departure_date, &$counter, $now, &$faresToInsert, &$classes, $flight_num) {


                    try {
                        $price = $sub_item->filter('span.price')->first()->text();
                    } catch (\InvalidArgumentException $e) {
                        try{
                            $price = $sub_item->filter('span.price-best-offer')->first()->text();
                        } catch (\InvalidArgumentException $e){
                            $price = null;
                        }
                    }

                    if ($price) {
                        $price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $faresToInsert[] = [
                            'amount' => $price,
                            'departure_time' => $departure_time,
                            'departure_date' => $departure_date,
                            'class_name' => $classes[$counter],
                            'depart_from_port' => $params['depart_from_port'],
                            'arrive_at_port' => $params['arrive_at_port'],
                            'direction' => $params['direction'],
                            'airline_id' => $params['airline_id'],
                            'flight_number' => $flight_num,
                            'checked_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                $counter++;
            });

        });

        return $faresToInsert;
    }

    public function getForAirPeace(Request $request, $params)
    {
        $now = now();

// Send the request and initialize Crawler
        $client = new HttpBrowser(HttpClient::create());
        $crawler = $client->request('GET', $params['ibe_url']);

        $faresToInsert = [];
        // Loop through each selection item
        $crawler->filter('div.selection-item')->each(function (Crawler $item) use ($params, $now, &$faresToInsert) {
            // Extract details
            $flight_num = $item->filter('div.flight-no span')->first()->text();
            $departure_time = $item->filter('div.desktop-route-block span.time')->first()->text();
            $departure_date = Carbon::parse($item->filter('div.desktop-route-block span.date')->first()->text());

            $counter = 0;
            $amounts = $item->filter('div.desktop-fare-block div.branded-fare-item')->each(function (Crawler $sub_item) use ($params, $departure_time, $departure_date, &$counter, $now, &$faresToInsert, $flight_num) {
                $counter++;
                if ($counter == 1) {
                    try {
                        $economy_price = $sub_item->filter('span.currency')->first()->text();
                    } catch (\InvalidArgumentException $e) {
                        try{
                            $economy_price = $sub_item->filter('span.currency-best-offer')->first()->text();
                        } catch (\InvalidArgumentException $e){
                            $economy_price = null;
                        }
                    }

                    if ($economy_price) {
                        $economy_price = filter_var($economy_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $faresToInsert[] = [
                            'amount' => $economy_price,
                            'departure_time' => $departure_time,
                            'departure_date' => $departure_date,
                            'class_name' => 'ECONOMY',
                            'depart_from_port' => $params['depart_from_port'],
                            'arrive_at_port' => $params['arrive_at_port'],
                            'direction' => $params['direction'],
                            'airline_id' => $params['airline_id'],
                            'flight_number' => $flight_num,
                            'checked_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                } else if ($counter == 2) {
                    try {
                        $business_price = $sub_item->filter('span.currency')->first()->text();
                    } catch (\InvalidArgumentException $e) {
                        try{
                            $business_price = $sub_item->filter('span.currency-best-offer')->first()->text();
                        } catch (\InvalidArgumentException $e){
                            $business_price = null;
                        }
                    }
                    if ($business_price) {
                        $business_price = filter_var($business_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $faresToInsert[] = [
                            'amount' => $business_price,
                            'departure_time' => $departure_time,
                            'departure_date' => $departure_date,
                            'class_name' => 'ECONOMY',
                            'depart_from_port' => $params['depart_from_port'],
                            'arrive_at_port' => $params['arrive_at_port'],
                            'direction' => $params['direction'],
                            'airline_id' => $params['airline_id'],
                            'flight_number' => $flight_num,
                            'checked_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                }
            });
        });

        return $faresToInsert;
    }

    public function getForIbomAir(Request $request, $params)
    {
        $now = now();
// Send the request and initialize Crawler
        $client = new HttpBrowser(HttpClient::create());
        try {
            $crawler = $client->request('GET', $params['ibe_url']);
        } catch (\Throwable $e) {
            // Handle any errors
//            report($e);
            return [];
        }

        $faresToInsert = [];
        // Loop through each selection item
        $crawler->filter('div.selection-item')->each(function (Crawler $item) use ($params, $now, &$faresToInsert) {
            // Extract details
            $flight_num = $item->filter('div.flight-no span')->first()->text();
            $departure_time = $item->filter('div.desktop-route-block span.time')->first()->text();
            $departure_date = Carbon::parse($item->filter('div.desktop-route-block span.date')->first()->text());

            $counter = 0;
            $amounts = $item->filter('div.desktop-fare-block div.branded-fare-item')->each(function (Crawler $sub_item) use ($params, $departure_time, $departure_date, &$counter, $now, &$faresToInsert, $flight_num) {
                $counter++;
                if ($counter == 1) {
                    try {
                        $economy_price = $sub_item->filter('span.currency')->first()->text();
                    } catch (\InvalidArgumentException $e) {
                        $economy_price = null;
                        try{
                            $economy_price = $sub_item->filter('span.currency-best-offer')->first()->text();
                        } catch (\InvalidArgumentException $e){
                            $economy_price = null;
                        }
                    }
                    if ($economy_price) {
                        $economy_price = filter_var($economy_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $faresToInsert[] = [
                            'amount' => $economy_price,
                            'departure_time' => $departure_time,
                            'departure_date' => $departure_date,
                            'class_name' => 'ECONOMY',
                            'depart_from_port' => $params['depart_from_port'],
                            'arrive_at_port' => $params['arrive_at_port'],
                            'direction' => $params['direction'],
                            'airline_id' => $params['airline_id'],
                            'flight_number' => $flight_num,
                            'checked_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                } else {
                    try {
                        $business_price = $sub_item->filter('span.currency')->first()->text();
                    } catch (\InvalidArgumentException $e) {
                        try{
                            $business_price = $sub_item->filter('span.currency-best-offer')->first()->text();
                        } catch (\InvalidArgumentException $e){
                            $business_price = null;
                        }
                    }
                    if ($business_price) {
                        $business_price = filter_var($business_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $faresToInsert[] = [
                            'amount' => $business_price,
                            'departure_time' => $departure_time,
                            'departure_date' => $departure_date,
                            'class_name' => 'ECONOMY',
                            'depart_from_port' => $params['depart_from_port'],
                            'arrive_at_port' => $params['arrive_at_port'],
                            'direction' => $params['direction'],
                            'airline_id' => $params['airline_id'],
                            'flight_number' => $flight_num,
                            'checked_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                }
            });
        });

        return $faresToInsert;
    }

    public function getForAero(Request $request, $params)
    {
        $now = now();
// Send the request and initialize Crawler
        $client = new HttpBrowser(HttpClient::create());

        try {
            $crawler = $client->request('GET', $params['ibe_url']);
        } catch (\Throwable $e) {
            // Handle any errors
//            report($e);
            return [];
        }

        $faresToInsert = [];
        $crawler->filter('div.flight-details')->each(function (Crawler $item) use ($params, $now, &$faresToInsert) {
            // Extract details
            $flight_num = $item->filter('div.flight-modal-body span')->first()->text();
            $departure_time = $item->filter('div.desktop-route-block span.time')->first()->text();
            $departure_date = Carbon::parse($item->filter('div.desktop-route-block span.date')->first()->text());

            $counter = 0;
            $amounts = $item->filter('div.desktop-fare-block div.branded-fare-item')->each(function (Crawler $sub_item) use ($params, $departure_time, $departure_date, &$counter, $now, &$faresToInsert, $flight_num) {
                $counter++;
                if ($counter == 1) {
                    try {
                        $economy_price = $sub_item->filter('span.currency')->first()->text();
                    } catch (\InvalidArgumentException $e) {
                        $economy_price = null;
                        try{
                            $economy_price = $sub_item->filter('span.currency-best-offer')->first()->text();
                        } catch (\InvalidArgumentException $e){
                            $economy_price = null;
                        }
                    }
                    if ($economy_price) {
                        $economy_price = filter_var($economy_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $faresToInsert[] = [
                            'amount' => $economy_price,
                            'departure_time' => $departure_time,
                            'departure_date' => $departure_date,
                            'class_name' => 'ECONOMY',
                            'depart_from_port' => $params['depart_from_port'],
                            'arrive_at_port' => $params['arrive_at_port'],
                            'direction' => $params['direction'],
                            'airline_id' => $params['airline_id'],
                            'flight_number' => $flight_num,
                            'checked_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                } else {
                    try {
                        $business_price = $sub_item->filter('span.currency')->first()->text();
                    } catch (\InvalidArgumentException $e) {
                        try{
                            $business_price = $sub_item->filter('span.currency-best-offer')->first()->text();
                        } catch (\InvalidArgumentException $e){
                            $business_price = null;
                        }
                    }
                    if ($business_price) {
                        $business_price = filter_var($business_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $faresToInsert[] = [
                            'amount' => $business_price,
                            'departure_time' => $departure_time,
                            'departure_date' => $departure_date,
                            'class_name' => 'ECONOMY',
                            'depart_from_port' => $params['depart_from_port'],
                            'arrive_at_port' => $params['arrive_at_port'],
                            'direction' => $params['direction'],
                            'airline_id' => $params['airline_id'],
                            'flight_number' => $flight_num,
                            'checked_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                }
            });
        });

        return $faresToInsert;
    }

    public function trial()
    {
        // Predefined variables
        $depart_from_port = 'LOS';
        $arrive_at_port = 'ABV';
        $direction = 'outbound';
        $now = Carbon::now();
        $airline_id = 1; // Arik Air

        // URL of the page to scrape
        $ibe_url = 'https://arikair.crane.aero/ibe/availability?tripType=ONE_WAY&depPort=LOS&arrPort=ABV&departureDate=07.11.2024&returnDate=&passengerQuantities%5B0%5D%5BpassengerType%5D=ADULT&passengerQuantities%5B0%5D%5BpassengerSubType%5D=&passengerQuantities%5B0%5D%5Bquantity%5D=1&passengerQuantities%5B1%5D%5BpassengerType%5D=CHILD&passengerQuantities%5B1%5D%5BpassengerSubType%5D=&passengerQuantities%5B1%5D%5Bquantity%5D=0&passengerQuantities%5B2%5D%5BpassengerType%5D=INFANT&passengerQuantities%5B2%5D%5BpassengerSubType%5D=&passengerQuantities%5B2%5D%5Bquantity%5D=0&currency=&cabinClass=&lang=EN&nationality=&promoCode=&accountCode=&affiliateCode=&clickId=&withCalendar=&isMobileCalendar=&market=&isFFPoint=&_ga=';

        $ibom_air = 'https://book-ibomair.crane.aero/ibe/availability?tripType=ONE_WAY&depPort=LOS&arrPort=ABV&departureDate=04.11.2024&returnDate=&passengerQuantities%5B0%5D%5BpassengerType%5D=ADULT&passengerQuantities%5B0%5D%5BpassengerSubType%5D=&passengerQuantities%5B0%5D%5Bquantity%5D=1&passengerQuantities%5B1%5D%5BpassengerType%5D=CHILD&passengerQuantities%5B1%5D%5BpassengerSubType%5D=&passengerQuantities%5B1%5D%5Bquantity%5D=0&passengerQuantities%5B2%5D%5BpassengerType%5D=INFANT&passengerQuantities%5B2%5D%5BpassengerSubType%5D=&passengerQuantities%5B2%5D%5Bquantity%5D=0&currency=NGN&cabinClass=&lang=EN&nationality=&promoCode=&accountCode=&affiliateCode=&clickId=&withCalendar=&isMobileCalendar=&market=&isFFPoint=';

        // Send the request and initialize Crawler
        $client = new HttpBrowser(HttpClient::create());
//        $response = Http::get($ibe_url);
//        $crawler = new Crawler($response->body());
        $crawler = $client->request('GET', $ibe_url);

        // Loop through each selection item
        $crawler->filter('div.selection-item')->each(function (Crawler $item) use ($depart_from_port, $arrive_at_port, $direction, $now) {
            // Extract details
            $departure_time = $item->filter('div.desktop-route-block span.time')->first()->text();
            $departure_date = Carbon::parse($item->filter('div.desktop-route-block span.date')->first()->text());
            $economy_price = $item->filter('div.desktop-fare-block div.cabin-name-ECONOMY span.price-best-offer, div.cabin-name-ECONOMY span.price')->first()->text();
            $business_price = $item->filter('div.desktop-fare-block div.cabin-name-BUSINESS span.price')->first()->text();

            // Convert prices to numeric format
            $economy_price = filter_var($economy_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $business_price = filter_var($business_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            // Insert Economy Fare
            AirlineFare::create([
                'amount' => $economy_price,
                'departure_time' => $departure_time,
                'departure_date' => $departure_date,
                'class_name' => 'ECONOMY',
                'depart_from_port' => $depart_from_port,
                'arrive_at_port' => $arrive_at_port,
                'direction' => $direction,
                'checked_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Insert Business Fare
            AirlineFare::create([
                'amount' => $business_price,
                'departure_time' => $departure_time,
                'departure_date' => $departure_date,
                'class_name' => 'BUSINESS',
                'depart_from_port' => $depart_from_port,
                'arrive_at_port' => $arrive_at_port,
                'direction' => $direction,
                'checked_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        return response()->json(['message' => 'Fares data inserted successfully']);
    }

}
