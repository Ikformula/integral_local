<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreCard extends Model
{
    protected $fillable = ['week_range_id', 'business_area_id', 'body_html'];
}
