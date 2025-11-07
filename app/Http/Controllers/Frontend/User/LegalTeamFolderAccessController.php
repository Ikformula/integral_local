<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\LegalTeamFolderAccess;
use Illuminate\Http\Request;

class LegalTeamFolderAccessController extends Controller
{
    public function index()
    {
        $items = LegalTeamFolderAccess::all();
        return view('frontend.legal_team_folder_accesses.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.legal_team_folder_accesses.create');
    }

    public function store(Request $request)
    {
        try {
            LegalTeamFolderAccess::create($request->all());
            return redirect()->route('frontend.legal_team_folder_accesses.index')
                ->withFlashSuccess('LegalTeamFolderAccess created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating LegalTeamFolderAccess: ' . $e->getMessage());
        }
    }

    public function show(LegalTeamFolderAccess $item)
    {
        return view('frontend.legal_team_folder_accesses.show', compact('item'));
    }

    public function edit(LegalTeamFolderAccess $item)
    {
        return view('frontend.legal_team_folder_accesses.edit', compact('item'));
    }

    public function update(Request $request, LegalTeamFolderAccess $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.legal_team_folder_accesses.index')
                ->withFlashSuccess('LegalTeamFolderAccess updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating LegalTeamFolderAccess: ' . $e->getMessage());
        }
    }

    public function destroy(LegalTeamFolderAccess $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.legal_team_folder_accesses.index')
                ->withFlashSuccess('LegalTeamFolderAccess deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting LegalTeamFolderAccess: ' . $e->getMessage());
        }
    }
}
