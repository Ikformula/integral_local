<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logkeep extends Model
{
    protected $fillable = [
      'message_to',
      'message_from',
      'event_summary',
      'entered_by_user_id',
      'erp_id',
    ];
}
