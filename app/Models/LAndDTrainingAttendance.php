<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LAndDTrainingAttendance extends Model
{
    use SoftDeletes;

    protected $table = 'l_and_d_training_attendances';

    protected $fillable = [
        'training_schedule_id',
        'staff_ara_id',
        'direction',
        'marked_by_user_id',
    ];

    public function schedule()
    {
        return $this->belongsTo(LAndDTrainingSchedule::class, 'training_schedule_id');
    }
}
