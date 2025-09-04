<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcsBookingTax extends Model
{
    protected $fillable = [
        'client_id',
        'booking_id',
        'tax_name',
        'amount',
    ];
}
