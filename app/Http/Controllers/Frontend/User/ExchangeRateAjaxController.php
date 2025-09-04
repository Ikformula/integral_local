<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateAjaxController extends Controller
{
    public $months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];

    public function index()
    {
        $exchange_rates = ExchangeRate::with([
            'entered_by_user_idRelation',
        ])->get();

        $now = now();
        $months = $this->months;
        return view('frontend.exchange_rates.index', compact('exchange_rates', 'now', 'months'));
    }

    public function store(Request $request)
    {
        $months = $this->months;
        $arr = $request->all();
        $arr['month_name'] = $months[$request->month_number];
        $arr['month_number'] = $request->month_number++;
        $year = $request->year;
        $month = $request->month_number; // month_number is 0-based
        $arr['from_date'] = date('Y-m-01', strtotime("$year-$month-01"));
        $arr['to_date'] = date('Y-m-t', strtotime("$year-$month-01"));

        ExchangeRate::create($arr);
        return back()->withFlashSuccess('Exchange Rates created successfully.');
    }

    public function update(Request $request, $id)
    {
        $exchange_rates = ExchangeRate::findOrFail($id);

        $months = $this->months;
        $arr = $request->all();
        $arr['month_name'] = $months[$request->month_number];
        $arr['month_number'] = $request->month_number++;
        $year = $request->year;
        $month = $request->month_number; // month_number is 0-based
        $arr['from_date'] = date('Y-m-01', strtotime("$year-$month-01"));
        $arr['to_date'] = date('Y-m-t', strtotime("$year-$month-01"));
        $exchange_rates->update($arr);
        return back()->withFlashSuccess('Exchange Rates updated successfully.');
    }

    public function destroy($id)
    {
        ExchangeRate::destroy($id);
        return back()->withFlashSuccess('Exchange Rates deleted successfully.');
    }
}
