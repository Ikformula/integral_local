<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;

class CbtExamsController extends Controller
{

    /**
     * Display a listing of the cbt exams.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cbtExams = CbtExam::with('creatoruser')->paginate(25);

        return view('cbt_exams.index', compact('cbtExams'));
    }

    /**
     * Show the form for creating a new cbt exam.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $creatorUsers = User::pluck('id','id')->all();

        return view('cbt_exams.create', compact('creatorUsers'));
    }

    /**
     * Store a new cbt exam in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $data = $this->getData($request);

        CbtExam::create($data);

        return redirect()->route('cbt_exams.cbt_exam.index')
            ->with('success_message', 'Cbt Exam was successfully added.');
    }

    /**
     * Display the specified cbt exam.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cbtExam = CbtExam::with('creatoruser')->findOrFail($id);

        return view('cbt_exams.show', compact('cbtExam'));
    }

    /**
     * Show the form for editing the specified cbt exam.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cbtExam = CbtExam::findOrFail($id);
        $creatorUsers = User::pluck('id','id')->all();

        return view('cbt_exams.edit', compact('cbtExam','creatorUsers'));
    }

    /**
     * Update the specified cbt exam in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {

        $data = $this->getData($request);

        $cbtExam = CbtExam::findOrFail($id);
        $cbtExam->update($data);

        return redirect()->route('cbt_exams.cbt_exam.index')
            ->with('success_message', 'Cbt Exam was successfully updated.');
    }

    /**
     * Remove the specified cbt exam from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $cbtExam = CbtExam::findOrFail($id);
            $cbtExam->delete();

            return redirect()->route('cbt_exams.cbt_exam.index')
                ->with('success_message', 'Cbt Exam was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }


    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
                'title' => 'required|string|min:1|max:255',
            'start_at' => 'required',
            'duration_in_minutes' => 'required|numeric|min:0|max:4294967295',
            'creator_user_id' => 'required',
        ];


        $data = $request->validate($rules);
        $data['start_at'] = Carbon::parse($request->start_at)->toDateTimeString();

        return $data;
    }

}
