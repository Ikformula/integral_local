<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpiQuarterlyPerformance extends Model
{
    protected $fillable = [
        'spi_metric_id',
        'entry_data',
        'amount',
        'spi_metric_target_id',
        'user_id',
        'from_date',
        'to_date',
        'year',
        'quarter_number',
        'colour_flag'
    ];
}
