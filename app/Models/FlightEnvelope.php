<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightEnvelope extends Model
{
    protected $fillable = [
      'flight_envelope_number',
      'aircraft_id',
      'tech_log_number',
      'plan_number',
    ];

    public function cell_values()
    {
        return $this->hasMany(FeCellInput::class, 'flight_envelope_id');
    }

    public function aircraft()
    {
        return $this->hasOne(Aircraft::class, 'id', 'aircraft_id');
    }

    public function first_officer()
    {
        $officer = null;
        $counter = 1;
        while(is_null($officer) && $counter <= 4){
            $officer = getFeCellValue($this->cell_values, 'flight_deck_company_id_no_' . $counter);
            $counter++;
        }

        if($officer){
            $officer_staff_info = StaffMember::where('staff_ara_id', $officer);
            if($officer_staff_info)
                return $officer_staff_info;
        }

        return null;
    }

}
