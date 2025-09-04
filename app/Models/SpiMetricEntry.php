<?php

namespace App\Models;
use App\Models\Traits\TracksDataChanges;

use Illuminate\Database\Eloquent\Model;

class SpiMetricEntry extends Model
{
    use TracksDataChanges;

    protected $fillable = [
        'spi_metric_id',
        'amount',
        'entry_data',
        'spi_metric_target_id',
        'user_id',
        'for_date',
        'month',
        'year',
        'quarter_number',
        'colour_flag',
        'centrik_confirmed_by_staff_ara_id',
        'centrik_confirmed_at',
    ];

    public function metric()
    {
        return $this->belongsTo(SpiMetric::class, 'spi_metric_id');
    }
}
