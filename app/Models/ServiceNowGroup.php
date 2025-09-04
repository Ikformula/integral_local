<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceNowGroup extends Model
{
    public function departments()
    {
        return $this->hasMany(Department::class, 'service_now_group_id');
    }

    public function agents()
    {
        return $this->belongsToMany(
            \App\Models\StaffMember::class,
            'service_now_group_agents',       // Pivot table
            'service_now_group_id',           // Foreign key on pivot table pointing to this model
            'staff_ara_id',                   // Foreign key on pivot table pointing to related model
            'id',                             // Local key on this model (ServiceNowGroup)
            'staff_ara_id'                    // Local key on related model (StaffMember)
        );
    }
}
