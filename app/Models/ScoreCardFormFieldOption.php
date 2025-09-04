<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreCardFormFieldOption extends Model
{
    protected $fillable = ['form_field_id', 'title', 'value'];
}
