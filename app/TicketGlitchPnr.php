<?php

namespace App;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class TicketGlitchPnr extends Model
{
    protected $fillable = [
        'pnr',
        'agent_user_id',
        'user',
        'vpos',
        'operation_date',
        'operation_day',
        'order_id',
        'departure_date',
        'ticket_status',
        'ticketed_date',
        'staff_ara_id',
    ];

    public function agent(){
        return User::find($this->agent_user_id);
    }
}
