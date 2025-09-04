<?php


namespace App\Http\Controllers\Traits;


trait CurrencyTrait
{
    public $currencies = [
        'NGN' => ['naira', 'Naira'],
        'GBP' => ['gbp', 'Great British Pounds'],
        'USD' => ['us_dollar', 'US Dollars'],
        'EUR' => ['euro', 'Euros']
    ];
}
