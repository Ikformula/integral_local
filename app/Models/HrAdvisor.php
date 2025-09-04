<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrAdvisor extends Model
{
    protected $fillable = [
        'set_by_user_id',
        'department_name',
        'staff_ara_id'
    ];

    public function staff_member()
    {
        return $this->hasOne(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }
}
