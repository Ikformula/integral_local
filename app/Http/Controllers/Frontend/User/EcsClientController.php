<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\EcsClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\EcsClientTransactionsTrait;
use Illuminate\Validation\Rule;

class EcsClientController extends Controller
{
    use EcsClientTransactionsTrait;
    protected $taxes = [
        'NUC',
        'QT',
        'NG',
        'NG (VAT)',
        'JY (VAT)',
        'JY (NC)',
        'YQ (CO)',
        'OTHER REGIONAL TAXES',
    ];

    protected $additional_fees = [
      'no_show_fee',
        'excess_baggage_charge',
        'date_change_fee',
        'name_change_fee',
        'reroute_fee',
    ];

    public function index()
    {
        $clients = EcsClient::all();
        return view('frontend.ecs_clients.index', compact('clients'));
    }

    public function create()
    {
        return view('frontend.ecs_clients.create')->with([
            'taxes' => $this->taxes,
            'additional_fees' => $this->additional_fees
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'current_balance' => 'required|string',
            'service_charge_amount' => 'required|string',
            'deal_code' => 'nullable|string|max:255',
            'account_type' => ['required', 'string', 'max:255', Rule::in(['PREPAID', 'POSTPAID'])],
        ]);

        $client = EcsClient::create($request->except(['current_balance']));
        if($request->select_category == 1)
            $client->select_category = 1;

        if($request->filled('tax_columns') && count($request->tax_columns)) {
            $tax_columns = [];
            foreach ($request->tax_columns as $tax_column) {
                $tax_columns[] = $tax_column;
            }

            $client->enabled_tax_columns = json_encode($tax_columns);
        }

        if($request->filled('fee_columns') && count($request->fee_columns)) {
            $fee_columns = [];
            foreach ($request->fee_columns as $fee_column) {
                $fee_columns[] = $fee_column;
            }

            $client->enabled_fee_columns = json_encode($fee_columns);
        }

        $client->save();
        if($request->current_balance != 0)
        $this->addTransactionSummary($client, $request->current_balance, $client, $request->current_balance > 0 ? 'credit' : 'debit');

        return redirect()->route('frontend.ecs_clients.index')->with('flash_success', 'Client created successfully.');
    }

    public function show(EcsClient $ecs_client)
    {
        return view('frontend.ecs_clients.show', compact('ecs_client'));
    }

    public function edit(EcsClient $ecs_client)
    {
        return view('frontend.ecs_clients.edit', compact('ecs_client'))->with([
            'taxes' => $this->taxes,
            'additional_fees' => $this->additional_fees
        ]);
    }

    public function update(Request $request, EcsClient $ecs_client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
//            'current_balance' => 'required|string',
            'service_charge_amount' => 'required|string',
            'deal_code' => 'nullable|string|max:255',
            'account_type' => ['required', 'string', 'max:255', Rule::in(['PREPAID', 'POSTPAID'])],
        ]);

        $ecs_client->update($validated);

        if(!is_null($request->select_category)) {
            $ecs_client->select_category = 1;
        }else{
            $ecs_client->select_category = null;
        }

        if($request->filled('tax_columns') && count($request->tax_columns)) {
            $tax_columns = [];
            foreach ($request->tax_columns as $tax_column) {
                $tax_columns[] = $tax_column;
            }

            $ecs_client->enabled_tax_columns = json_encode($tax_columns);
        }

        if($request->filled('fee_columns') && count($request->fee_columns)) {
            $fee_columns = [];
            foreach ($request->fee_columns as $fee_column) {
                $fee_columns[] = $fee_column;
            }

            $ecs_client->enabled_fee_columns = json_encode($fee_columns);
        }

        $ecs_client->enabled_tax_columns = json_encode($tax_columns);
        $ecs_client->save();

        return redirect()->back()->with('flash_success', 'Client updated successfully.');
        return redirect()->route('frontend.ecs_clients.index')->with('flash_success', 'Client updated successfully.');
    }

    public function destroy(EcsClient $ecs_client)
    {
        $ecs_client->delete();

        return redirect()->route('frontend.ecs_clients.index')->with('flash_success', 'Client deleted successfully.');
    }

}
