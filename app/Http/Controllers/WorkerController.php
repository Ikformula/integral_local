<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\AirlineFare;
use App\Models\BusinessArea;
use App\Models\DataPoint;
use App\Models\StaffMember;
use App\Models\WeekRange;
use App\Services\WeekRangeService;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkerController extends Controller
{
    use OutgoingMessagesTrait;

    protected $locations = [
        'LOS' => 'Lagos',
        'ABV' => 'Abuja',
        'ABB' => 'Asaba',
        'BNI' => 'Benin City',
        'JOS' => 'Jos',
        'PHC' => 'Port Harcourt Int\'l',
        'QRW' => 'Warri'
    ];


    public function checkUnfilledBusinessScoreCards(Request $request)
    {
        $current_week = WeekRangeService::currentWeekChecker();
        $week_range = $previous_week = WeekRangeService::weekBeforeAWeek($current_week);
       $business_areas = BusinessArea::all();
        // $business_areas = BusinessArea::where('id', '!=', 9)->get();
//        $week_ranges = WeekRange::orderBy('week_number', 'DESC')->where('id', '!=', $current_week->id)->take(2)->get();

        $unfilled_business_score_areas = $data_points = [];
        foreach ($business_areas as $business_area){
            $data_points[$business_area->id] = DataPoint::where('business_area_id', $business_area->id)
                ->where('time_title', 'week_range_id')
                ->pluck('week_range_id')->toArray();
//            foreach($week_ranges as $week_range){
            if(!in_array($week_range->id, $data_points[$business_area->id])){
                $unfilled_business_score_areas[$business_area->id][] = $week_range;
            }
//            }

            if(isset($unfilled_business_score_areas[$business_area->id]) && count($unfilled_business_score_areas[$business_area->id]))
                $this->sendEmail($business_area->name, $business_area->id, $unfilled_business_score_areas[$business_area->id]);
        }
    }

    private function sendEmail($business_area_name, $business_area_id, $unfilled_weeks){
        $num_weeks = count($unfilled_weeks);
        unset($data);
        $data['subject'] = "Unfilled B.S.C. - ".$business_area_name;
        $data['greeting'] = "Dear Team";
        $data['line'][] = "Please note that for ".$business_area_name." Business Score Card the ".Str::plural('week', $num_weeks)." listed below do not have data entered yet:";
        foreach ($unfilled_weeks as $week){
            echo '<br>'.$week->week_number.': id: '.$week->id.'<br>';
            $data['formatted_line'][] = '<a href="'.route('frontend.business_goals.add_report') .'?business_area_id='. $business_area_id.'&week_range_id='.$week->id.'">Week '.  $week->week_number  . ' : '.  $week->from_day  .  ' - '.  $week->to_day.'</a>';
        }
        $data['formatted_line'][] = " ";
        $data['formatted_line'][] = "Click the ".Str::plural('link', $num_weeks)." above to enter the missing data.";
        $data['action_url'] = route('frontend.business_goals.add_report') ."?business_area_id=". $business_area_id;
        $data['action_text'] = "Data Entry";
        $recipients = $this->BSCEmailRecipients()[$business_area_id];
        $data['to'] = $recipients['to'];
        $data['cc'] = $recipients['cc'];
        $data['to_name'] = 'Team';

        $this->storeMessage($data, null);
        echo $business_area_id.': '.$business_area_name.'<br>';
    }

    private function BSCEmailRecipients()
    {
        return [
            1 => [
                'to' => ['tochukwu.echesi@arikair.com', 'idaresit.essien@arikair.com'],
                'cc' => ['ijeoma.ike-okereke@arikair.com', 'adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
            2 => [
                'to' => ['najeeb.raji@arikair.com', 'theodora.ijeh@arikair.com', 'omobolanle.akande@arikair.com'],
                'cc' => ['rasheed.lawal@arikair.com', 'adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
            3 => [
                'to' => ['wasiu.okedeyi@arikair.com', 'smith.jamaho@arikair.com'],
                'cc' => ['babajide.oni@arikair.com', 'augustine.addy-lamptey@arikair.com', 'adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
            4 => [
                'to' => ['prince.ahuchaogu@arikair.com', 'babajide.adebomi@arikair.com'],
                'cc' => ['ailemen.arumemi-johnson@arikair.com', 'henry.ejiogu@arikair.com', 'adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
            5 => [
                'to' => ['prince.ahuchaogu@arikair.com', 'uchechukwu.ojukwu@arikair.com', 'babajide.adebomi@arikair.com'],
                'cc' => ['ailemen.arumemi-johnson@arikair.com', 'henry.ejiogu@arikair.com', 'adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
            6 => [
                'to' => ['prince.ahuchaogu@arikair.com', 'babajide.adebomi@arikair.com'],
                'cc' => ['babajide.oni@arikair.com', 'augustine.addy-lamptey@arikair.com', 'kevin.erinjeri@arikair.com', 'adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
            7 => [
                'to' => ['ifeoma.omeogu@arikair.com', 'prince.ahuchaogu@arikair.com', 'babajide.adebomi@arikair.com'],
                'cc' => ['ailemen.arumemi-johnson@arikair.com', 'henry.ejiogu@arikair.com', 'adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
            8 => [
                'to' => ['gafar.sokunle@arikair.com', 'abiola.ola-adigun@arikair.com'],
                'cc' => ['adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
//            9 => [
//                'to' => ['busayo.kalejaiye@arikair.com', 'rhoda.fasanmi@arikair.com', 'david.agbogun@arikair.com'],
//                'cc' => ['adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'catherine.allamogu@arikair.com', 'ithelpdesk@arikair.com', 'ikechukwu.asuquo@arikair.com'],
//            ],
            9 => [
                'to' => ['kevin.erinjeri@arikair.com'],
                'cc' => ['john.nomnor@arikair.com', 'catherine.allamogu@arikair.com'],
            ],
            10 => [
                'to' => ['blessing.akpomedaye@arikair.com'],
                'cc' => ['stella.edomwonyi@arikair.com', 'adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
            11 => [
                'to' => ['emmanuel.balami@arikair.com', 'paul.roomes@arikair.com', 'kulvinder.singh@arikair.com', 'james.onoak@arikair.com', 'ibrahim.adeyale@arikair.com'],
                'cc' => ['adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com', 'john.nomnor@arikair.com', 'ikechukwu.asuquo@arikair.com'],
            ],
        ];
    }
    private function BSCEmailRecipients5()
    {
        return [
            1 => [
                'to' => ['ikechukwu.asuquo@arikair.com', 'arikair@mailinator.com'],
                'cc' => ['asuquobartholomewi@gmail.com'],
            ],
            2 => [
                'to' => ['ikechukwu.asuquo@arikair.com', 'arikair@mailinator.com'],
                'cc' => ['asuquobartholomewi@gmail.com'],
            ],
            3 => [
                'to' => ['ikechukwu.asuquo@arikair.com', 'arikair@mailinator.com'],
                'cc' => ['asuquobartholomewi@gmail.com'],
            ],
            4 => [
                'to' => ['ikechukwu.asuquo@arikair.com', 'arikair@mailinator.com'],
                'cc' => ['asuquobartholomewi@gmail.com'],
            ],
            5 => [
                'to' => ['ikechukwu.asuquo@arikair.com', 'arikair@mailinator.com'],
                'cc' => ['asuquobartholomewi@gmail.com'],
            ],
            6 => [
                'to' => ['ikechukwu.asuquo@arikair.com', 'arikair@mailinator.com'],
                'cc' => ['asuquobartholomewi@gmail.com'],
            ],
            7 => [
                'to' => ['ikechukwu.asuquo@arikair.com', 'arikair@mailinator.com'],
                'cc' => ['asuquobartholomewi@gmail.com'],
            ],
            8 => [
                'to' => ['ikechukwu.asuquo@arikair.com', 'arikair@mailinator.com'],
                'cc' => ['asuquobartholomewi@gmail.com'],
            ],
        ];
    }

    private function hods()
    {
        $arr = [
            ['ijeoma.ike-okereke@arikair.com', 'Ma', [1], 'HR'],
            ['rasheed.lawal@arikair.com', 'Sir', [2], 'Ground Ops'],
            ['augustine.addy-lamptey@arikair.com', 'Sir', [3, 6], 'Flight Ops'],
            ['ailemen.arumemi-johnson@arikair.com', 'Sir', [4, 5, 7], 'Commercial'],
            ['john.nomnor@arikair.com', 'Sir', [9], 'I.T.'],
            ['stella.edomwonyi@arikair.com', 'Ma', [10], 'Internal Control'],
            ['emmanuel.balami@arikair.com', 'Sir', [11], 'Technical - Aircraft Status'],
        ];

        return $arr;
    }

    public function HODsReminder()
    {
        $arr = $this->hods();
        $business_areas = BusinessArea::all();
        foreach ($arr as $section){
            unset($data);
            $data['subject'] = "{$section[3]} Business Score Card Reports";
            $data['greeting'] = "Dear ".$section[1];
            $data['line'][] = "Kindly find below the link for accessing your Business Score Card Reports for presenting {$section[3]} reports during Senior Management Meetings.";
            $data['line'][] = "The most recently concluded week's report should be seen there as well as the daily entries (if any) beneath that.";
            if(count($section[2]) > 1){
                $data['formatted_line'][] = '<a href="'.route('frontend.business_goals.multi.business.areas').'">Business Areas</a>';
            } else {

                foreach ($section[2] as $business_area_id) {
                    $business_area = $business_areas->where('id', $business_area_id)->first();
                    $data['formatted_line'][] = '<a href="' . route('frontend.business_goals.single.quadrant') . '?business_area_id=' . $business_area_id . '">' . $business_area->name . '</a>';
                }
            }
//            $data['action_url'] = route('frontend.business_goals.add_report') ."?business_area_id=". $business_area_id;
//            $data['action_text'] = "Data Entry";
            $recipients = $this->BSCEmailRecipients()[$business_area_id];
            $data['to'] = $section[0];
            if($section[3] == 'Flight Ops'){
                $data['to'] = [$section[0], 'babajide.oni@arikair.com'];
            }elseif ($section[3] == 'Internal Control'){
                $data['to'] = [$section[0], 'rasak.audu@arikair.com'];
            }

            if($section[3] == 'I.T.'){
                $data['cc'] = ['kevin.erinjeri@arikair.com', 'ikechukwu.asuquo@arikair.com'];
            }else {
                $data['cc'] = ['kevin.erinjeri@arikair.com', 'catherine.allamogu@arikair.com', 'ikechukwu.asuquo@arikair.com'];
            }
            $staff = StaffMember::where('email', $section[0])->first();
            if($staff){
                $data['to_name'] = $staff->full_name;
            } else {
                $names = explode('.', $section[0]);
                $full_name = '';
                foreach ($names as $name){
                    $full_name .= ucfirst($name).' ';
                }
                $data['to_name'] = $full_name;
            }

            echo 'Stored for '.$data['to_name'].'<br>';
            $this->storeMessage($data, null);
        }

//        For the CEO
        unset($data);
        $data['subject'] = "CEO Business Score Card Reports";
        $data['greeting'] = "Dear Sir";
        $data['line'][] = "Kindly find below the link for accessing the Business Score Card Reports presented during Senior Management Meetings.";
        $data['line'][] = "Reports from the different units for the most recently concluded week should be seen there as well as the daily entries (if any) beneath each.";

        $data['action_url'] = route('frontend.business_goals.multi.business.areas');
        $data['action_text'] = "CEO B.S.C. Reports";
        $data['to'] = "roy.ilegbodu@arikair.com";
        $data['cc'] = ['adetokunbo.adekunbi@arikair.com', 'jonathan.sani@arikair.com', 'kevin.erinjeri@arikair.com'];
        $data['to_name'] = 'Captain Roy Ilegbodu';

        echo 'Stored for '.$data['to_name'].'<br>';
        $this->storeMessage($data, null);
    }

    public function HODsLinkText()
    {
        $arr = $this->hods();
        $business_areas = BusinessArea::all();
        foreach ($arr as $section){
            unset($data);
            $data['subject'] = "{$section[3]} Business Score Card Reports";
            $data['greeting'] = "Dear ".$section[1];
            $data['line'][] = "Kindly find below relevant link for accessing your Business Score Card Reports for presenting {$section[3]} reports during Senior Management Meetings.";
            $data['line'][] = "The most recently concluded week's report should be seen there as well as the daily entries beneath that.";

            if(count($section[2]) > 1){
                $data['formatted_line'][] = '<a href="'.route('frontend.business_goals.multi.business.areas').'">Business Areas</a>';
            } else {

                foreach ($section[2] as $business_area_id) {
                    $business_area = $business_areas->where('id', $business_area_id)->first();
                    $data['formatted_line'][] = '<a href="' . route('frontend.business_goals.single.quadrant') . '?business_area_id=' . $business_area_id . '">' . $business_area->name . '</a>';
                }
            }

            $data['formatted_line'][] = 'Recipient: '.$section[0];
            $data['to'] = 'kevin.erinjeri@arikair.com';
            $data['cc'] = ['ikechukwu.asuquo@arikair.com'];
            $staff = StaffMember::where('email', $section[0])->first();
            if($staff){
                $data['to_name'] = $staff->full_name;
            } else {
                $names = explode('.', $section[0]);
                $full_name = '';
                foreach ($names as $name){
                    $full_name .= ucfirst($name).' ';
                }
                $data['to_name'] = $full_name;
            }
            $data['formatted_line'][] = "Recipient's Name: ".$data['to_name'];

            echo 'Stored for '.$data['to_name'].'<br>';
            $this->storeMessage($data, null);
        }
    }


}
