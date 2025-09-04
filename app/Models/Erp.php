<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Erp extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'purpose',
        'remarks',
        'created_at',
        'updated_at',
    ];
}
