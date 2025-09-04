<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\QaLetter;
use Illuminate\Http\Request;

class QaLetterAjaxController extends Controller
{
    public function index()
    {
        $qa_letter = QaLetter::with([
            'category_idRelation',
        ])->get();

        return view('frontend.qa_letter.index', compact('qa_letter'));
    }

    public function store(Request $request)
    {
        QaLetter::create($request->all());
        return back()->withFlashSuccess('QA Letters created successfully.');
    }

    public function update(Request $request, $id)
    {
        $qa_letter = QaLetter::findOrFail($id);
        $qa_letter->update($request->all());
        return back()->withFlashSuccess('QA Letters updated successfully.');
    }

    public function destroy($id)
    {
        QaLetter::destroy($id);
        return back()->withFlashSuccess('QA Letters deleted successfully.');
    }
}
