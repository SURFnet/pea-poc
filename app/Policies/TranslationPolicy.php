<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\App;

class TranslationPolicy extends BasePolicy
{
    public function before(): ?bool
    {
        if (App::environment(config('constants.environment.production'))) {
            return false;
        }

        return null;
    }

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
