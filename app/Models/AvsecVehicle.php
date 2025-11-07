<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvsecVehicle extends Model
{
    protected $fillable = [
        'staff_ara_id',
        'car_model',
        'colour',
        'brand',
        'reg_number',
        'sticker_number',
        'attended_by_user_id',
        'registration_cert',
        'proof_of_ownership',
        'registered_name_on_vehicle',
        'vehicle_type',
        'effective_date',
        'expiration_date',
        'sticker_category_id',
        'approved_at',
        'disapproved_at',
        'disapproval_reason',
        'line_manager_staff_ara_id',
        'opened_for_editing',
    ];

    protected $dates = [
        'approved_at',
        'disapproved_at',
        'effective_date',
        'expiration_date',
    ];

    public function staff_ara_idRelation()
    {
        return $this->belongsTo(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }

    public function attended_by_user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'attended_by_user_id');
    }

    public function stickerCategory()
    {
        return $this->belongsTo(AvsecVehicleStickerCategory::class, 'sticker_category_id');
    }

    public function line_manager_staff_ara_idRelation()
    {
        return $this->belongsTo(StaffMember::class, 'line_manager_staff_ara_id', 'staff_ara_id');
    }

    public function status_symbol()
    {
        if (is_null($this->approved_at) && is_null($this->disapproved_at))
            return 'Pending';

        if ($this->approved_at)
            return 'Approved';

        return 'Disapproved';
    }
}
