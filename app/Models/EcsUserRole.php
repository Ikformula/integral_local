<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcsUserRole extends Model
{
    protected $fillable = [
      'user_id',
      'staff_ara_id',
      'role_name',
      'role_id',
        'assigned'
    ];
}
