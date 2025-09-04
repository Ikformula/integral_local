<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsBooking;
use Illuminate\Http\Request;

class EcsBookingController extends Controller
{
    public function index()
    {
        $items = EcsBooking::all();
        return view('frontend.ecs_bookings.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.ecs_bookings.create');
    }

    public function store(Request $request)
    {
        try {
            $booking = EcsBooking::create($request->all());
            return redirect()->route('frontend.ecs_bookings.show', $booking->id)
                ->withFlashSuccess('EcsBooking created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating EcsBooking: ' . $e->getMessage());
        }
    }

    public function show(EcsBooking $ecs_booking)
    {
        return view('frontend.ecs_bookings.show', compact('ecs_booking'));
    }

    public function edit(EcsBooking $item)
    {
        return view('frontend.ecs_bookings.edit', compact('item'));
    }

    public function update(Request $request, EcsBooking $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.ecs_bookings.index')
                ->withFlashSuccess('EcsBooking updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating EcsBooking: ' . $e->getMessage());
        }
    }

    public function destroy(EcsBooking $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.ecs_bookings.index')
                ->withFlashSuccess('EcsBooking deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting EcsBooking: ' . $e->getMessage());
        }
    }
}
