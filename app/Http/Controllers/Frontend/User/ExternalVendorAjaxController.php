<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ExternalVendor;
use Illuminate\Http\Request;

class ExternalVendorAjaxController extends Controller
{
    public function index()
    {
        $external_vendors = ExternalVendor::with([
        ])->get();

        return view('frontend.external_vendors.index', compact('external_vendors'));
    }

    public function store(Request $request)
    {
        ExternalVendor::create($request->all());
        return back()->withFlashSuccess('Vendors created successfully.');
    }

    public function update(Request $request, $id)
    {
        $external_vendors = ExternalVendor::findOrFail($id);
        $external_vendors->update($request->all());
        return back()->withFlashSuccess('Vendors updated successfully.');
    }

    public function destroy($id)
    {
        ExternalVendor::destroy($id);
        return back()->withFlashSuccess('Vendors deleted successfully.');
    }
}
