<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CbtSubject;
use Illuminate\Http\Request;
use Exception;

class CbtSubjectsController extends Controller
{

    /**
     * Display a listing of the cbt subjects.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cbtSubjects = CbtSubject::paginate(25);

        return view('cbt_subjects.index', compact('cbtSubjects'));
    }

    /**
     * Show the form for creating a new cbt subject.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('cbt_subjects.create');
    }

    /**
     * Store a new cbt subject in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $data = $this->getData($request);
        
        CbtSubject::create($data);

        return redirect()->route('cbt_subjects.cbt_subject.index')
            ->with('success_message', 'Cbt Subject was successfully added.');
    }

    /**
     * Display the specified cbt subject.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cbtSubject = CbtSubject::findOrFail($id);

        return view('cbt_subjects.show', compact('cbtSubject'));
    }

    /**
     * Show the form for editing the specified cbt subject.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cbtSubject = CbtSubject::findOrFail($id);
        

        return view('cbt_subjects.edit', compact('cbtSubject'));
    }

    /**
     * Update the specified cbt subject in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $data = $this->getData($request);
        
        $cbtSubject = CbtSubject::findOrFail($id);
        $cbtSubject->update($data);

        return redirect()->route('cbt_subjects.cbt_subject.index')
            ->with('success_message', 'Cbt Subject was successfully updated.');  
    }

    /**
     * Remove the specified cbt subject from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $cbtSubject = CbtSubject::findOrFail($id);
            $cbtSubject->delete();

            return redirect()->route('cbt_subjects.cbt_subject.index')
                ->with('success_message', 'Cbt Subject was successfully deleted.');
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
                'name' => 'required|string|min:1|max:255', 
        ];

        
        $data = $request->validate($rules);




        return $data;
    }

}
