<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class EcsClientAccountSummary extends Model
{
    protected $fillable = [
        'client_id',
        'credit_amount',
        'ticket_number',
        'details',
        'debit_amount',
        'balance',
        'client_approved_at',
        'approver_client_user_id',
        'client_disputed_at',
        'disputer_client_user_id',
        'agent_user_id',
        'summarisable_id',
        'summarisable_type',
        'for_date'
    ];

    protected $dates = [
        'for_date',
    ];

    public function client_idRelation()
    {
        return $this->belongsTo(EcsClient::class, 'client_id');
    }

    public function bookingId()
    {
        $ticket = EcsFlightTransaction::where('ticket_number', $this->ticket_number)->first();
        if ($ticket)
            return $ticket->ecs_booking_id;

        return null;
    }

    public function ticket()
    {
//        $ticket = EcsFlightTransaction::where('ticket_number', $this->ticket_number)->first();
        $ticket = EcsFlightTransaction::find($this->summarisable_id);
        if ($ticket)
            return $ticket;

        return null;
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }

    public function getStatusChangeDateAttribute()
    {
        if (isset($this->client_approved_at) && is_null($this->client_disputed_at))
            return $this->client_approved_at;

        if (is_null($this->client_approved_at) && isset($this->client_disputed_at))
            return $this->client_disputed_at;

        if (isset($this->client_approved_at) && isset($this->client_disputed_at))
            return max(array_map('strtotime', [$this->client_approved_at, $this->client_disputed_at]));

        return null;
    }
    public function summarisable()
    {
        return $this->morphTo();
    }
}
