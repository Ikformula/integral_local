<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\QaCategory;
use Illuminate\Http\Request;

class QaCategoryAjaxController extends Controller
{
    public function index()
    {
        $qa_categories = QaCategory::with([
            'parent_idRelation',
        ])->get();

        return view('frontend.qa_categories.index', compact('qa_categories'));
    }

    public function store(Request $request)
    {
        QaCategory::create($request->all());
        return back()->withFlashSuccess('Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $qa_categories = QaCategory::findOrFail($id);
        $qa_categories->update($request->all());
        return back()->withFlashSuccess('Category updated successfully.');
    }

    public function destroy($id)
    {
        QaCategory::destroy($id);
        return back()->withFlashSuccess('Category deleted successfully.');
    }
}
