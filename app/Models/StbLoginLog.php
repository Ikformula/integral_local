<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StbLoginLog extends Model
{
    protected $fillable = [
        'staff_ara_id',
        'ip_address',
        'logged_in_at',
        'session_id'
    ];

    public function staff_member()
    {
        return $this->hasOne(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }
}
