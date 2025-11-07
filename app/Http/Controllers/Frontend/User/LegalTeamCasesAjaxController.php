<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\LegalTeamCases;
use App\Models\LegalTeamExternalLawyer;
use Illuminate\Http\Request;

class LegalTeamCasesAjaxController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $lawyer = $user->lawyer;

        if($request->has('l_id')){
            $lawyer = LegalTeamExternalLawyer::find($request->l_id);
        }

        if($lawyer && $lawyer->firm) {
            $firm = $lawyer->firm;
            $legal_team_cases = LegalTeamCases::where('firm', $firm)->latest()->paginate(30);
        }else{
            $legal_team_cases = LegalTeamCases::latest()->paginate(30);
        }

//        $legal_team_cases->with([
//            'user_idRelation',
//            'lawyer',
//        ])->get();

        return view('frontend.legal_team_cases.index', compact('legal_team_cases', 'lawyer', 'firm'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            // Handle file & image uploads
            foreach ($request->allFiles() as $field => $file) {
                if ($file->isValid()) {
                    $folder = (strpos($field, 'image') !== false) ? 'images' : 'files';
                    $path = $file->store($folder, 'public');
                    $data[$field] = $path;
                }
            }

            LegalTeamCases::create($data);

            return back()->withFlashSuccess('Cases created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors('Error creating Cases: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $legal_team_cases = LegalTeamCases::findOrFail($id);
            $data = $request->all();

            // Handle file & image uploads
            foreach ($request->allFiles() as $field => $file) {
                if ($file->isValid()) {
                    $folder = (strpos($field, 'image') !== false) ? 'images' : 'files';
                    $path = $file->store($folder, 'public');
                    $data[$field] = $path;
                }
            }

            $legal_team_cases->update($data);

            return back()->withFlashSuccess('Cases updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors('Error updating Cases: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $legal_team_cases = LegalTeamCases::findOrFail($id);
            $legal_team_cases->delete();

            return back()->withFlashSuccess('Cases deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors('Error deleting Cases: ' . $e->getMessage());
        }
    }
}
