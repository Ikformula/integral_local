<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QaLetter extends Model
{
    protected $fillable = [
        'Organization',
        'external_reference',
        'internal_reference',
        'department',
        'description',
        'administrator_ara_id',
        'file_path',
        'category_id',
        'for_date',
        'status',
        'status_last_changed_at',
        'updater_user_id',
        'direction',
    ];

    protected $dates = [
        'status_last_changed_at'
    ];

    public function category_idRelation()
    {
        return $this->belongsTo(QaCategory::class, 'category_id');
    }

}
