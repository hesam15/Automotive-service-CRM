<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
{
    private $checkCustomerServiceCeter;

    /**
     * Create a new policy instance.
     */
    public function __construct(User $user, Customer $customer)
    {
        $this->checkCustomerServiceCeter = $customer->hasServiceCenter($user->serviceCenter);
    }

    public function index(User $user ,Customer $customer) {
        return $user->can('view_customers') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(User $user, Customer $customer) {
        return $user->can('create_customers') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user, Customer $customer) {
        return $user->can('update_customers') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}