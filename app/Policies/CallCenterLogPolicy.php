<?php

namespace App\Policies;

use App\Models\CallCenterLog;
use App\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CallCenterLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any call center logs.
     *
     * @param  \App\Models\Auth\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('');
    }

    /**
     * Determine whether the user can view the call center log.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\CallCenterLog  $callCenterLog
     * @return mixed
     */
    public function view(User $user, CallCenterLog $callCenterLog)
    {
        //
    }

    /**
     * Determine whether the user can create call center logs.
     *
     * @param  \App\Models\Auth\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the call center log.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\CallCenterLog  $callCenterLog
     * @return mixed
     */
    public function update(User $user, CallCenterLog $callCenterLog)
    {
        //
    }

    /**
     * Determine whether the user can delete the call center log.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\CallCenterLog  $callCenterLog
     * @return mixed
     */
    public function delete(User $user, CallCenterLog $callCenterLog)
    {
        //
    }

    /**
     * Determine whether the user can restore the call center log.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\CallCenterLog  $callCenterLog
     * @return mixed
     */
    public function restore(User $user, CallCenterLog $callCenterLog)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the call center log.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\CallCenterLog  $callCenterLog
     * @return mixed
     */
    public function forceDelete(User $user, CallCenterLog $callCenterLog)
    {
        //
    }
}
