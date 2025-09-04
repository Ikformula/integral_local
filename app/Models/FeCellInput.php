<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeCellInput extends Model
{
    protected $fillable = [
      'flight_envelope_id',
      'cell_name',
      'cell_value',
    ];

    public function cellType()
    {
        return $this->belongsTo(FeCell::class, 'fe_cell_id');
    }
}
