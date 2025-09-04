<?php
namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class EcsBooking extends Model
{
    protected $fillable = [
        'booking_reference',
        'penalties',
        'ticket_fare',
        'remarks',
        'for_date',
        'agent_user_id',
        'client_id'
    ];

    protected $dates = [
        'for_date',
    ];

    public function agent_user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'agent_user_id');
    }

    public function client_idRelation()
    {
        return $this->belongsTo(EcsClient::class, 'client_id');
    }

    public function flights()
    {
        return $this->hasMany(EcsFlight::class, 'ecs_booking_id');
    }

    public function flight_transactions()
    {
        return $this->hasMany(EcsFlightTransaction::class, 'ecs_booking_id');
    }

    public function taxes()
    {
        return $this->hasMany(EcsBookingTax::class, 'booking_id');
    }

    public function taxField($tax_name)
    {
        return $this->taxes()->where('tax_name', $tax_name)->first();
    }

    public function totalFare()
    {
        return $this->flight_transactions()->count() * $this->ticket_fare;
    }

    public function totalServiceCharge()
    {
        return $this->flight_transactions()->sum('service_fee');
    }

    public function totalTaxes()
    {
        return $this->taxes()->sum('amount');
    }
}
