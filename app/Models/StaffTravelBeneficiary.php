<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TracksDataChanges;

class StaffTravelBeneficiary extends Model
{
    use TracksDataChanges;

    protected $fillable = [
        'staff_ara_id',
        'firstname',
        'surname',
        'other_name',
        'dob',
        'gender',
        'relationship',
        'photo',
        'posted_by',
        'status',
        'actioned_by',
        'actioned_time',
        'actioned_comment'
    ];

    protected $dates = [
        'dob',
        'actioned_time'
    ];


    public function staff_member()
    {
        return $this->hasOne(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }

    protected $appends = ['photo_url'];

    public function posted_byRelation()
    {
        return $this->belongsTo(Auth\User::class, 'posted_by');
    }

    public function actioned_byRelation()
    {
        return $this->belongsTo(Auth\User::class, 'actioned_by');
    }

    public function actionedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User::class, 'actioned_by');
    }

    public function getPhotoUrlAttribute()
    {
        return asset('storage/' . $this->photo);
    }

    public function getNameAttribute()
    {
        return ($this->surname ? $this->surname : '').($this->firstname ? $this->firstname : '').($this->other_name ? $this->other_name : '');
    }
}
