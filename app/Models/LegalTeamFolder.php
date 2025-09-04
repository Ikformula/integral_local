<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalTeamFolder extends Model
{
    protected $fillable = [
        'name',
        'parent_id'
    ];

    public function parent_idRelation()
    {
        return $this->belongsTo(LegalTeamFolder::class, 'parent_id');
    }

    public function childrenFolders()
    {
        return $this->hasMany(LegalTeamFolder::class, 'parent_id');
    }

    public function parentPath()
    {
        return $this->parent_idRelation ? $this->parent_idRelation->parentPath() . $this->parent_idRelation->name.' / ' : '';
    }

    public function documents()
    {
        return $this->hasMany(LegalTeamDocument::class, 'folder_id');
    }

}
