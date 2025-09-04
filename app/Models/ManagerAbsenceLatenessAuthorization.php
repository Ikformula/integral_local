<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerAbsenceLatenessAuthorization extends Model
{
    protected $dates = [
      'start_date',
        'end_date',
    ];

    public function manager()
    {
        return $this->hasOne(StaffMember::class, 'staff_ara_id', 'manager_ara_id');
    }

    public function staff()
    {
        return $this->hasOne(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }

}
