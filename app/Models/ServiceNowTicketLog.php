<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class ServiceNowTicketLog extends Model
{
    public function user(){
        return $this->belongsTo(User::class, 'triggerer_user_id', 'id');
    }
}
