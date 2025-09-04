<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $fillable = [
        'position',
        'eligible_grade',
        'proposed_grade',
        'date_advertised',
        'date_of_closing',
        'mode_of_sourcing',
        'department',
        'recruiter',
        'job_description',
        'job_description_doc_path',
        'location'
    ];

    protected $dates = [
        'date_advertised',
        'date_of_closing',
    ];

    public function applicationsCount()
    {
        return JobApplication::where('vacancy_id', $this->id)->count();
    }
}
