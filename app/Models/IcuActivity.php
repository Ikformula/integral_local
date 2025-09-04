<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IcuActivity extends Model
{
    protected $fillable = [
        'category',
        'description',
        'department',
        'naira_amount',
        'us_dollar_amount',
        'euro_amount',
        'gbp_amount',
        'trx_currency',
        'date_treated',
        'vendor_id',
        'beneficiary_staff_ara_id',
        'beneficiary_details',
        'status',
        'status_changed_at',
        'entered_by_user_id',
        'naira_value',
        'euro_value',
        'us_dollar_value',
        'gbp_value'
    ];

    public function vendor_idRelation()
    {
        return $this->belongsTo(ExternalVendor::class, 'vendor_id');
    }

    public function beneficiary_staff_ara_idRelation()
    {
        return $this->belongsTo(StaffMember::class,  'beneficiary_staff_ara_id', 'staff_ara_id');
    }

    public function entered_by_user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'entered_by_user_id');
    }

}
