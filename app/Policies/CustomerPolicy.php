<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Customer $customer) {
        return $user->can('update_customers') && $customer->hasServiceCenter($user->serviceCenter->name);
    }
}
