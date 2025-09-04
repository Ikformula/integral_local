<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsFlight;
use Illuminate\Http\Request;

class EcsFlightController extends Controller
{
    public function index()
    {
        $items = EcsFlight::all();
        return view('frontend.ecs_flights.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.ecs_flights.create');
    }

    public function store(Request $request)
    {
        try {
            EcsFlight::create($request->all());
            return redirect()->back()
                ->withFlashSuccess('EcsFlight created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating EcsFlight: ' . $e->getMessage());
        }
    }

    public function show(EcsFlight $item)
    {
        return view('frontend.ecs_flights.show', compact('item'));
    }

    public function edit(EcsFlight $item)
    {
        return view('frontend.ecs_flights.edit', compact('item'));
    }

    public function update(Request $request, EcsFlight $item)
    {
        try {
            $item->update($request->all());
            return redirect()->back()
                ->withFlashSuccess('EcsFlight updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating EcsFlight: ' . $e->getMessage());
        }
    }

    public function destroy(EcsFlight $item)
    {
        try {
            $item->delete();
            return redirect()->back()
                ->withFlashSuccess('EcsFlight deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting EcsFlight: ' . $e->getMessage());
        }
    }
}
