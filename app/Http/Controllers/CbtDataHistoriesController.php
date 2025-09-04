<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CbtDataHistory;
use App\Models\ChangedByUser;
use App\Models\Model;
use Illuminate\Http\Request;
use Exception;

class CbtDataHistoriesController extends Controller
{

    /**
     * Display a listing of the cbt data histories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cbtDataHistories = CbtDataHistory::with('model')->paginate(25);

        return view('cbt_data_histories.index', compact('cbtDataHistories'));
    }

    /**
     * Show the form for creating a new cbt data history.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $models = Model::pluck('id','id')->all();
$changedByUsers = ChangedByUser::pluck('id','id')->all();
        
        return view('cbt_data_histories.create', compact('models','changedByUsers'));
    }

    /**
     * Store a new cbt data history in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $data = $this->getData($request);
        
        CbtDataHistory::create($data);

        return redirect()->route('cbt_data_histories.cbt_data_history.index')
            ->with('success_message', 'Cbt Data History was successfully added.');
    }

    /**
     * Display the specified cbt data history.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cbtDataHistory = CbtDataHistory::with('model','changedbyuser')->findOrFail($id);

        return view('cbt_data_histories.show', compact('cbtDataHistory'));
    }

    /**
     * Show the form for editing the specified cbt data history.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cbtDataHistory = CbtDataHistory::findOrFail($id);
        $models = Model::pluck('id','id')->all();
$changedByUsers = ChangedByUser::pluck('id','id')->all();

        return view('cbt_data_histories.edit', compact('cbtDataHistory','models','changedByUsers'));
    }

    /**
     * Update the specified cbt data history in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $data = $this->getData($request);
        
        $cbtDataHistory = CbtDataHistory::findOrFail($id);
        $cbtDataHistory->update($data);

        return redirect()->route('cbt_data_histories.cbt_data_history.index')
            ->with('success_message', 'Cbt Data History was successfully updated.');  
    }

    /**
     * Remove the specified cbt data history from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $cbtDataHistory = CbtDataHistory::findOrFail($id);
            $cbtDataHistory->delete();

            return redirect()->route('cbt_data_histories.cbt_data_history.index')
                ->with('success_message', 'Cbt Data History was successfully deleted.');
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
                'model_type' => 'required|string|min:1|max:255',
            'model_id' => 'required',
            'previous_value' => 'required',
            'changed_by_user_id' => 'required', 
        ];

        
        $data = $request->validate($rules);




        return $data;
    }

}
