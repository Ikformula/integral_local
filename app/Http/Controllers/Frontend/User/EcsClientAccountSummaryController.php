<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsClient;
use App\Models\EcsClientAccountSummary;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EcsClientTransactionsTrait;

class EcsClientAccountSummaryController extends Controller
{
    use EcsClientTransactionsTrait;

    public function index(Request $request)
    {
//        $items = EcsClientAccountSummary::all();
//        return view('frontend.ecs_client_account_summaries.index', compact('items'));
        return $this->summariesList($request);
    }

    public function create(Request $request)
    {
        if($request->filled('client_id'))
            return view('frontend.ecs_client_account_summaries.create')->with(['client' => EcsClient::find($request->client_id)]);

        return view('frontend.ecs_client_account_summaries.create');
    }

    public function store(Request $request)
    {
        try {
            $type = $request->trx_type;
            $this->addTransactionSummary(EcsClient::find($request->client_id), $request->amount, null, $type, 'summary', $request->details);
            return redirect()->back()
                ->withFlashSuccess('Client Account Summary created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating Client Account Summary: ' . $e->getMessage());
        }
    }

    public function show(EcsClientAccountSummary $item)
    {
        return view('frontend.ecs_client_account_summaries.show', compact('item'));
    }

    public function edit(EcsClientAccountSummary $item)
    {
        return view('frontend.ecs_client_account_summaries.edit', compact('item'));
    }

    public function update(Request $request, EcsClientAccountSummary $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.ecs_client_account_summaries.index')
                ->withFlashSuccess('Client Account Summary updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating Client Account Summary: ' . $e->getMessage());
        }
    }

    public function destroy(EcsClientAccountSummary $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.ecs_client_account_summaries.index')
                ->withFlashSuccess('Client Account Summary deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting Client Account Summary: ' . $e->getMessage());
        }
    }
}
