<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirlineFare extends Model
{
    protected $fillable = [
        'amount', 'airline_id', 'departure_time', 'departure_date', 'class_name',
        'depart_from_port', 'arrive_at_port', 'direction', 'checked_at',
        'created_at', 'updated_at'
    ];

    protected $dates = [
      'departure_date',
        'checked_at'
    ];

    public function airline(){
        return $this->belongsTo(Airline::class, 'airline_id', 'id');
    }
}
