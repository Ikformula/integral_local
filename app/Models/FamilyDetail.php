<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyDetail extends Model
{
    protected $fillable = [
      'user_id',
        'staff_member_id',
        'surname',
        'first_name',
        'other_name',
        'gender',
        'relationship',
        'dob'
    ];

    public function getNameAttribute()
    {
        return $this->surname.' '.$this->first_name.' '.$this->other_name;
    }

    public function staff_member()
    {
        return $this->belongsTo(StaffMember::class);
    }
}
