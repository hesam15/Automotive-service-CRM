<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Option;
use Illuminate\Auth\Access\Response;

class OptionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Option $option) {
        return $user->can('update_options') && $user->serviceCenter == $option->serviceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
