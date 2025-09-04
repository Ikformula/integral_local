<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StbRegistrationWindow extends Model
{
    protected $fillable = [
      'set_by_user_id',
      'window_year',
      'from_date',
      'to_date',
      'remarks'
    ];

    protected $dates = [
      'from_date',
      'to_date',
      'closed_at',
      'year',
    ];
}
