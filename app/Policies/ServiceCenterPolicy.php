<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ServiceCenter;
use Illuminate\Auth\Access\Response;

class ServiceCenterPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, ServiceCenter $serviceCenter) {
        return $user->can('edit_serviceCenters') && $user->serviceCeter == $serviceCenter
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
