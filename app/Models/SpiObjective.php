<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpiObjective extends Model
{
    public function indicators()
    {
        return $this->hasMany(SpiIndicator::class);
    }

    public function sector()
    {
        return $this->belongsTo(SpiSector::class, 'spi_sector_id');
    }


}
