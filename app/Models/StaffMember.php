<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffMember extends Model
{
    use SoftDeletes;
    protected $table = 'staff_member_details';

    protected $fillable = [
        'staff_id', 'staff_ara_id', 'id_card_file_name', 'email', 'surname', 'other_names',
        'department_name', 'department_name_2', 'manager_ara_id', 'department_id', 'unit',
        'id_remarks', 'id_expiry_date', 'location_in_hq', 'status', 'resigned_on',
        'restrict_access_from', 'staff_travel_blocked_at', 'stb_access_code',
        'stb_access_code_expires_at', 'paypoint', 'location', 'location_2', 'job_title',
        'grade', 'current_employment_status', 'staff_cadre', 'staff_category',
        'employment_category', 'gender', 'age', 'years_of_service', 'start_date',
        'region', 'state', 'local_government_area', 'marital_status', 'shift_nonshift',
        'deactivated_at', 'deactivate_from', 'leavers_reason'
    ];

    protected $dates = [
      'stb_access_code_expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function staffTravelBeneficiaries()
    {
        return $this->hasMany(StaffTravelBeneficiary::class, 'staff_ara_id', 'staff_ara_id');
    }

    public function getNameAttribute()
    {
        return is_null($this->surname) ? $this->other_names : $this->surname.(!is_null($this->other_names) ? ' '.$this->other_names : '');
    }

//    public function family_details()
//    {
//        return $this->hasMany(FamilyDetail::class, 'staff_member_id');
//    }

//    public function contact_info($category, $data_type = 'phone number')
//    {
//        $contact_info = $this->user->contact_details->where('contact_category', $category)->first();
//        if($contact_info){
//            return $contact_info->value;
//        }
//
//        return '';
//    }

//    public function travel_bookings()
//    {
//        return $this->hasMany(StaffTravelBooking::class, 'staff_ara_id', 'staff_ara_id');
//    }

    public function scopePaginateasc($query)
    {
        return $query->paginate(50)->sortBy('staff_ara_id', 'ASC');
    }

    public function pdf_read($pdfFile_id)
    {
        return PdfRead::where('staff_ara_id', $this->staff_ara_id)->where('pdf_id', $pdfFile_id)->first();
    }

//    public function attendances()
//    {
//        return $this->hasMany(StaffAttendance::class, 'staff_ara_id', 'staff_ara_id');
//    }

    public function manager()
    {
        return $this->belongsTo(StaffMember::class, 'manager_ara_id', 'staff_ara_id');
    }

    public function getFullNameAttribute()
    {
        return ($this->surname ?? '').' '.($this->other_names ?? '');
    }

    public function getNameAndAraAttribute()
    {
        return $this->full_name.' ('.$this->staff_id.')';
    }

    public function appliedVacancies()
    {
        return Vacancy::whereIn('id', JobApplication::where('staff_ara_id', $this->staff_ara_id)->pluck('vacancy_id')->toArray())->get();
    }

    public function nonAppliedVacancies()
    {
        return Vacancy::whereNotIn('id', $this->appliedVacancies()->pluck('id')->toArray())->get();
    }

    public function serviceNowViewables()
    {
        return $this->hasMany(ServiceNowGroupViewer::class, 'staff_ara_id', 'staff_ara_id');
    }

    public function advisorTo()
    {
        return $this->hasMany(HrAdvisor::class, 'staff_ara_id', 'staff_ara_id');
    }

    public function advisees()
    {
        return $this->hasManyThrough(
            StaffMember::class,        // Final model
            HrAdvisor::class,          // Intermediate model
            'staff_ara_id',            // Foreign key on HrAdvisor table
            'department_name',         // Local key on StaffMember table
            'staff_ara_id',            // Local key on this model (the advisor)
            'department_name'          // Foreign key on StaffMember (matching department)
        );
    }

    public function getAdviseeBeneficiaries()
    {
        $departments = $this->advisorTo()->pluck('department_name');

        return StaffTravelBeneficiary::whereIn('staff_ara_id', function ($query) use ($departments) {
            $query->select('staff_ara_id')
                ->from('staff_member_details')
                ->whereIn('department_name', $departments);
        })->get();
    }

    public function advisedBeneficiaries()
    {
        $departments = $this->advisorTo()->pluck('department_name');

        return StaffTravelBeneficiary::whereIn('staff_ara_id', function ($query) use ($departments) {
            $query->select('staff_ara_id')
                ->from('staff_member_details')
                ->whereIn('department_name', $departments);
        })->get();
    }


}
