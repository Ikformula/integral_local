<?php


namespace App\Http\Controllers\Traits;


use App\Models\Setting;
use App\Models\StaffTravelBooking;
use Carbon\Carbon;

trait StaffTravelTrait
{
    public function getSTBBalance($staff_ara_id)
    {
        $now = Carbon::now();
        $years_bookings = StaffTravelBooking::where('staff_ara_id', $staff_ara_id)
            ->where('request_year', $now->year)
            ->count();

        $max_bookings_allowed = Setting::where('category', 'staff_travel_portal')
            ->where('key', 'yearly_booking_allowance')
            ->first();

        return $max_bookings_allowed->value - $years_bookings;
    }
}
