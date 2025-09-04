<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'status',
        'id_no',
        'surname',
        'other_names',
        'department_name',
        'location',
        'job_title',
        'grade',
        'location_2',
        'gross_pay_monthly',
        'staff_cadre',
        'nationality',
        'staff_category',
        'gender',
        'join_date',
        'end_date',
        'years_of_service',
        'rounded_up_years',
        'in_lieu',
        'one_month_gross_feyw',
        'redundancy_pay',
        'total_severance',
        'ext_till',
        'current_employment_status',
        'effective_date',
        'reason'
    ];
}
