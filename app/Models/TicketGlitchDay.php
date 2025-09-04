<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketGlitchDay extends Model
{
    protected $fillable = [
      'glitches_date',
      'reports_file_name',
      'staff_ara_id',
    ];
}
