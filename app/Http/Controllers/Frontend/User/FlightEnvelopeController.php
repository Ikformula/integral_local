<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Aircraft;
use App\Models\FeCellInput;
use App\Models\FlightEnvelope;
use App\Models\Pilot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightEnvelopeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $they_can = $user->can('plan flights');
        $is_Admin = $user->isAdmin();
        $staff_ara_id = null;
        if($user->staff_member)
            $staff_ara_id = $user->staff_member->staff_ara_id;

        $is_pilot = false;
        if($staff_ara_id)
            $is_pilot = Pilot::where('company_id', $staff_ara_id)->count();

        if(!$they_can && !$is_pilot && !$is_Admin)
            return redirect()->route('frontend.user.dashboard')->withErrors('Unauthorized navigation');

        if($request->filled('from_date') && $request->filled('to_date')){
                $from_date = Carbon::parse($request->input('from_date'));
                $to_date = Carbon::parse($request->input('to_date'));
        }else{
            $from_date = now()->subWeek();
            $to_date = now();
        }

        $pilot_id = $request->filled('pilot_id') ? $request->pilot_id : null;
        $aircraft_id = $request->filled('aircraft_id') ? $request->aircraft_id : null;

        $pilot_staff_ara_id = null;
        if($pilot_id){
            $pilot = Pilot::find($pilot_id);
            $pilot_staff_ara_id = $pilot->company_id;
        }

        $arr = [
            'flight_deck_company_id_no_1',
            'flight_deck_company_id_no_2',
            'flight_deck_company_id_no_3',
            'flight_deck_company_id_no_4',
            'flight_deck_company_id_no_5',
        ];

        if ($user->can('plan flights') || $is_Admin) {
            if($pilot_staff_ara_id) {

                $fe_ids = FeCellInput::whereIn('cell_name', $arr)->where('cell_value', $pilot_staff_ara_id)->pluck('flight_envelope_id');

                $flight_envelopes = FlightEnvelope::whereIn('id', $fe_ids->toArray());
            }else {
                $flight_envelopes = FlightEnvelope::query();
            }
        } else {
            $fe_ids = FeCellInput::whereIn('cell_name', $arr)->where('cell_value', $staff_ara_id)->pluck('flight_envelope_id');

            $flight_envelopes = FlightEnvelope::whereIn('id', $fe_ids->toArray());
        }

// Additional filtering based on conditions
        $flight_envelopes->when($request->filled('from_date') && $request->filled('to_date'), function ($query) use ($from_date, $to_date) {
            $query->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
        })->when($aircraft_id, function ($query) use ($aircraft_id) {
            $query->where('aircraft_id', $aircraft_id);
        });

        $flight_envelopes = $flight_envelopes->get();


        return view('frontend.fuel-discrepancies.flight_data_records_dashboard')->with([
            'pilots' => Pilot::all(),
            'aircrafts' => Aircraft::all(),
            'flight_envelopes' => $flight_envelopes,
            'params' => $request->query(),
            'from_date' => $from_date,
            'to_date' => $to_date,
            'is_pilot' => $is_pilot,
        ]);
    }

    public function show(FlightEnvelope $flightEnvelope)
    {
        return redirect()->route('frontend.flight_envelopes.records.edit', $flightEnvelope);
//        return view('frontend.fuel-discrepancies.tech-log-entry')->with([
//            'flightEnvelope' => $flightEnvelope
//        ]);
    }

    public function store(Request $request)
    {
        $arr['flight_envelope_number'] = 'FE'.$request->aircraftReg.'-'.now()->toDateTimeString();
        $arr['aircraft_id'] = Aircraft::where('registration_number', $request->aircraftReg)->first();
        $arr['aircraft_id'] = $arr['aircraft_id']->id;
        $flightEnvelope = FlightEnvelope::create(array_merge($arr, $request->all()));
        return redirect()->route('frontend.flight_envelopes.reports.edit', $flightEnvelope);
    }

    public function edit($flightEnvelope)
    {
        $flightEnvelope = FlightEnvelope::find($flightEnvelope);

        if($flightEnvelope){
            return view('frontend.fuel-discrepancies.tech-log-entry')->with([
                'flightEnvelope' => $flightEnvelope,
                'pilots' => Pilot::all(),
                'aircrafts' => Aircraft::all(),
                'flight_numbers' => DB::table('flight_numbers')->select('flight_number', 'departure', 'arrival')->get()
            ]);
        }

        return redirect()->back()->withErrors('Envelope not found');
    }

    public function saveFieldData(Request $request)
    {
        // TODO: Check Auth and access privileges
//        $fei = FeCellInput::where('flight_envelope_id', $request->flightEnvelopeId)
//            ->where('cell_name', $request->cell_name)
//            ->first();
//
//        // TODO: Check if value is in required data format
//        $fei->cell_value = $request->cell_value;
//        $fei->save();
        $fei = FeCellInput::updateOrCreate(
            [
                'flight_envelope_id' => $request->flightEnvelopeId,
                'cell_name' => $request->cell_name,
            ],
            [
                'cell_value' => $request->cell_value,
            ]
        );


        return [
          'message' => 'Saved',
          'status' => 'successful'
        ];
    }
}
