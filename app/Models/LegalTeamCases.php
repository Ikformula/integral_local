<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class LegalTeamCases extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'external_lawyer_id',
        'firm'
    ];

    protected $table = 'legal_team_cases';

    public function user_idRelation()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(LegalTeamExternalLawyer::class, 'external_lawyer_id');
    }

}
