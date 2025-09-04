<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TracksDataChanges;


class StaffTravelBooking extends Model
{
    use TracksDataChanges;

    public function staff_member()
    {
        return $this->belongsTo(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(StaffTravelBeneficiary::class, 'beneficiary_id', 'id');
    }
}
