<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, User $targetUser) {
        return $user->hasRole('adminstrator') && $user->serviceCenter == $targetUser->serviceCenter || !$user->hasRole('adminstrator') && $user->id == $targetUser->id
            ? Response::allow()
            : Response::deny();
    }
}
