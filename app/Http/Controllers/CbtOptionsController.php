<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CbtOption;
use App\Models\CbtQuestion;
use Illuminate\Http\Request;
use Exception;

class CbtOptionsController extends Controller
{

    /**
     * Display a listing of the cbt options.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cbtOptions = CbtOption::with('cbtquestion')->paginate(25);

        return view('cbt_options.index', compact('cbtOptions'));
    }

    /**
     * Show the form for creating a new cbt option.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $CbtQuestions = CbtQuestion::pluck('id','id')->all();
        
        return view('cbt_options.create', compact('CbtQuestions'));
    }

    /**
     * Store a new cbt option in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $data = $this->getData($request);
        
        CbtOption::create($data);

        return redirect()->route('cbt_options.cbt_option.index')
            ->with('success_message', 'Cbt Option was successfully added.');
    }

    /**
     * Display the specified cbt option.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cbtOption = CbtOption::with('cbtquestion')->findOrFail($id);

        return view('cbt_options.show', compact('cbtOption'));
    }

    /**
     * Show the form for editing the specified cbt option.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cbtOption = CbtOption::findOrFail($id);
        $CbtQuestions = CbtQuestion::pluck('id','id')->all();

        return view('cbt_options.edit', compact('cbtOption','CbtQuestions'));
    }

    /**
     * Update the specified cbt option in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $data = $this->getData($request);
        
        $cbtOption = CbtOption::findOrFail($id);
        $cbtOption->update($data);

        return redirect()->route('cbt_options.cbt_option.index')
            ->with('success_message', 'Cbt Option was successfully updated.');  
    }

    /**
     * Remove the specified cbt option from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $cbtOption = CbtOption::findOrFail($id);
            $cbtOption->delete();

            return redirect()->route('cbt_options.cbt_option.index')
                ->with('success_message', 'Cbt Option was successfully deleted.');
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
                'cbt_question_id' => 'required',
            'body' => 'required',
            'is_correct' => 'boolean|nullable', 
        ];

        
        $data = $request->validate($rules);


        $data['is_correct'] = $request->has('is_correct');


        return $data;
    }

}
