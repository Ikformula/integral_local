<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable = [
        'surname',
        'firstname',
        'other_name',
        'gender',
        'nationality',
        'date_of_birth',
        'passport_number',
        'place_of_issue',
        'date_of_issue',
        'expiry_date',
        'destination',
        'class',
        'ssr_group',
        'visa_date_of_issuance',
        'visa_date_of_expiry',
        'proposed_flight_date',
        'ticket_id',
        'pnr_number',
    ];

    protected $appends = [
        'phone_number',
        'email'
    ];

    public function viewingLogs()
    {
        return $this->hasMany(PassengerViewingLog::class, 'passenger_id', 'id');
    }

    public function canBeBooked()
    {
        $last_view = $this->viewingLogs()->latest()->first();
        if(!$last_view){
            return 1;
        }

        if(!is_null($last_view->opened_by_user_at) && !is_null($last_view->closed_by_user_at)){
            return 1;
        }

        return 0;
    }

    public function currentlyOpenedBy()
    {
        return $this->belongsTo(User::class, 'currently_opened_by_user_id');
    }

    public function infant()
    {
        return Passenger::where('attached_to_passport_number', $this->passport_number)->first();
    }

    public function adult()
    {
        return Passenger::where('passport_number', $this->attached_to_passport_number)->first();
    }

    public function tourOperatorUser()
    {
        return $this->belongsTo(TourOperatorZone::class, 'tour_operator_zone_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPhoneNumberAttribute()
    {
        return $this->tourOperatorUser->phone_number;
    }

    public function getEmailAttribute()
    {
        return $this->user->email;
    }
}
