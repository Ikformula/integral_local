<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsFlight;
use Illuminate\Http\Request;

class EcsFlightAjaxController extends Controller
{
    public function index()
    {
        $ecs_flights = EcsFlight::with([
            'ecs_booking_idRelation',
            'client_idRelation',
        ])->get();

        return view('frontend.ecs_flights.index', compact('ecs_flights'));
    }

    public function store(Request $request)
    {
        EcsFlight::create($request->all());
        return back()->withFlashSuccess('EcsFlight created successfully.');
    }

    public function update(Request $request, $id)
    {
        $ecs_flights = EcsFlight::findOrFail($id);
        $ecs_flights->update($request->all());
        return back()->withFlashSuccess('EcsFlight updated successfully.');
    }

    public function destroy($id)
    {
        EcsFlight::destroy($id);
        return back()->withFlashSuccess('EcsFlight deleted successfully.');
    }
}
