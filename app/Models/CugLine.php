<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class CugLine extends Model
{

    protected $fillable = [
        'first_name',
        'surname',
        'other_names',
        'staff_ara_id',
        'phone_number',
        'service_provider',
        'phone_type',
        'phone_model',
        'confirmed_by',
        'confirmed_at',
        'user_supplied_phone_number',
        'dob',
        'nin',
        'serial_number',
        'notes',
    ];

//    public function getStaffIdAttribute()
//    {
//        return $this->staff_ara_id ? 'ARA' . $this->staff_ara_id : 'N/A';
//    }

    public function staffMember()
    {
        return $this->hasOne(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
