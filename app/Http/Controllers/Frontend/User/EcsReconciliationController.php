<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsReconciliation;
use Illuminate\Http\Request;

class EcsReconciliationController extends Controller
{
    public function index()
    {
        $items = EcsReconciliation::orderBy('id', 'DESC')->get();
        return view('frontend.ecs_reconciliations.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.ecs_reconciliations.create');
    }

    public function store(Request $request)
    {
        try {
            $arr = $request->all();
            $arr['difference'] = $request->ecs_sales_amount - $request->ibe_sales_amount;
            EcsReconciliation::create($arr);
            return redirect()->back()
                ->withFlashSuccess('EcsReconciliation created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating EcsReconciliation: ' . $e->getMessage());
        }
    }

    public function show(EcsReconciliation $item)
    {
        return view('frontend.ecs_reconciliations.show', compact('item'));
    }

    public function edit(EcsReconciliation $item)
    {
        return view('frontend.ecs_reconciliations.edit', compact('item'));
    }

    public function update(Request $request, EcsReconciliation $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.ecs_reconciliations.index')
                ->withFlashSuccess('EcsReconciliation updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating EcsReconciliation: ' . $e->getMessage());
        }
    }

    public function destroy(EcsReconciliation $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.ecs_reconciliations.index')
                ->withFlashSuccess('EcsReconciliation deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting EcsReconciliation: ' . $e->getMessage());
        }
    }
}
