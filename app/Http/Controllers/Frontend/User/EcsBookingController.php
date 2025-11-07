<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsBooking;
use App\Models\EcsBookingTax;
use App\Models\EcsClient;
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
        return view('frontend.ecs_bookings.client-selection')->with([
            'clients' => EcsClient::all()
        ]);
    }

    public function createBooking(Request $request)
    {
        $client = EcsClient::find($request->client_id);
        return view('frontend.ecs_bookings.create', compact('client'));
    }

    public function store(Request $request)
    {
        try {
            $ecs_booking = EcsBooking::create($request->all());

            if ($request->filled('tax')) {
                foreach ($request->tax as $tax_name => $amount) {
                    if (!isset($amount))
                        continue;

                    $booking_tax = new EcsBookingTax();
                    $booking_tax->client_id = $ecs_booking->client_id;
                    $booking_tax->booking_id = $ecs_booking->id;
                    $booking_tax->tax_name = $tax_name;
                    $booking_tax->amount = $amount;
                    $booking_tax->save();
                }
            }

            return redirect()->route('frontend.ecs_bookings.show', $ecs_booking->id)
                ->withFlashSuccess('Request created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating Request: ' . $e->getMessage());
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
