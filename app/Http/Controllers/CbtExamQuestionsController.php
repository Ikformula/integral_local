<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\CbtExamQuestion;
use App\Models\CbtQuestion;
use Illuminate\Http\Request;
use Exception;

class CbtExamQuestionsController extends Controller
{

    /**
     * Display a listing of the cbt exam questions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cbtExamQuestions = CbtExamQuestion::with('cbtexam','cbtquestion')->paginate(25);

        return view('cbt_exam_questions.index', compact('cbtExamQuestions'));
    }

    /**
     * Show the form for creating a new cbt exam question.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $CbtExams = CbtExam::pluck('title','id')->all();
$CbtQuestions = CbtQuestion::pluck('id','id')->all();
        
        return view('cbt_exam_questions.create', compact('CbtExams','CbtQuestions'));
    }

    /**
     * Store a new cbt exam question in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $data = $this->getData($request);
        
        CbtExamQuestion::create($data);

        return redirect()->route('cbt_exam_questions.cbt_exam_question.index')
            ->with('success_message', 'Cbt Exam Question was successfully added.');
    }

    /**
     * Display the specified cbt exam question.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cbtExamQuestion = CbtExamQuestion::with('cbtexam','cbtquestion')->findOrFail($id);

        return view('cbt_exam_questions.show', compact('cbtExamQuestion'));
    }

    /**
     * Show the form for editing the specified cbt exam question.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cbtExamQuestion = CbtExamQuestion::findOrFail($id);
        $CbtExams = CbtExam::pluck('title','id')->all();
$CbtQuestions = CbtQuestion::pluck('id','id')->all();

        return view('cbt_exam_questions.edit', compact('cbtExamQuestion','CbtExams','CbtQuestions'));
    }

    /**
     * Update the specified cbt exam question in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $data = $this->getData($request);
        
        $cbtExamQuestion = CbtExamQuestion::findOrFail($id);
        $cbtExamQuestion->update($data);

        return redirect()->route('cbt_exam_questions.cbt_exam_question.index')
            ->with('success_message', 'Cbt Exam Question was successfully updated.');  
    }

    /**
     * Remove the specified cbt exam question from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $cbtExamQuestion = CbtExamQuestion::findOrFail($id);
            $cbtExamQuestion->delete();

            return redirect()->route('cbt_exam_questions.cbt_exam_question.index')
                ->with('success_message', 'Cbt Exam Question was successfully deleted.');
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
                'cbt_exam_id' => 'required',
            'cbt_question_id' => 'required', 
        ];

        
        $data = $request->validate($rules);




        return $data;
    }

}
