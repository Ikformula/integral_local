<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlightDisruptionLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'action_date',
        'time_sent_by_crc',
        'log_number',
        'requested_by',
        'flight_date_start',
        'flight_date_end',
        'days_of_week',
        'flight_number',
        'old_flight_time',
        'old_flight_route',
        'new_flight_number',
        'new_flight_time',
        'disruption_status',
        'reason_for_disruption',
        'actions_taken',
        'actioned_by',
        'callcentre_comments',
        'pax_figure_for_disrupted_flt',
        'pax_figure_for_connecting_pax',
    ];
}
