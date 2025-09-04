<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\LAndDTrainingCourse;
use Illuminate\Http\Request;

class LAndDTrainingCourseController extends Controller
{
    public function index()
    {
        $courses = LAndDTrainingCourse::all();
        return view('frontend.l_and_d_training_courses.index', compact('courses'));
    }

    public function create()
    {
        return view('frontend.l_and_d_training_courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_virtual' => 'required|boolean',
            'in_house' => 'required|boolean',
            'facilitated_by_in_house' => 'required|boolean',
            'facilitated_by' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'held_from' => 'required|date',
            'ended_at' => 'required|date|after_or_equal:held_from',
            'certificate_name' => 'nullable|string|max:255',
            'cost_in_naira' => 'nullable|numeric',
            'cost_in_dollars' => 'nullable|numeric',
        ]);

        LAndDTrainingCourse::create($validated);

        return redirect()->route('frontend.l_and_d_training_courses.index')->with('success', 'Training course created successfully.');
    }

    public function edit(LAndDTrainingCourse $l_and_d_training_course)
    {
        return view('frontend.l_and_d_training_courses.edit')->with([
            'course' => $l_and_d_training_course
        ]);
    }

    public function update(Request $request, LAndDTrainingCourse $l_and_d_training_course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_virtual' => 'required|boolean',
            'in_house' => 'required|boolean',
            'facilitated_by_in_house' => 'required|boolean',
            'facilitated_by' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'held_from' => 'required|date',
            'ended_at' => 'required|date|after_or_equal:held_from',
            'certificate_name' => 'nullable|string|max:255',
            'cost_in_naira' => 'nullable|numeric',
            'cost_in_dollars' => 'nullable|numeric',
        ]);

        $l_and_d_training_course->update($validated);

        return redirect()->route('frontend.l_and_d_training_courses.index')->with('success', 'Training course updated successfully.');
    }

    public function show(LAndDTrainingCourse $l_and_d_training_course)
    {
        return view('frontend.l_and_d_training_courses.show')->with([
            'course' => $l_and_d_training_course
        ]);
    }

    public function destroy(LAndDTrainingCourse $l_and_d_training_course)
    {
        $l_and_d_training_course->delete();

        return redirect()->route('frontend.l_and_d_training_courses.index')->with('success', 'Training course deleted successfully.');
    }
}
