<?php

namespace App\Models;
use App\Models\Traits\TracksDataChanges;

use Illuminate\Database\Eloquent\Model;

class SpiMetric extends Model
{
    use TracksDataChanges;

    public function targets()
    {
        return $this->hasMany(SpiMetricTarget::class, 'spi_metric_id');
    }

    public function indicator()
    {
        return $this->belongsTo(SpiIndicator::class, 'spi_indicator_id');
    }

    public function entries()
    {
        return $this->hasMany(SpiMetricEntry::class, 'spi_metric_id');
    }

    public function quarterlyPerformances()
    {
        return $this->hasMany(SpiQuarterlyPerformance::class, 'spi_metric_id', 'id');
    }

}
