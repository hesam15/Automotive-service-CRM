<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user ,Customer $customer) {
        return $user->can('view_customers') && $customer->hasServiceCenter($user->serviceCenter)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(User $user, Customer $customer) {
        return $user->can('create_customers') && $customer->hasServiceCenter($user->serviceCenter)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user, Customer $customer) {
        return $user->can('update_customers') && $customer->hasServiceCenter($user->serviceCenter)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}