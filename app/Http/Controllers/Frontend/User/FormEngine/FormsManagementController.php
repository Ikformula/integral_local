<?php

namespace App\Http\Controllers\Frontend\User\FormEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormsManagementController extends Controller
{
    public function create()
    {
        return view('frontend.form_engine.create');
    }
}
