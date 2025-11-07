<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\QaCategory;
use App\Models\QaLetter;
use Illuminate\Http\Request;

class QaLetterAjaxController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $defaultFrom = $now->copy()->subMonth()->startOfYear();
        $defaultTo = $now->endOfDay();
        $created_from = $request->get('created_from', $defaultFrom->toDateString());
        $created_to = $request->get('created_to', $defaultTo->toDateString());
        $for_date_from = $request->get('for_date_from');
        $for_date_to = $request->get('for_date_to');

        $query = QaLetter::with(['category_idRelation']);
//        $query->whereBetween('created_at', [$created_from, $created_to]);
        if ($created_from) $query->where('created_at', '>=', $created_from);
        if ($created_to) $query->where('created_at', '<=', $created_to.' 23:59:59');
        if ($for_date_from) $query->where('for_date', '>=', $for_date_from);
        if ($for_date_to) $query->where('for_date', '<=', $for_date_to.' 23:59:59');

        $qa_letter = $query->get();

        $qa_categories = QaCategory::all();

        // Statistics
        $total_count = $qa_letter->count();
        $in_count = $qa_letter->where('direction', 'in')->count();
        $out_count = $qa_letter->where('direction', 'out')->count();
        $pending_count = $qa_letter->where('status', 'pending')->count();
        $attended_count = $qa_letter->where('status', 'attended')->count();

        $files_base_url = substr(config('app.url'), 0, -7);

        return view('frontend.qa_letter.index', compact(
            'qa_letter',
            'qa_categories',
            'created_from',
            'created_to',
            'for_date_from',
            'for_date_to',
            'total_count',
            'in_count',
            'out_count',
            'pending_count',
            'attended_count',
            'files_base_url'
        ));
    }

    public function store(Request $request)
    {
        $arr = $request->all();
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('qa_letters', 'public');
            $arr['file_path'] = $path;
        }
        QaLetter::create($arr);
        return back()->withFlashSuccess('QA Letters created successfully.');
    }

    public function update(Request $request, $id)
    {
        $arr = $request->all();
        $qa_letter = QaLetter::findOrFail($id);
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('qa_letters', 'public');
            $arr['file_path'] = $path;
        }
        if ($request->status != $qa_letter->status)
            $arr['status_changed_at'] = now();
        $qa_letter->update($arr);
        return back()->withFlashSuccess('QA Letters updated successfully.');
    }

    public function destroy($id)
    {
        QaLetter::destroy($id);
        return back()->withFlashSuccess('QA Letters deleted successfully.');
    }
}
