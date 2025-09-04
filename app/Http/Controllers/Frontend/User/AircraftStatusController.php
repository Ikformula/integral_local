<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\DataPoint;
use App\Models\WeekRange;
use Illuminate\Http\Request;
use App\Models\Aircraft;
use App\Models\AircraftStatusSubmission;
use App\Models\AircraftChecklistItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AircraftStatusController extends Controller
{
    public function index(Request $request)
    {
        return view('frontend.aircraft_status.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'checklist_id' => 'required|exists:aircraft_status_checklist_items,id',
            'aircraft_id' => 'required|exists:aircrafts,id',
            'item_value' => 'nullable|string|max:655',
            'for_date' => 'required|date|before_or_equal:today',
        ]);

        $user = Auth::user();

        $week_range = WeekRange::where('from_date', '<=', $request->for_date)->where('to_date', '>=', $request->for_date)->first();
        if($week_range) {
            $week_range_id = $week_range->id;
            // 344, For Date
            $data_point = DataPoint::firstOrCreate([
                'week_range_id' => $week_range_id,
                'score_card_form_field_id' => 344,
                'name' => 'For Date',
                'business_area_id' => 11,
                'for_date' => $request->for_date,
                'time_title' => 'for_date'
            ],
                [
                'data_value' => $request->for_date
            ]);
        }

        $submission = AircraftStatusSubmission::updateOrCreate(
            [
                'checklist_id' => $data['checklist_id'],
                'aircraft_id' => $data['aircraft_id'],
                'for_date' => $data['for_date'],
            ],
            [
                'user_id' => $user->id,
                'item_value' => $data['item_value'],
            ]
        );

        return response()->json(['success' => true, 'submission' => $submission]);
    }
}
