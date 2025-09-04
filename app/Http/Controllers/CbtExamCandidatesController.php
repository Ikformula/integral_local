<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\CbtExamCandidate;
use App\Models\StaffMember;
use Illuminate\Http\Request;
use Exception;

class CbtExamCandidatesController extends Controller
{

    /**
     * Display a listing of the cbt exam candidates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cbtExamCandidates = CbtExamCandidate::with('StaffMember','cbtexam')->paginate(25);

        return view('cbt_exam_candidates.index', compact('cbtExamCandidates'));
    }

    /**
     * Show the form for creating a new cbt exam candidate.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $StaffMembers = StaffMember::all();
        $CbtExams = CbtExam::pluck('title','id')->all();

        return view('cbt_exam_candidates.create', compact('StaffMembers','CbtExams'));
    }

    /**
     * Store a new cbt exam candidate in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $data = $this->getData($request);

        CbtExamCandidate::create($data);

//        return redirect()->route('cbt_exam_candidates.cbt_exam_candidate.index')
//            ->with('success_message', 'Cbt Exam Candidate was successfully added.');
        return redirect()->back()
            ->withFlashSuccess('Cbt Exam Candidate was successfully added.');
    }

    public function uploadFromCSV(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csvFile' => 'required|file|mimes:csv,txt',
        ]);

        // Get the uploaded file
        $file = $request->file('csvFile');

        // Open the file for reading
        $fileHandle = fopen($file->getRealPath(), 'r');

        // Get the header row
        $headers = fgetcsv($fileHandle);

        // Initialize the result array
        $data = [];

        // Loop through each remaining row
        while (($row = fgetcsv($fileHandle)) !== false) {
            // Combine the row with the headers
            $data[] = array_combine($headers, $row);
        }

        // Close the file
        fclose($fileHandle);

        // Return or process the resulting array
        // For demonstration, we'll return it as JSON
        return response()->json($data);

    }

    /**
     * Display the specified cbt exam candidate.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cbtExamCandidate = CbtExamCandidate::with('StaffMember','cbtexam')->findOrFail($id);

        return view('cbt_exam_candidates.show', compact('cbtExamCandidate'));
    }

    /**
     * Show the form for editing the specified cbt exam candidate.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cbtExamCandidate = CbtExamCandidate::findOrFail($id);
        $StaffMembers = StaffMember::pluck('id','id')->all();
$CbtExams = CbtExam::pluck('title','id')->all();

        return view('cbt_exam_candidates.edit', compact('cbtExamCandidate','StaffMembers','CbtExams'));
    }

    /**
     * Update the specified cbt exam candidate in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {

        $data = $this->getData($request);

        $cbtExamCandidate = CbtExamCandidate::findOrFail($id);
        $cbtExamCandidate->update($data);

        return redirect()->route('cbt_exam_candidates.cbt_exam_candidate.index')
            ->with('success_message', 'Cbt Exam Candidate was successfully updated.');
    }

    /**
     * Remove the specified cbt exam candidate from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $cbtExamCandidate = CbtExamCandidate::findOrFail($id);
            $cbtExamCandidate->delete();

            return redirect()->route('cbt_exam_candidates.cbt_exam_candidate.index')
                ->with('success_message', 'Cbt Exam Candidate was successfully deleted.');
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
                'email' => 'required|string|min:1|max:255',
            'staff_ara_id' => 'nullable',
            'cbt_exam_id' => 'required',
            'surname' => 'required|string|min:1|max:255',
            'first_name' => 'nullable|string|min:1|max:255',
            'other_names' => 'nullable|string|min:0|max:255',
            'age' => 'nullable|numeric|min:0|max:4294967295',
            'gender' => 'required',
            'state' => 'required|string|min:1|max:255',
            'address' => 'required',
            'phone_number' => 'required|string|min:1|max:20',
        ];


        $data = $request->validate($rules);




        return $data;
    }

}
