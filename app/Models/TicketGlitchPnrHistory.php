<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketGlitchPnrHistory extends Model
{
    protected $fillable = [
        'agent_user_id', 'created_at', 'deleted_at', 'departure_date', 'operation_date', 'operation_day', 'order_id', 'pnr', 'staff_ara_id', 'ticket_glitch_pnr_id', 'ticket_status', 'ticketed_date', 'updated_at', 'user', 'vpos'
    ];
}
