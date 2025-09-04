<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;

class HolidayController extends Controller
{
    public function holidays()
    {
//        return view('frontend.holidays.index');
        return view('frontend.holidays.holidays-vue');
    }
    public function index()
    {
        $holidays = Holiday::all();
        return response()->json($holidays);
    }

    public function show($id)
    {
        $holiday = Holiday::find($id);
        return response()->json($holiday);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'entered_by_staff_ara_id' => 'required|string|max:255',
            'holidate' => 'required|date',
        ]);

        $holiday = new Holiday();
        $holiday->title = $request->input('title');
        $holiday->entered_by_staff_ara_id = $request->input('entered_by_staff_ara_id');
        $holiday->holidate = $request->input('holidate');
        $holiday->save();

        return response()->json($holiday, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'entered_by_staff_ara_id' => 'required|string|max:255',
            'holidate' => 'required|date',
        ]);

        $holiday = Holiday::find($id);
        if (!$holiday) {
            return response()->json(['message' => 'Holiday not found'], 404);
        }

        $holiday->title = $request->input('title');
        $holiday->entered_by_staff_ara_id = $request->input('entered_by_staff_ara_id');
        $holiday->holidate = $request->input('holidate');
        $holiday->save();

        return response()->json($holiday);
    }

    public function destroy($id)
    {
        $holiday = Holiday::find($id);
        if (!$holiday) {
            return response()->json(['message' => 'Holiday not found'], 404);
        }

        $holiday->delete();

        return response()->json(['message' => 'Holiday deleted']);
    }
}

