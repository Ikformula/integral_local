<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceNowTicketType extends Model
{
    public function escalateTimeInSeconds()
    {
        $conv = [
            'minute' => '60',
            'hour' => '3600',
            'day' => '86400',
            'week' => '604800',
            'month' => '18144000',
            'not applicable' => '1',
        ];

        return $this->escalates_in_time_amount * $conv[$this->escalates_in_time_units];
    }
}
