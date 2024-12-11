<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class TranslationPolicy extends BasePolicy
{
    public function activateLocale(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function deactivateLocale(User $user): bool
    {
        return $this->activateLocale($user);
    }

    public function updateGroup(User $user): bool
    {
        return $this->viewAny($user);
    }
}
