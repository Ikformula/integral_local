<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightOpsSummary extends Model
{
    protected $fillable = [
        'user_id',
        'staff_ara_id',
        'month_year',
        'airline',
        'total_flights',
        'cnx_flights',
        'otp',
        'load_factor',
        'comp_factor',
        'no_of_pax',
        'ac_cap',
        'no_of_ac_utilised',
        'no_of_ac_available'
    ];
}
