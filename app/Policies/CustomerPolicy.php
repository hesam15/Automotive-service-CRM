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
    public function __construct(Customer $customer)
    {
        $user = auth()->user();
        $this->checkCustomerServiceCeter = $customer->hasServiceCenter($user->serviceCenter);
    }

    public function show(User $user) {
        return $user->can('view_customers') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user) {
        return $user->can('update_customers') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}