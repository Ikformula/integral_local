<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'gbp',
        'usd',
        'eur',
        'month_name',
        'month_number',
        'year',
        'entered_by_user_id',
        'from_date',
        'to_date'
    ];
    
    public function entered_by_user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'entered_by_user_id');
    }

}
