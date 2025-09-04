<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPointHistories extends Model
{
    protected $fillable = ['data_point_id', 'week_range_id', 'name', 'data_value', 'is_computed', 'presenter_id', 'for_date'];
}
