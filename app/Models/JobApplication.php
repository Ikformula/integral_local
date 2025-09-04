<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'staff_ara_id',
        'user_id',
        'vacancy_id',
        'academic_level',
        'highest_qualification',
        'professional_training_and_certifications',
        'relevant_experience',
        'supervisory_experience',
        'skills',
        'line_manager',
        'cv_file'
    ];

    public function staffMember()
    {
        return $this->belongsTo(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }

    public function lineManager()
    {
        return $this->belongsTo(StaffMember::class, 'line_manager', 'staff_ara_id');
    }
}
