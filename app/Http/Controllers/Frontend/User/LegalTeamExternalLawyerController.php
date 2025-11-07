<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\LegalTeamDocument;
use App\Models\LegalTeamExternalLawyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LegalTeamExternalLawyerController extends Controller
{
    public function index()
    {
        $items = LegalTeamExternalLawyer::all();
        return view('frontend.legal_team_external_lawyers.index', compact('items'));
    }

    public function create()
    {
        $firms = DB::table('legal_team_external_lawyers')
        ->select('firm')
        ->distinct()
        ->pluck('firm');
        return view('frontend.legal_team_external_lawyers.create', compact('firms'));
    }

    public function store(Request $request)
    {
        try {
            $lawyer = LegalTeamExternalLawyer::create($request->all());
            $user = $lawyer->user;
//            $user->give
            return redirect()->route('frontend.legal_team_external_lawyers.index')
                ->withFlashSuccess('LegalTeamExternalLawyer created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating External Lawyer:' . $e->getMessage());
        }
    }

    public function show(LegalTeamExternalLawyer $item)
    {
        return view('frontend.legal_team_external_lawyers.show', compact('item'));
    }

    public function edit(LegalTeamExternalLawyer $item)
    {
        return view('frontend.legal_team_external_lawyers.edit', compact('item'));
    }

    public function update(Request $request, LegalTeamExternalLawyer $item)
    {
        try {
            $user = User::find($item->user_id);
            if($user){
                $user->update($request->all());
            }
            $item->update($request->all());
            return redirect()->route('frontend.legal_team_external_lawyers.index')
                ->withFlashSuccess('External Lawyer updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating External Lawyer:' . $e->getMessage());
        }
    }

    public function destroy(LegalTeamExternalLawyer $item)
    {
        try {
            $user = User::find($item->user_id);
            if($user){
                $user->delete();
            }
            $item->delete();
            return redirect()->route('frontend.legal_team_external_lawyers.index')
                ->withFlashSuccess('External Lawyer deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting External Lawyer:' . $e->getMessage());
        }
    }
}
