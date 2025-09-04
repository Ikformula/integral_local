<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'url', 'method', 'accessed_at', 'duration', 'user_agent', 'ip_address'
    ];

    protected $dates = [
      'accessed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
