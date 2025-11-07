<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use App\Models\ExternalVendor;
use App\Models\IcuActivity;
use App\Models\StaffMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\CurrencyTrait;

class IcuActivityAjaxController extends Controller
{
    use CurrencyTrait;

    public function index(Request $request)
    {
        // Default to present quarter
        $now = now();
        $quarter = ceil($now->month / 3);
        $quarterStart = $now->copy()->startOfQuarter();
        $quarterEnd = $now->copy()->endOfQuarter();
        $quarterLabel = 'Present Quarter (' . $quarterStart->format('M d, Y') . ' - ' . $quarterEnd->format('M d, Y') . ')';

        // Filters
        $filters = [
            'date_treated_from' => $request->get('date_treated_from', $quarterStart->toDateString()),
            'date_treated_to' => $request->get('date_treated_to', $quarterEnd->toDateString()),
            'created_from' => $request->get('created_from'),
            'created_to' => $request->get('created_to'),
            'category' => $request->get('category'),
            'department' => $request->get('department'),
            'trx_currency' => $request->get('trx_currency'),
            'vendor_id' => $request->get('vendor_id'),
            'beneficiary_staff_ara_id' => $request->get('beneficiary_staff_ara_id'),
            'min_amount' => $request->get('min_amount'),
            'max_amount' => $request->get('max_amount'),
        ];

        $query = IcuActivity::with([
            'vendor_idRelation',
            'beneficiary_staff_ara_idRelation',
            'entered_by_user_idRelation',
        ]);

        // Apply filters
        $query->whereBetween('date_treated', [$filters['date_treated_from'], $filters['date_treated_to']]);
        if ($filters['created_from']) $query->whereDate('created_at', '>=', $filters['created_from']);
        if ($filters['created_to']) $query->whereDate('created_at', '<=', $filters['created_to']);
        if ($filters['category']) $query->where('category', $filters['category']);
        if ($filters['department']) $query->where('department', $filters['department']);
        if ($filters['trx_currency']) $query->where('trx_currency', $filters['trx_currency']);
        if ($filters['vendor_id']) $query->where('vendor_id', $filters['vendor_id']);
        if ($filters['beneficiary_staff_ara_id']) $query->where('beneficiary_staff_ara_id', $filters['beneficiary_staff_ara_id']);
        if ($filters['min_amount']) $query->where(function ($q) use ($filters) {
            $q->where('naira_amount', '>=', $filters['min_amount'])
                ->orWhere('us_dollar_amount', '>=', $filters['min_amount'])
                ->orWhere('euro_amount', '>=', $filters['min_amount'])
                ->orWhere('gbp_amount', '>=', $filters['min_amount']);
        });
        if ($filters['max_amount']) $query->where(function ($q) use ($filters) {
            $q->where('naira_amount', '<=', $filters['max_amount'])
                ->orWhere('us_dollar_amount', '<=', $filters['max_amount'])
                ->orWhere('euro_amount', '<=', $filters['max_amount'])
                ->orWhere('gbp_amount', '<=', $filters['max_amount']);
        });

        $icu_activities = $query->get();

        // Stats
        $total_count = $icu_activities->count();
        $total_naira = $icu_activities->sum('naira_amount');
        $total_usd = $icu_activities->sum('us_dollar_amount');
        $total_euro = $icu_activities->sum('euro_amount');
        $total_gbp = $icu_activities->sum('gbp_amount');
        $unique_vendors = $icu_activities->pluck('vendor_id')->unique()->count();
        $unique_departments = $icu_activities->pluck('department')->unique()->count();

        $external_vendors = ExternalVendor::all();
        $staff_members = StaffMember::all();
        $categories = IcuActivity::select('category')->distinct()->pluck('category');
        $departments = IcuActivity::select('department')->distinct()->pluck('department');
        $currencies = ['NGN', 'USD', 'EUR', 'GBP'];

        // Top 10 vendors by resolved naira value in the filtered time range
        $topVendorsRaw = IcuActivity::with('vendor_idRelation')
            ->whereBetween('date_treated', [$filters['date_treated_from'], $filters['date_treated_to']])
            ->get()
            ->groupBy('vendor_id');

        $topVendors = $topVendorsRaw->map(function ($items, $vendorId) {
            $name = optional($items->first()->vendor_idRelation)->name ?? 'Unknown';
            $total = $items->sum('naira_value');
            return ['name' => $name, 'total' => round($total, 2)];
        })->sortByDesc('total')->take(10)->values()->all();

        return view('frontend.icu_activities.index', compact(
            'icu_activities',
            'external_vendors',
            'staff_members',
            'categories',
            'departments',
            'currencies',
            'filters',
            'quarterLabel',
            'total_count',
            'total_naira',
            'total_usd',
            'total_euro',
            'total_gbp',
            'unique_vendors',
            'unique_departments',
            'topVendors'
        ));
    }



    public function store(Request $request)
    {
        $arr = $request->all();
        $arr = $this->preArrange($request, $arr);
        $arr = $this->currencyValuesCalculator($arr);

        // Calculate cost savings for each currency
        $arr['cost_savings_naira'] = isset($arr['initial_naira_amount'], $arr['naira_amount']) ? ($arr['initial_naira_amount'] - $arr['naira_amount']) : null;
        $arr['cost_savings_usd'] = isset($arr['initial_us_dollar_amount'], $arr['us_dollar_amount']) ? ($arr['initial_us_dollar_amount'] - $arr['us_dollar_amount']) : null;
        $arr['cost_savings_euro'] = isset($arr['initial_euro_amount'], $arr['euro_amount']) ? ($arr['initial_euro_amount'] - $arr['euro_amount']) : null;
        $arr['cost_savings_gbp'] = isset($arr['initial_gbp_amount'], $arr['gbp_amount']) ? ($arr['initial_gbp_amount'] - $arr['gbp_amount']) : null;

        // Set status_changed_at if status is filled
        if (!empty($arr['status'])) {
            $arr['status_changed_at'] = now();
        }

        IcuActivity::create($arr);
        return back()->withFlashSuccess('Internal Control Activity Report created successfully.');
    }


