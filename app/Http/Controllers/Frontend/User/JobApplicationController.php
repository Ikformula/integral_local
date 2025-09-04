<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\StaffMember;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function store(Request $request, Vacancy $vacancy)
    {
        $arr['user_id'] = auth()->id();
        if($request->hasFile('cv_file')) {
            $staff = StaffMember::where('staff_ara_id', $request->staff_ara_id)->first();
            if($staff){
                $prefix = str_replace(' ', '_', $staff->full_name).'_';
            }else{
                $prefix = '_';
            }
            $timestamp = now()->format('Y-m-d_H-i-s');
            $imageName = $request->vacancy_id.'_'.$prefix.$request->staff_ara_id. $arr['user_id'] .'_'. $timestamp . '.' . $request->cv_file->extension();
            $request->cv_file->move(public_path('/job_vacancies/cv_files'), $imageName);
            $arr['cv_file'] = $imageName;
        }

        $job_application = JobApplication::create(array_merge($request->all(), $arr));
        return back()->withFlashSuccess('Application submitted');
    }
}
