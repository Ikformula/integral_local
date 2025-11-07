<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        if($user->isEcsClient)
            return redirect()->route('frontend.ecs_client_portal.accountSummaries');

        $lawyer = $user->lawyer;
        if($lawyer)
            return redirect()->route('frontend.external_lawyer.documents');

        return view('frontend.index');
    }

}
