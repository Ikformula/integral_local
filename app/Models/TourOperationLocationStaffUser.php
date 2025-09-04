<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class TourOperationLocationStaffUser extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff_member()
    {
        return $this->user->staff_member;
//        if($user) {
//            return StaffMember::where('email', $user->email)->first();
//        }
//
//        return null;
    }
}
