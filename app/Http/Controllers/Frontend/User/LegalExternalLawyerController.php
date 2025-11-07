<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\LegalTeamDocument;

class LegalExternalLawyerController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $lawyer = $user->lawyer;

        if(!$lawyer)
            return redirect()->back()->withErrors('Unauthorized action');

//        $items = LegalTeamDocument::where('firm', $lawyer->firm)->get();

        return redirect()->route('frontend.legal_team_cases.index');
//        return view('frontend.legal_team_documents.index', compact('items'));
    }
}
