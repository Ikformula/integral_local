<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QaCategory extends Model
{
    protected $fillable = [
        'name',
        'parent_id'
    ];
    
    public function parent_idRelation()
    {
        return $this->belongsTo(QaCategory::class, 'parent_id');
    }

}
