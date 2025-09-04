<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LAndDTrainingCourse extends Model
{
    use SoftDeletes;

    protected $table = 'l_and_d_training_courses';

    protected $fillable = [
        'title',
        'description',
        'is_virtual',
        'in_house',
        'facilitated_by_in_house',
        'facilitated_by',
        'venue',
        'held_from',
        'ended_at',
        'certificate_name',
        'cost_in_naira',
        'cost_in_dollars',
    ];

    public function schedules()
    {
        return $this->hasMany(LAndDTrainingSchedule::class, 'course_id');
    }

    public function materials()
    {
        return $this->hasMany(LAndDTrainingMaterial::class, 'course_id');
    }
}
