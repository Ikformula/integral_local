<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\LegalTeamFolder;
use Illuminate\Http\Request;

class LegalTeamFolderController extends Controller
{
    public function index()
    {
        $items = LegalTeamFolder::all();
        return view('frontend.legal_team_folders.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.legal_team_folders.create');
    }

    public function store(Request $request)
    {
        try {
            LegalTeamFolder::create($request->all());
            return redirect()->route('frontend.legal_team_folders.index')
                ->withFlashSuccess('LegalTeamFolder created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating LegalTeamFolder: ' . $e->getMessage());
        }
    }

    public function show(LegalTeamFolder $item)
    {
        return view('frontend.legal_team_folders.show', compact('item'));
    }

    public function edit(LegalTeamFolder $item)
    {
        return view('frontend.legal_team_folders.edit', compact('item'));
    }

    public function update(Request $request, LegalTeamFolder $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.legal_team_folders.index')
                ->withFlashSuccess('LegalTeamFolder updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating LegalTeamFolder: ' . $e->getMessage());
        }
    }

    public function destroy(LegalTeamFolder $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.legal_team_folders.index')
                ->withFlashSuccess('LegalTeamFolder deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting LegalTeamFolder: ' . $e->getMessage());
        }
    }
}
