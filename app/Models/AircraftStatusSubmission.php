<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AircraftStatusSubmission extends Model
{
    protected $fillable = [
      'checklist_id',
      'user_id',
      'aircraft_id',
      'item_value',
      'for_date'
    ];
}
