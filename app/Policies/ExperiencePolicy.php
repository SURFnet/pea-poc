<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Experience;
use App\Models\Tool;
use App\Models\User;

class ExperiencePolicy
{
    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function create(User $currentUser, Tool $tool): bool
    {
        return $currentUser->isTeacher();
    }

    public function seeUser(User $currentUser, Experience $experience): bool
    {
        if ($currentUser->is($experience->user)) {
            return true;
        }

        return $currentUser->institute->is($experience->user->institute);
    }

    public function update(User $currentUser, Experience $experience): bool
    {
        return $experience->user()->is($currentUser);
    }

    public function delete(User $currentUser, Experience $experience): bool
    {
        return $this->update($currentUser, $experience);
    }
}
