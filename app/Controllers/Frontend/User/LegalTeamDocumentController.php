<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\LegalTeamDocument;
use Illuminate\Http\Request;

class LegalTeamDocumentController extends Controller
{
    public function index()
    {
        $items = LegalTeamDocument::all();
        return view('frontend.legal_team_documents.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.legal_team_documents.create');
    }

    public function store(Request $request)
    {
        try {
            LegalTeamDocument::create($request->all());
            return redirect()->route('frontend.legal_team_documents.index')
                ->withFlashSuccess('LegalTeamDocument created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating LegalTeamDocument: ' . $e->getMessage());
        }
    }

    public function show(LegalTeamDocument $item)
    {
        return view('frontend.legal_team_documents.show', compact('item'));
    }

    public function edit(LegalTeamDocument $item)
    {
        return view('frontend.legal_team_documents.edit', compact('item'));
    }

    public function update(Request $request, LegalTeamDocument $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.legal_team_documents.index')
                ->withFlashSuccess('LegalTeamDocument updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating LegalTeamDocument: ' . $e->getMessage());
        }
    }

    public function destroy(LegalTeamDocument $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.legal_team_documents.index')
                ->withFlashSuccess('LegalTeamDocument deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting LegalTeamDocument: ' . $e->getMessage());
        }
    }
}
