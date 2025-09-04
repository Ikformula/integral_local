<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeekRange extends Model
{
    protected $dates = [
      'from_date',
      'to_date',
    ];

    public function getFromDayAttribute(){
        return $this->from_date->format('l, F j, Y');
    }

    public function getToDayAttribute(){
        return $this->to_date->format('l, F j, Y');
    }
}
