<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Auth\User;

class OutgoingMessageRecipient extends Model
{
    use SoftDeletes;

    protected $dates = [
        'email_sent_at'
    ];

    public function message()
    {
        return $this->belongsTo(OutgoingMessage::class, 'message_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function canSendToEmail()
    {
        return 1;
    }

    public function canSendToWhatsapp()
    {
        $user = $this->user;
        if(!is_null($user) && isset($user->phone_number) && !is_null($user->otp_verified_at)){
            return 1;
        }
        return 0;
    }
}
