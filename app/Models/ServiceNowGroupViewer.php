<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceNowGroupViewer extends Model
{
    protected $fillable = [
        'user_id',
        'staff_ara_id',
        'service_now_group_id',
        'can_view_all_tickets'
    ];

    public function user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'user_id');
    }

    public function staff_ara_idRelation()
    {
        return $this->belongsTo(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }

    public function service_now_group_idRelation()
    {
        return $this->belongsTo(ServiceNowGroup::class, 'service_now_group_id');
    }

}
