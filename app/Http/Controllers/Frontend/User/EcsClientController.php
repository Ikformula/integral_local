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
    ];

    public function index()
    {
        $clients = EcsClient::all();
        return view('frontend.ecs_clients.index', compact('clients'));
    }

    public function create()
    {
        return view('frontend.ecs_clients.create')->with([
            'taxes' => $this->taxes
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

        $tax_columns = [];
        foreach ($request->tax_columns as $tax_column){
            $tax_columns[] = $tax_column;
        }

        $client->enabled_tax_columns = json_encode($tax_columns);
        if($request->current_balance != 0)
        $this->addTransactionSummary($client, $request->current_balance, $client, $request->current_balance > 0 ? 'credit' : 'debit');

        return redirect()->route('frontend.ecs_clients.index')->with('success', 'Client created successfully.');
    }

    public function show(EcsClient $ecs_client)
    {
        return view('frontend.ecs_clients.show', compact('ecs_client'));
    }

    public function edit(EcsClient $ecs_client)
    {
        return view('frontend.ecs_clients.edit', compact('ecs_client'))->with([
            'taxes' => $this->taxes
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

        $tax_columns = [];
        foreach ($request->tax_columns as $tax_column){
            $tax_columns[] = $tax_column;
        }

        $ecs_client->enabled_tax_columns = json_encode($tax_columns);
        $ecs_client->save();

        return redirect()->route('frontend.ecs_clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(EcsClient $ecs_client)
    {
        $ecs_client->delete();

        return redirect()->route('frontend.ecs_clients.index')->with('success', 'Client deleted successfully.');
    }

}
