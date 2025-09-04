<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalTeamFolderAccess extends Model
{
    protected $fillable = [
        'user_id',
        'folder_id'
    ];

    public function user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'user_id');
    }

    public function folder_idRelation()
    {
        return $this->belongsTo(LegalTeamFolder::class, 'folder_id');
    }

}
