<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OccasionMessage extends Model
{
    protected $fillable = [
        'user_id',
        'occasion_id',
        'displayed_name',
        'message_body',
        'writer_title'
    ];
}
