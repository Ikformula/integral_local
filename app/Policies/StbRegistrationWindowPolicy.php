<?php

namespace App\Policies;

use App\Models\StbRegistrationWindow;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StbRegistrationWindowPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        //
    }

    public function view(User $user, StbRegistrationWindow $stbRegistrationWindow): bool
    {
        //
    }

    public function create(User $user): bool
    {
        //
    }

    public function update(User $user, StbRegistrationWindow $stbRegistrationWindow): bool
    {
        //
    }

    public function delete(User $user, StbRegistrationWindow $stbRegistrationWindow): bool
    {
        //
    }

    public function restore(User $user, StbRegistrationWindow $stbRegistrationWindow): bool
    {
        //
    }

    public function forceDelete(User $user, StbRegistrationWindow $stbRegistrationWindow): bool
    {
        //
    }
}
