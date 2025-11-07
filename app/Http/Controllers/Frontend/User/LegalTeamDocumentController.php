<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\LegalTeamCases;
use App\Models\LegalTeamDocument;
use App\Models\LegalTeamExternalLawyer;
use Illuminate\Http\Request;

class LegalTeamDocumentController extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();

        $request->validate([
            'case_id' => ['required', 'integer', 'exists:legal_team_cases,id']
        ]);
        $case_id = $request->case_id;
        $case = LegalTeamCases::find($case_id);
        if(!$case)
            return back()->withErrors('Unauthorized');

        $items = LegalTeamDocument::query();

        $items->where('case_id', $request->case_id);

        $lawyer = $case->lawyer;

        $process_type = $request->has('pr') && (in_array($request->pr, ['claimant', 'defendant'])) ? $request->pr : 'claimant';
        $items = $items->where('process_type', $process_type)->orderBy('created_at', 'desc')->get();

        return view('frontend.legal_team_documents.index', compact('items', 'process_type', 'lawyer', 'case_id'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'case_id' => ['required', 'integer', 'exists:legal_team_cases,id']
        ]);
        $case_id = $request->case_id;
        $case = LegalTeamCases::find($case_id);
        $lawyer = $case->lawyer;
        $process_type = $request->has('pr') && (in_array($request->pr, ['claimant', 'defendant'])) ? $request->pr : 'claimant';

        return view('frontend.legal_team_documents.create', compact('process_type', 'lawyer', 'case_id', 'case'));
    }

    public function store(Request $request)
    {

        try {
            $arr = $request->all();
            $user = auth()->user();
            $arr['user_id'] = $user->id;

            if($request->filled('external_lawyer_id')){
                $lawyer = LegalTeamExternalLawyer::find($request->external_lawyer_id);
            }else {
                $lawyer = $user->lawyer;
            }

            if ($lawyer)
                $arr['firm'] = $lawyer->firm;
            if ($request->hasFile('file_name')) {
                $file = $request->file('file_name');
                $size = $request->file('file_name')->getSize();
                $arr['size_in_kilobytes'] = round($size / 1024, 2);
                $arr['size_in_megabytes'] = round($size / 1048576, 2);
                $path = $file->store('legal_team_docs', 'public');
                $arr['file_name'] = $path;
            }

            LegalTeamDocument::create($arr);
            return redirect()->route('frontend.legal_team_documents.index', ['pr' => $request->process_type, 'case_id' => $request->case_id])
                ->withFlashSuccess('Document created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating Document: ' . $e->getMessage());
        }
    }

    public function show(LegalTeamDocument $item)
    {
        $user = auth()->user();

        $lawyer = $user->lawyer;

        if ($lawyer) {
            if ($item->firm != $lawyer->firm && !$user->can('manage legal team'))
                return redirect()->back()->withErrors('Unauthorized');
        }
        return view('frontend.legal_team_documents.show', compact('item'));
    }

    public function edit(LegalTeamDocument $item)
    {
        $user = auth()->user();

        $lawyer = $user->lawyer;

        if ($lawyer) {
            if ($item->firm != $lawyer->firm && !$user->can('manage legal team'))
                return redirect()->back()->withErrors('Unauthorized');
        }
        return view('frontend.legal_team_documents.edit', compact('item'));
    }

    public function update(Request $request, LegalTeamDocument $item)
    {
        try {
            $arr = $request->all();
            $user = auth()->user();
            $arr['user_id'] = $user->id;
            $lawyer = $user->lawyer;
            if ($lawyer)
                $arr['firm'] = $lawyer->firm;
            if ($request->hasFile('file_name')) {
                $file = $request->file('file_name');
                $size = $request->file('file_name')->getSize();
                $arr['size_in_kilobytes'] = round($size / 1024, 2);
                $arr['size_in_megabytes'] = round($size / 1048576, 2);
                $path = $file->store('legal_team_docs', 'public');
                $arr['file_name'] = $path;
            }
            $item->update($arr);
            return redirect()->route('frontend.legal_team_documents.index')
                ->withFlashSuccess('Document updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating Document: ' . $e->getMessage());
        }
    }

    public function destroy(LegalTeamDocument $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.legal_team_documents.index')
                ->withFlashSuccess('Document deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting Document: ' . $e->getMessage());
        }
    }

    public function downloadZip(Request $request)
    {
        $user = auth()->user();
        $lawyer = $user->lawyer;
        $process_type = $request->has('pr') && (in_array($request->pr, ['claimant', 'defendant'])) ? $request->pr : 'claimant';
        $case_id = $request->case_id;

        $query = LegalTeamDocument::query();
        if ($case_id) {
            $query->where('case_id', $case_id);
        }
        if ($lawyer) {
            $query->where('firm', $lawyer->firm);
        }
        $query->where('process_type', $process_type);
        $items = $query->get();

        if ($items->isEmpty()) {
            return redirect()->back()->withErrors('No documents found to zip.');
        }

        // Get case title for slug
        $caseTitleSlug = '';
        if ($case_id) {
            $case = \DB::table('legal_team_cases')->where('id', $case_id)->first();
            if ($case && isset($case->title)) {
                $caseTitleSlug = \Str::slug($case->title);
            }
        }

        $zipFileName = 'legal_team_documents_' . $process_type . '_case_' . $case_id . '_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->withErrors('Could not create ZIP file.');
        }

        foreach ($items as $item) {
            if ($item->file_name) {
                $filePath = storage_path('app/public/' . $item->file_name);
                if (file_exists($filePath)) {
                    // Slugify document title
                    $docTitleSlug = $item->title ? \Str::slug($item->title) : 'document';
                    $baseName = basename($item->file_name);
                    $ext = pathinfo($baseName, PATHINFO_EXTENSION);
                    $zipName = $docTitleSlug;
                    if ($caseTitleSlug) {
                        $zipName .= '_case_' . $caseTitleSlug;
                    }
                    $zipName .= '.' . $ext;
                    $zip->addFile($filePath, $zipName);
                }
            }
        }
        $zip->close();

        if (!file_exists($zipPath)) {
            return redirect()->back()->withErrors('ZIP file not found after creation.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
