<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaxComplaintsController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        return view('frontend.pax_complaints.create');
    }

    public function showQR()
    {
        $img_src = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" . urlencode(route('frontend.pax_complaints.pax_complaints.create'));
        return view('frontend.pax_complaints.qr-code', compact('img_src'));
    }
    

}
