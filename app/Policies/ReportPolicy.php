<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    private $checkCustomerServiceCeter;

    /**
     * Create a new policy instance.
     */
    public function __construct(User $user, Customer $customer)
    {
        $this->checkCustomerServiceCeter = $customer->hasServiceCenter($user->serviceCenter);
    }

    public function index(User $user) {
        return $user->can('view_reports') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(User $user) {
        return $user->can('create_reports') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user) {
        return $user->can('update_reports') && $this->checkCustomerServiceCeter
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
