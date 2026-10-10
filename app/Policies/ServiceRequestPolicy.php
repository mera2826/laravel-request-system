<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->isAdministrator()
            || (int) $serviceRequest->user_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function updateStatus(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->isAdministrator();
    }
}