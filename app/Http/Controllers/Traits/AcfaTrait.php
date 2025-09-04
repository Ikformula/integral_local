<?php


namespace App\Http\Controllers\Traits;


use App\Models\Airline;
use App\Models\AirlineFare;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

trait AcfaTrait
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

        $dept_date = substr($params['departureDate'], 0,10);
        if (!empty($fares) && count($fares)) {
            AirlineFare::insert($fares);
            return "Fares Stored for " . $airline->name . " for {$params['depart_from_port']} to {$params['arrive_at_port']} on {$dept_date}. Checked at " . now()->toDateTimeString() . PHP_EOL;
        }

        return "No fares found for " . $airline->name . " for {$params['depart_from_port']} to {$params['arrive_at_port']} on {$dept_date}. Checked at " . now()->toDateTimeString() . PHP_EOL;
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

}
