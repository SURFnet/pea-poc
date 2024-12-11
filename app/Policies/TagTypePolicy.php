<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Auth\Role;
use App\Enums\Tags\TagTypes;
use App\Models\User;

class TagTypePolicy
{
    public function filterBy(User $currentUser, string $tagType): bool
    {
        if ($currentUser->isOnlyRole(Role::TEACHER)) {
            return in_array($tagType, TagTypes::forTeacher());
        }

        return true;
    }
}
