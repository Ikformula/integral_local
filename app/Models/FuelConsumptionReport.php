<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelConsumptionReport extends Model
{
    protected $fillable = [
        'date_f',
        'vendors',
        'nf',
        'location',
        'destination',
        'flight_no',
        'adi_no',
        'invoiced',
        'calibration',
        'ac_reg',
        'mtr_after',
        'mtr_before',
        'uplifts',
        'unit_price',
        'debit',
    ];
}
