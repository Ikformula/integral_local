<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcsPortalUser extends Model
{
    protected $fillable = [
        'user_id',
        'staff_ara_id',
        'role',
        'role_id',
        'added_by'
    ];

    public function user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'user_id');
    }

    public function added_byRelation()
    {
        return $this->belongsTo(Auth\User::class, 'added_by');
    }

}
