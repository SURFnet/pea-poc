<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Institute;
use App\Models\User;

class InstitutePolicy extends BasePolicy
{
    public function editHomepage(User $user): bool
    {
        return $user->isInformationManager();
    }

    public function impersonate(User $user, Institute $institute = null): bool
    {
        if ($institute !== null && $institute->is($user->institute)) {
            return false;
        }

        return $this->viewAny($user);
    }

    public function sendNotifications(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }
}
