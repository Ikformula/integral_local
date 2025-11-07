<?php
namespace App\Models;

use App\Models\Auth\User;
use App\Models\Traits\TracksDataChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcsFlightTransaction extends Model
{
    use TracksDataChanges, SoftDeletes;

    protected $fillable = [
        'ecs_booking_id',
        'client_id',
        'name',
        'ticket_number',
        'booking_reference',
        'trx_id',
        'is_cancelled',
        'cancel_comment',
        'service_fee',
        'pushed_to_reconciliation_at',
        'client_approved_at',
        'approver_client_user_id',
        'client_disputed_at',
        'disputer_client_user_id',
        'dispute_comment',
        'ticket_fare',
        'penalties',
        'category',
        'no_show_fee',
        'excess_baggage_charge',
        'date_change_fee',
        'name_change_fee',
        'reroute_fee',
        'source',
        'for_date',
        'internal_approved_at',
        'internal_approver_id',
        'agent_user_id',
        'rejected_internally_at',
        'rejected_internally_by_user_id',
        'rejection_comment',
        'pushed_to_client_by_user_id',
        'pushed_to_client_at',
    'client_rejected_at',
    'client_rejection_note',
    ];

    protected $dates = [
        'for_date',
        'pushed_to_reconciliation_at',
        'internal_approved_at',
        'rejected_internally_at',
        'pushed_to_client_at',
    ];

    public function getPositionAttribute()
    {
        if($this->client_disputed_at)
            return 'DISPUTE: '.$this->dispute_comment;

        if($this->client_approved_at)
            return 'Client Accepted (ACC)';

        if($this->pushed_to_client_at)
            return 'With Client (CL)';

        return 'Agent (AG)';
    }

    public function ecsBooking()
    {
        return $this->belongsTo(EcsBooking::class);
    }

    public function client()
    {
        return $this->belongsTo(EcsClient::class, 'client_id');
    }

    public function internalApprover()
    {
        return $this->hasOne(User::class, 'internal_approver_id');
    }

    public function rejectedInternallyByUser()
    {
        return $this->hasOne(User::class, 'rejected_internally_by_user_id');
    }

    public function agentUser()
    {
        return $this->hasOne(User::class, 'id', 'agent_user_id');
    }

    public function flights()
    {
        return $this->hasMany(EcsFlight::class, 'ecs_transaction_id');
    }


    public function taxes()
    {
        return $this->hasMany(EcsTransactionTax::class, 'transaction_id');
    }

    public function totalTaxes()
    {
        return $this->taxes()->sum('amount');
    }

    public function totalAmount()
    {
        return $this->totalTaxes() + $this->ticket_fare + $this->service_fee + $this->penalties + $this->no_show_fee + $this->excess_baggage_charge + $this->date_change_fee + $this->name_change_fee + $this->reroute_fee;
    }
}
