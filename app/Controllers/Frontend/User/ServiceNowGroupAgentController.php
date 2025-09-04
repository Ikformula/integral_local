<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ServiceNowGroupAgent;
use Illuminate\Http\Request;

class ServiceNowGroupAgentController extends Controller
{
    public function index()
    {
        $items = ServiceNowGroupAgent::all();
        return view('frontend.service_now_group_agents.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.service_now_group_agents.create');
    }

    public function store(Request $request)
    {
        try {
            ServiceNowGroupAgent::create($request->all());
            return redirect()->route('frontend.service_now_group_agents.index')
                ->withFlashSuccess('ServiceNowGroupAgent created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating ServiceNowGroupAgent: ' . $e->getMessage());
        }
    }

    public function show(ServiceNowGroupAgent $item)
    {
        return view('frontend.service_now_group_agents.show', compact('item'));
    }

    public function edit(ServiceNowGroupAgent $item)
    {
        return view('frontend.service_now_group_agents.edit', compact('item'));
    }

    public function update(Request $request, ServiceNowGroupAgent $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.service_now_group_agents.index')
                ->withFlashSuccess('ServiceNowGroupAgent updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating ServiceNowGroupAgent: ' . $e->getMessage());
        }
    }

    public function destroy(ServiceNowGroupAgent $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.service_now_group_agents.index')
                ->withFlashSuccess('ServiceNowGroupAgent deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting ServiceNowGroupAgent: ' . $e->getMessage());
        }
    }
}
