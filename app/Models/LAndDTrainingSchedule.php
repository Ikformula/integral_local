<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LAndDTrainingSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'l_and_d_training_schedules';

    protected $fillable = [
        'course_id',
        'day',
        'holding_date',
        'start_time',
        'end_at',
    ];

    public function course()
    {
        return $this->belongsTo(LAndDTrainingCourse::class, 'course_id');
    }

    public function expectedAttendees()
    {
        return $this->hasMany(LAndDTrainingExpectedAttendee::class, 'training_schedule_id');
    }

    public function feedback()
    {
        return $this->hasMany(LAndDTrainingFeedback::class, 'training_schedule_id');
    }
}
