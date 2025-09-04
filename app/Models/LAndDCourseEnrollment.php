<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LAndDCourseEnrollment extends Model
{
    use SoftDeletes;

    protected $table = 'l_and_d_course_enrollments';

    protected $fillable = [
        'staff_ara_id',
        'attended_from',
        'completed_at',
        'progress_in_percentage',
        'certified_at',
        'certificate_file_path',
        'expended_amount',
    ];
}
