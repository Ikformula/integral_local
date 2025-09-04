<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\StbLoginLog;
use Illuminate\Http\Request;

class StbLoginLogController extends Controller
{
    public function index()
    {
        $items = StbLoginLog::all();
        return view('frontend.stb_login_logs.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.stb_login_logs.create');
    }

    public function store(Request $request)
    {
        try {
            StbLoginLog::create($request->all());
            return redirect()->route('frontend.stb_login_logs.index')
                ->withFlashSuccess('StbLoginLog created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating StbLoginLog: ' . $e->getMessage());
        }
    }

    public function show(StbLoginLog $item)
    {
        return view('frontend.stb_login_logs.show', compact('item'));
    }

    public function edit(StbLoginLog $item)
    {
        return view('frontend.stb_login_logs.edit', compact('item'));
    }

    public function update(Request $request, StbLoginLog $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.stb_login_logs.index')
                ->withFlashSuccess('StbLoginLog updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating StbLoginLog: ' . $e->getMessage());
        }
    }

    public function destroy(StbLoginLog $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.stb_login_logs.index')
                ->withFlashSuccess('StbLoginLog deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting StbLoginLog: ' . $e->getMessage());
        }
    }
}
