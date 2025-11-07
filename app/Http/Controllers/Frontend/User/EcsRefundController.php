<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsRefund;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EcsClientTransactionsTrait;

class EcsRefundController extends Controller
{
    use EcsClientTransactionsTrait;

    public function index()
    {
        $items = EcsRefund::all();
        return view('frontend.ecs_refunds.index', compact('items'));
    }

    public function create()
    {
        return view('frontend.ecs_refunds.create');
    }

    public function createGroupRefunds()
    {
        return view('frontend.ecs_refunds.create-group');
    }

    public function store(Request $request)
    {
        try {

            // Duplicate check for single refund
            $duplicate = \App\Models\EcsRefund::where('client_id', $request->client_id)
                ->where('ticket_number', $request->ticket_number)
                ->where('amount_refundable', $request->amount_refundable)
                ->first();
            if ($duplicate) {
                return redirect()->back()->withErrors('Duplicate refund detected. No record saved.');
            }

            $refund = EcsRefund::create($request->all());

            $this->addTransactionSummary($refund->client_idRelation, $refund->amount_refundable, $refund);

            return redirect()->route('frontend.ecs_refunds.index')
                ->withFlashSuccess('EcsRefund created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating EcsRefund: ' . $e->getMessage());
        }
    }

    public function storeGroupRefunds(Request $request)
    {
        $arr = $request->all();
        foreach($request->gr_name as $key => $name){
            if(isset($name) && $name != ''){
                // Duplicate check for group refund
                $dup = EcsRefund::where('client_id', $arr['client_id'])
                    ->where('ticket_number', $request->gr_ticket_number[$key])
                    ->where('amount_refundable', $arr['amount_refundable'])
                    ->first();
                if ($dup) {
                    continue; // skip duplicate
                }

                $arr['name'] = $name;
                $arr['ticket_number'] = $request->gr_ticket_number[$key];
                $arr['cost_code'] = $request->gr_cost_code[$key];

                $refund = EcsRefund::create($arr);

                // Push to Summaries
                $this->addTransactionSummary($refund->client_idRelation, $refund->amount_refundable, $refund, 'CREDIT');
            }
        }

            return redirect()->route('frontend.ecs_refunds.index')
                ->withFlashSuccess('Refunds created successfully!');

    }

    public function show(EcsRefund $item)
    {
        return view('frontend.ecs_refunds.show', compact('item'));
    }

    public function edit(EcsRefund $item)
    {
        return view('frontend.ecs_refunds.edit', compact('item'));
    }

    public function update(Request $request, EcsRefund $item)
    {
        try {
            $item->update($request->all());
            return redirect()->route('frontend.ecs_refunds.index')
                ->withFlashSuccess('EcsRefund updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating EcsRefund: ' . $e->getMessage());
        }
    }

    public function destroy(EcsRefund $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.ecs_refunds.index')
                ->withFlashSuccess('EcsRefund deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting EcsRefund: ' . $e->getMessage());
        }
    }
}
