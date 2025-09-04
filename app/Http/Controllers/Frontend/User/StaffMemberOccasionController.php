<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\OccasionMessage;
use App\Models\StaffMemberOccasion;
use Illuminate\Http\Request;

class StaffMemberOccasionController extends Controller
{
    public function occasion($slug)
    {
        $occasion = StaffMemberOccasion::where('slug', $slug)->first();
        if(!$occasion){
            return back()->withErrors('Invalid url');
        }

        return view('frontend.staff_occasions.occasion')->with([
            'occasion' => $occasion
        ]);
    }

    public function addMessage(Request $request, $slug)
    {
        $occasion = StaffMemberOccasion::where('slug', $slug)->first();
        if(!$occasion){
            return back()->withErrors('Invalid url');
        }

        $arr = [
            'user_id' => auth()->id(),
            'occasion_id' => $occasion->id
        ];

        $message = OccasionMessage::create(array_merge($request->all(), $arr));
        return back()->withFlashSuccess('Message Added');
    }
}
