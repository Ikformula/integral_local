<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LAndDTrainingMaterial extends Model
{
    use SoftDeletes;

    protected $table = 'l_and_d_training_materials';

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'file_path',
        'size',
        'type',
    ];

    public function course()
    {
        return $this->belongsTo(LAndDTrainingCourse::class, 'course_id');
    }
}
