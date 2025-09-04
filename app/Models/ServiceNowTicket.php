<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceNowTicket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type_id',
        'concerned_staff_ara_id',
        'origin_type',
        'assigned_to_agent_user_id',
        'escalate_to_user_id',
        'notify_agent',
        'notify_escalation_user',
        'status',
        'group_id',
        'priority',
        'rating',
        'created_by_user_id',
    ];

    public function concernedStaff(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'concerned_staff_ara_id', 'staff_ara_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_agent_user_id', 'id');
    }

    public function escalateToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalate_to_user_id', 'id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ServiceNowGroup::class, 'group_id', 'id');
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(ServiceNowTicketType::class, 'type_id', 'id');
    }

    public function getPercentAged(){
        if(!$this->ticketType){
            return [
                'type_escalates_seconds' => 0,
                'percent_aged' => 0
            ];
        }
        $type_escalates_seconds = $this->ticketType->escalateTimeInSeconds();
        $ticket_age = $this->created_at->diffInSeconds(now());

        if($type_escalates_seconds){
        $percent_aged = ( $ticket_age / $type_escalates_seconds ) * 100;
        return [
            'type_escalates_seconds' => $type_escalates_seconds,
            'percent_aged' => $percent_aged
            ];
        }else{
            return [
                'type_escalates_seconds' => $type_escalates_seconds,
                'percent_aged' => 0
                ];
        }
    }

    public function agingColour()
    {
        $agingData = $this->getPercentAged();
        extract($agingData);

        if($type_escalates_seconds == 0 || $this->status == 'closed' || $this->status == 'completed'){
            return 'secondary';
        }
        if($percent_aged <= 35){
            return 'success';
        }else if($percent_aged > 35 && $percent_aged <= 65){
            return 'warning';
        }else{
            return 'danger';
        }
    }

    public function priorityUI()
    {
//        <i class="fal fa-battery-quarter" style="color: #2b5618;"></i>
        $arr = [
          'low' => 'battery-full text-info',
          'medium' => 'battery-half text-warning',
          'high' => 'battery-quarter text-danger',
        ];

        return $arr[$this->priority];
    }

    public function agingData()
    {
        $ticket_type = $this->ticketType;
        if($ticket_type->escalates_in_time_units == 'not applicable'){
            return '';
        }

        return ' / '.$ticket_type->escalates_in_time_amount.' '.\Illuminate\Support\Str::plural($ticket_type->escalates_in_time_units, $ticket_type->escalates_in_time_amount);
    }

    public function logs(){
        return $this->hasMany(ServiceNowTicketLog::class, 'service_now_ticket_id', 'id');
    }
}
