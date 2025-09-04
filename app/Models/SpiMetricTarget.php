<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpiMetricTarget extends Model
{

    protected $fillable = [
        'spi_metric_id',
        'from_date',
        'to_date',
        'year',
        'should_surpass_target',
        'green_left_limit',
        'green_right_limit',
        'yellow_left_limit',
        'yellow_right_limit',
        'red_left_limit',
        'red_right_limit',
    ];

}
