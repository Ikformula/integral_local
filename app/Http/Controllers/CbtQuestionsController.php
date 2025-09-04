<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CbtOption;
use App\Models\CbtQuestion;
use App\Models\CbtSubject;
use Illuminate\Http\Request;
use Exception;

class CbtQuestionsController extends Controller
{

    /**
     * Display a listing of the cbt questions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cbtQuestions = CbtQuestion::paginate(25);

        return view('cbt_questions.index', compact('cbtQuestions'));
    }

    /**
     * Show the form for creating a new cbt question.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $CbtSubjects = CbtSubject::pluck('id','name')->all();
        $formAction = route('cbt_questions.cbt_question.store');

        return view('cbt_questions.claude-question-form', compact('CbtSubjects', 'formAction'));
    }

    /**
     * Store a new cbt question in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function storeOld(Request $request)
    {

        $data = $this->getData($request);

        CbtQuestion::create($data);

        return redirect()->route('cbt_questions.cbt_question.index')
            ->with('success_message', 'Cbt Question was successfully added.');
    }

    /**
     * Display the specified cbt question.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cbtQuestion = CbtQuestion::with('cbtsubject')->findOrFail($id);

        return view('cbt_questions.show', compact('cbtQuestion'));
    }

    /**
     * Show the form for editing the specified cbt question.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cbtQuestion = CbtQuestion::findOrFail($id);
        $CbtSubjects = CbtSubject::pluck('id','name')->all();
        $formAction = route('cbt_questions.cbt_question.update', $id);

        return view('cbt_questions.claude-question-form', compact('cbtQuestion','CbtSubjects', 'formAction'));
    }

    /**
     * Update the specified cbt question in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function updateOld($id, Request $request)
    {

        $data = $this->getData($request);

        $cbtQuestion = CbtQuestion::findOrFail($id);
        $cbtQuestion->update($data);

        return redirect()->route('cbt_questions.cbt_question.index')
            ->with('success_message', 'Cbt Question was successfully updated.');
    }

    /**
     * Remove the specified cbt question from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $cbtQuestion = CbtQuestion::findOrFail($id);
            $cbtQuestion->delete();

            return redirect()->route('cbt_questions.cbt_question.index')
                ->with('success_message', 'Cbt Question was successfully deleted.');
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
                'question' => 'required',
            'cbt_subject_id' => 'required',
        ];


        $data = $request->validate($rules);


        return $data;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'required|string',
            'cbt_subject_id' => 'required|exists:cbt_subjects,id',
            'options' => 'required|array|min:2',
            'options.*.body' => 'required|string',
            'options.*.is_correct' => 'boolean',
        ]);

        $question = new CbtQuestion();
        $question->question = $validatedData['question'];
        $question->cbt_subject_id = $validatedData['cbt_subject_id'];
        $question->save();
        $latestQuestion = CbtQuestion::latest()->first();

        foreach ($validatedData['options'] as $optionData) {
            CbtOption::create([
                'cbt_question_id' => $latestQuestion->id,
                'body' => $optionData['body'],
                'is_correct' => isset($optionData['is_correct']),
            ]);
        }

        return redirect()->back()->withFlashSuccess('Question created successfully.');
    }

    public function update(Request $request, CbtQuestion $cbtQuestion)
    {
        $validatedData = $request->validate([
            'question' => 'required|string',
            'cbt_subject_id' => 'required|exists:cbt_subjects,id',
            'options' => 'required|array|min:2',
            'options.*.body' => 'required|string',
            'options.*.is_correct' => 'boolean',
        ]);

        $cbtQuestion->update([
            'question' => $validatedData['question'],
            'cbt_subject_id' => $validatedData['cbt_subject_id'],
        ]);

        // Remove existing options
        $cbtQuestion->cbtOptions()->delete();

        // Add new options
        foreach ($validatedData['options'] as $optionData) {
            $cbtQuestion->cbtOptions()->create([
                'body' => $optionData['body'],
                'is_correct' => isset($optionData['is_correct']),
            ]);
        }

        return redirect()->route('cbt_questions.cbt_question.index')->with('success', 'Question updated successfully.');
    }
}
