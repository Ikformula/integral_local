<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\LegalTeamFolderAccess;
use Illuminate\Http\Request;

class LegalTeamFolderAccessAjaxController extends Controller
{
    public function index()
    {
        $legal_team_folder_accesses = LegalTeamFolderAccess::with([
            'user_idRelation',
            'folder_idRelation',
        ])->get();

        return view('frontend.legal_team_folder_accesses.index', compact('legal_team_folder_accesses'));
    }

    public function store(Request $request)
    {
        LegalTeamFolderAccess::create($request->all());
        return back()->withFlashSuccess('Folder Access created successfully.');
    }

    public function update(Request $request, $id)
    {
        $legal_team_folder_accesses = LegalTeamFolderAccess::findOrFail($id);
        $legal_team_folder_accesses->update($request->all());
        return back()->withFlashSuccess('Folder Access updated successfully.');
    }

    public function destroy($id)
    {
        LegalTeamFolderAccess::destroy($id);
        return back()->withFlashSuccess('Folder Access deleted successfully.');
    }
}
