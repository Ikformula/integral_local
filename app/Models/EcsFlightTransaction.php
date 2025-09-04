<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcsFlightTransaction extends Model
{
    protected $fillable = [
        'ecs_booking_id',
        'client_id',
        'name',
        'ticket_number',
        'booking_reference',
        'trx_id',
        'is_cancelled',
        'service_fee',
        'client_approved_at',
        'client_approver_id',
        'ticket_fare',
        'penalties',
        'for_date',
    ];

    protected $dates = [
        'for_date',
    ];

    public function ecsBooking()
    {
        return $this->belongsTo(EcsBooking::class);
    }
}
