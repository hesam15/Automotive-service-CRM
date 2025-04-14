<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\Response;

class CarPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, Customer $customer) {
        return $user->can('create_cars') && $customer->hasServiceCenter($user->serviceCenter)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user, Car $car) {
        return $user->can('create_cars') && $car->customer->hasServiceCenter($user->serviceCenter)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
