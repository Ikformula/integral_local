<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ServiceNowGroupViewer;
use Illuminate\Http\Request;

class ServiceNowGroupViewerController extends Controller
{
    public function index()
    {
        $items = ServiceNowGroupViewer::all();
        return view('frontend.service_now_group_viewers.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.service_now_group_viewers.create');
    }

    public function store(Request $request)
    {
        try {
            ServiceNowGroupViewer::create($request->all());
            return redirect()->route('frontend.service_now_group_viewers.index')
                ->withFlashSuccess('ServiceNowGroupViewer created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating ServiceNowGroupViewer: ' . $e->getMessage());
        }
    }

    public function show(ServiceNowGroupViewer $item)
    {
        return view('frontend.service_now_group_viewers.show', compact('item'));
    }

    public function edit(ServiceNowGroupViewer $item)
    {
        return view('frontend.service_now_group_viewers.edit', compact('item'));
    }

    public function update(Request $request, ServiceNowGroupViewer $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.service_now_group_viewers.index')
                ->withFlashSuccess('ServiceNowGroupViewer updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating ServiceNowGroupViewer: ' . $e->getMessage());
        }
    }

    public function destroy(ServiceNowGroupViewer $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.service_now_group_viewers.index')
                ->withFlashSuccess('ServiceNowGroupViewer deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting ServiceNowGroupViewer: ' . $e->getMessage());
        }
    }
}
