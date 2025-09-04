<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\StaffTravelBeneficiary;
use Illuminate\Http\Request;

class StaffTravelBeneficiaryController extends Controller
{
    public function index()
    {
        $items = StaffTravelBeneficiary::all();
        return view('frontend.staff_travel_beneficiaries.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.staff_travel_beneficiaries.create');
    }

    public function store(Request $request)
    {
        try {
            StaffTravelBeneficiary::create($request->all());
            return redirect()->route('frontend.staff_travel_beneficiaries.index')
                ->withFlashSuccess('StaffTravelBeneficiary created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating StaffTravelBeneficiary: ' . $e->getMessage());
        }
    }

    public function show(StaffTravelBeneficiary $item)
    {
        return view('frontend.staff_travel_beneficiaries.show', compact('item'));
    }

    public function edit(StaffTravelBeneficiary $item)
    {
        return view('frontend.staff_travel_beneficiaries.edit', compact('item'));
    }

    public function update(Request $request, StaffTravelBeneficiary $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.staff_travel_beneficiaries.index')
                ->withFlashSuccess('StaffTravelBeneficiary updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating StaffTravelBeneficiary: ' . $e->getMessage());
        }
    }

    public function destroy(StaffTravelBeneficiary $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.staff_travel_beneficiaries.index')
                ->withFlashSuccess('StaffTravelBeneficiary deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting StaffTravelBeneficiary: ' . $e->getMessage());
        }
    }
}
