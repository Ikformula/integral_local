<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
      'user_id',
      'staff_ara_id',
      'reg_number',
      'colour',
      'maker',
      'model',
      'type',
    ];

    public function staffMember()
    {
        return StaffMember::where('staff_ara_id', $this->staff_ara_id)->first();
    }
}
