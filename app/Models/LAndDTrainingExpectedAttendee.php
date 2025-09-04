<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LAndDTrainingExpectedAttendee extends Model
{
    use SoftDeletes;

    protected $table = 'l_and_d_training_expected_attendees';

    protected $fillable = [
        'staff_ara_id',
        'training_schedule_id',
    ];

    public function schedule()
    {
        return $this->belongsTo(LAndDTrainingSchedule::class, 'training_schedule_id');
    }
}
