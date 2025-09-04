<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use Illuminate\Http\Request;

class ContentCategoryController extends Controller
{
    public function index()
    {
        $categories = ContentCategory::all();
        return view('frontend.pilots_library.categories.index')->with([
            'categories' => $categories
        ]);
    }

//    public function create()
//    {
//
//    }
//
//    public function store(Request $request)
//    {
//        $category = ContentCategory::create($request->all);
//    }
}
