<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataChangeHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'staff_ara_id',
        'table_name',
        'model_name',
        'record_id',
        'previous_data',
    ];

    protected $casts = [
        'previous_data' => 'array',
    ];
}
