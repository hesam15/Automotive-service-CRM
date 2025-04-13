<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use CodeDredd\Soap\Client\Response;

class BookingPolicy
{
    private $checkCustomerServiceCeter;

    /**
     * Create a new policy instance.
     */
    public function __construct(User $user ,Customer $customer)
    {
        $this->checkCustomerServiceCeter = $customer->hasServiceCenter($user->serviceCenter);
    }

    public function create(User $user, Customer $customer) {
        return $user->can('create_bookings') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user, Customer $customer) {
        return $user->can('edit_bookings') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
