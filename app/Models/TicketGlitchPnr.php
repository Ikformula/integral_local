<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class TicketGlitchPnr extends Model
{
    protected $fillable = [
        'agent_user_id', 'created_at', 'deleted_at', 'departure_date', 'operation_date', 'operation_day', 'order_id', 'pnr', 'staff_ara_id', 'ticket_status', 'ticketed_date', 'updated_at', 'user', 'vpos'
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }
}
