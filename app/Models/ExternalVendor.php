<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalVendor extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];
    
}
