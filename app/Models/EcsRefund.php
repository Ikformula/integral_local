<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcsRefund extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'ticket_number',
        'booking_reference',
        'route',
        'travel_date',
        'ticket_class',
        'amount_refundable',
        'remarks',
        'agent_user_id',
        'for_date',
    ];

    protected $dates = [
        'for_date',
    ];

    public function client_idRelation()
    {
        return $this->belongsTo(EcsClient::class, 'client_id');
    }

    public function agent_user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'agent_user_id');
    }

}
