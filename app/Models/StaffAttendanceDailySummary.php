<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAttendanceDailySummary extends Model
{
//    protected $with = [
//      'staff_member'
//    ];

    public function staff_member()
    {
        return $this->belongsTo(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }
}
