<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class FuelRecords extends Model
{
    public function aircraft()
    {
        return $this->belongsTo(Aircraft::class, 'aircraft_id');
    }

    public function pilot()
    {
        return $this->belongsTo(Pilot::class, 'pilot_id');
    }

    public function pilot_user()
    {
        return $this->belongsTo(User::class, 'pilot_user_id', 'id');
    }

    public function getAircraftRegNumberAttribute()
    {
        return $this->aircraft()->registration_number;
    }

    public function getPilotNameAttribute()
    {
        return $this->pilot_user()->full_name;
    }

    public function departure_location()
    {
        return DepartureArrivalLocation::find($this->departure_location_id)->name;
    }

    public function arrival_location()
    {
        return DepartureArrivalLocation::find($this->arrival_location_id)->name;
    }

}
