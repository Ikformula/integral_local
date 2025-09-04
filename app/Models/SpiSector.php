<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpiSector extends Model
{
    public function objectives()
    {
        return $this->hasMany(SpiObjective::class);
    }

}
