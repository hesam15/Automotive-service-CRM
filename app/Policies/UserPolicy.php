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

    public function update(User $user, User $targetUser) {
        return $user->can('update_users') && $user->serviceCenter == $targetUser->serviceCenter
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, User $targetUser) {
        return $user->hasRole('adminstrator') && $user->serviceCenter == $targetUser->serviceCenter || !$user->hasRole('adminstrator') && $user->can('delete_users') && $user->id == $targetUser->id
            ? Response::allow()
            : Response::deny();
    }
}
