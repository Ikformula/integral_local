<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAttendanceWeeklySummary extends Model
{
    protected $fillable = [
        'staff_ara_id',
        'week_range_id',
        'week_number_in_month',
        'month',
        'year',
        'late',
        'absent',
        'total_work_hours',
        'early_leaving',
        'remarks_and_reasons',
    ];

    public function staffMember()
    {
        return $this->belongsTo(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }
}