    public function preArrange(Request $request, $arr)
    {
        $currencies = $this->currencies;

        foreach ($currencies as $code => $currency) {
            if ($request->has($currency['0'] . '_amount')) {
                $arr['trx_currency'] = $code;
                break;
            }
        }

        if ($request->filled('vendor_name')) {
            $vendor = ExternalVendor::firstOrCreate(
                ['name' => $request->vendor_name]
            );

            $arr['vendor_id'] = $vendor->id;
        }

        return $arr;
    }


    protected function currencyValuesCalculator($arr)
    {
        // Set currency values based on exchange rate for date treated
        if ($arr['date_treated'] ?? false) {
            $rate = ExchangeRate::where('from_date', '<=', $arr['date_treated'])
                ->where('to_date', '>=', $arr['date_treated'])
                ->orderByDesc('from_date')
                ->first();

            if ($rate) {
                $arr['naira_value'] = 0;
                $arr['euro_value'] = 0;
                $arr['us_dollar_value'] = 0;
                $arr['gbp_value'] = 0;
                switch ($arr['trx_currency']) {
                    case 'NGN':
                        $arr['naira_value'] = $arr['naira_amount'] ?? 0;
                        $arr['euro_value'] = $rate->eur ? ($arr['naira_amount'] / $rate->eur) : 0;
                        $arr['us_dollar_value'] = $rate->usd ? ($arr['naira_amount'] / $rate->usd) : 0;
                        $arr['gbp_value'] = $rate->gbp ? ($arr['naira_amount'] / $rate->gbp) : 0;
                        break;
                    case 'EUR':
                        $arr['euro_value'] = $arr['euro_amount'] ?? 0;
                        $arr['naira_value'] = $rate->eur ? ($arr['euro_amount'] * $rate->eur) : 0;
                        $arr['us_dollar_value'] = $rate->usd && $rate->eur ? ($arr['euro_amount'] * $rate->eur / $rate->usd) : 0;
                        $arr['gbp_value'] = $rate->gbp && $rate->eur ? ($arr['euro_amount'] * $rate->eur / $rate->gbp) : 0;
                        break;
                    case 'USD':
                        $arr['us_dollar_value'] = $arr['us_dollar_amount'] ?? 0;
                        $arr['naira_value'] = $rate->usd ? ($arr['us_dollar_amount'] * $rate->usd) : 0;
                        $arr['euro_value'] = $rate->eur && $rate->usd ? ($arr['us_dollar_amount'] * $rate->usd / $rate->eur) : 0;
                        $arr['gbp_value'] = $rate->gbp && $rate->usd ? ($arr['us_dollar_amount'] * $rate->usd / $rate->gbp) : 0;
                        break;
                    case 'GBP':
                        $arr['gbp_value'] = $arr['gbp_amount'] ?? 0;
                        $arr['naira_value'] = $rate->gbp ? ($arr['gbp_amount'] * $rate->gbp) : 0;
                        $arr['euro_value'] = $rate->eur && $rate->gbp ? ($arr['gbp_amount'] * $rate->gbp / $rate->eur) : 0;
                        $arr['us_dollar_value'] = $rate->usd && $rate->gbp ? ($arr['gbp_amount'] * $rate->gbp / $rate->usd) : 0;
                        break;
                }
            }
        }

        return $arr;
    }

    public function edit($id)
    {
        $icu_activitiesItem = IcuActivity::find($id);
        $external_vendors = ExternalVendor::all();
        $staff_members = StaffMember::all();
        $categories = IcuActivity::select('category')->distinct()->pluck('category');
        $departments = IcuActivity::select('department')->distinct()->pluck('department');
        $currencies = ['NGN', 'USD', 'EUR', 'GBP'];
        return view('frontend.icu_activities.edit', compact('icu_activitiesItem', 'external_vendors', 'staff_members', 'categories', 'departments', 'currencies'));
    }



    public function update(Request $request, $id)
    {
        $arr = $request->all();
        $arr = $this->preArrange($request, $arr);
        $arr = $this->currencyValuesCalculator($arr);

        // Calculate cost savings for each currency
        $arr['cost_savings_naira'] = isset($arr['initial_naira_amount'], $arr['naira_amount']) ? ($arr['initial_naira_amount'] - $arr['naira_amount']) : null;
        $arr['cost_savings_usd'] = isset($arr['initial_us_dollar_amount'], $arr['us_dollar_amount']) ? ($arr['initial_us_dollar_amount'] - $arr['us_dollar_amount']) : null;
        $arr['cost_savings_euro'] = isset($arr['initial_euro_amount'], $arr['euro_amount']) ? ($arr['initial_euro_amount'] - $arr['euro_amount']) : null;
        $arr['cost_savings_gbp'] = isset($arr['initial_gbp_amount'], $arr['gbp_amount']) ? ($arr['initial_gbp_amount'] - $arr['gbp_amount']) : null;

        $icu_activities = IcuActivity::findOrFail($id);
        // Set status_changed_at if status is filled and changed
        if (!empty($arr['status']) && $arr['status'] !== $icu_activities->status) {
            $arr['status_changed_at'] = now();
        }

        $icu_activities->update($arr);
        return back()->withFlashSuccess('Internal Control Activity Report updated successfully.');
    }

    public function destroy($id)
    {
        IcuActivity::destroy($id);
        return back()->withFlashSuccess('Internal Control Activity Report deleted successfully.');
    }
}
