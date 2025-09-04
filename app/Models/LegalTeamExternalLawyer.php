<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class LegalTeamExternalLawyer extends Model
{
    protected $fillable = [
//        'first_name',
//        'last_name',
//        'email',
        'firm',
        'notes'
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
