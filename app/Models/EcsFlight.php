<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcsFlight extends Model
{
    protected $fillable = [
        'ecs_booking_id',
        'booking_reference',
        'flight',
        'class',
        'flight_date',
        'depart_from',
        'departure_time',
        'arrive_at',
        'client_id',
    ];

    public function ecsBooking()
    {
        return $this->belongsTo(EcsBooking::class);
    }
}
