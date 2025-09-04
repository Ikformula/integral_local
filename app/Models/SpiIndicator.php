<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpiIndicator extends Model
{
    public function metrics()
    {
        return $this->hasMany(SpiMetric::class);
    }

    public function objective()
    {
        return $this->belongsTo(SpiObjective::class, 'spi_objective_id');
    }

}
