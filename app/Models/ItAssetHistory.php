<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItAssetHistory extends Model
{
    use SoftDeletes;
    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
