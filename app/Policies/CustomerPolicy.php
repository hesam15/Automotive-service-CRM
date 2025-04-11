<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use App\Models\ServiceCenter;
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

    public function index(User $user ,ServiceCenter $serviceCenter) {
        return $user->can('view_customers') && $user->serviceCenter == $serviceCenter
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(User $user, ServiceCenter $serviceCenter) {
        return $user->can('create_customers') && $user->serviceCenter == $serviceCenter
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user, ServiceCenter $serviceCenter, Customer $customer) {
        return $user->can('update_customers') && $user->serviceCenter == $serviceCenter && $customer->hasServiceCenter($serviceCenter)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}