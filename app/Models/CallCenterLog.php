<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\PropertiesIterator;

class CallCenterLog extends Model
{
    // use PropertiesIterator;
    protected $fillable = [
        'passenger_name',
        'passenger_mobile_number',
        'passenger_email_address',
        'passenger_location',
        'ticket_fare',
        'date_of_call',
        'flight_route',
        'flight_time',
        'pnr',
        'class_of_booking',
        'call_purpose',
        'type_of_call',
        'footnote',
        'agent_user_id',
        'receiving_phone_number',
        'supervisors',
        'duration'
    ];

    protected $dates = [
        'date_of_call'
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }
}
