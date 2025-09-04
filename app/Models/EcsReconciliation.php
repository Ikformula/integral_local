<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcsReconciliation extends Model
{
    protected $fillable = [
        'for_date',
        'ecs_sales_amount',
        'ibe_sales_amount',
        'amounts_difference',
        'comment',
        'agent_user_id'
    ];

    protected $dates = [
        'for_date',
    ];

    public function agent_user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'agent_user_id');
    }

}
