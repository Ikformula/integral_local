<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CbtExamCandidate;
use App\Models\CbtExamQuestion;
use App\Models\CbtOption;
use App\Models\CbtQuestionResponse;
use Illuminate\Http\Request;
use Exception;

class CbtQuestionResponsesController extends Controller
{

    /**
     * Display a listing of the cbt question responses.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cbtQuestionResponses = CbtQuestionResponse::with('cbtexamquestion','cbtoption','cbtexamcandidate')->paginate(25);

        return view('cbt_question_responses.index', compact('cbtQuestionResponses'));
    }

    /**
     * Show the form for creating a new cbt question response.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $CbtExamQuestions = CbtExamQuestion::pluck('created_at','id')->all();
$CbtOptions = CbtOption::pluck('body','id')->all();
$CbtExamCandidates = CbtExamCandidate::pluck('email','id')->all();
        
        return view('cbt_question_responses.create', compact('CbtExamQuestions','CbtOptions','CbtExamCandidates'));
    }

    /**
     * Store a new cbt question response in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $data = $this->getData($request);
        
        CbtQuestionResponse::create($data);

        return redirect()->route('cbt_question_responses.cbt_question_response.index')
            ->with('success_message', 'Cbt Question Response was successfully added.');
    }

    /**
     * Display the specified cbt question response.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cbtQuestionResponse = CbtQuestionResponse::with('cbtexamquestion','cbtoption','cbtexamcandidate')->findOrFail($id);

        return view('cbt_question_responses.show', compact('cbtQuestionResponse'));
    }

    /**
     * Show the form for editing the specified cbt question response.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cbtQuestionResponse = CbtQuestionResponse::findOrFail($id);
        $CbtExamQuestions = CbtExamQuestion::pluck('created_at','id')->all();
$CbtOptions = CbtOption::pluck('body','id')->all();
$CbtExamCandidates = CbtExamCandidate::pluck('email','id')->all();

        return view('cbt_question_responses.edit', compact('cbtQuestionResponse','CbtExamQuestions','CbtOptions','CbtExamCandidates'));
    }

    /**
     * Update the specified cbt question response in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $data = $this->getData($request);
        
        $cbtQuestionResponse = CbtQuestionResponse::findOrFail($id);
        $cbtQuestionResponse->update($data);

        return redirect()->route('cbt_question_responses.cbt_question_response.index')
            ->with('success_message', 'Cbt Question Response was successfully updated.');  
    }

    /**
     * Remove the specified cbt question response from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $cbtQuestionResponse = CbtQuestionResponse::findOrFail($id);
            $cbtQuestionResponse->delete();

            return redirect()->route('cbt_question_responses.cbt_question_response.index')
                ->with('success_message', 'Cbt Question Response was successfully deleted.');
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
                'cbt_exam_question_id' => 'required',
            'cbt_option_id' => 'required',
            'cbt_exam_candidate_id' => 'required',
            'is_history' => 'boolean|nullable', 
        ];

        
        $data = $request->validate($rules);


        $data['is_history'] = $request->has('is_history');


        return $data;
    }

}
