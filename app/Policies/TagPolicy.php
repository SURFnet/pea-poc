<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

class TagPolicy
{
    public function viewAll(User $currentUser): bool
    {
        return $currentUser->isContentManager();
    }

    public function createForInstitute(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }

    public function updateForInstitute(User $currentUser, Tag $tag): bool
    {
        return $tag->institute->id === $currentUser->institute->id && $currentUser->isInformationManager();
    }

    public function deleteForInstitute(User $currentUser, Tag $tag): bool
    {
        return $tag->institute->id === $currentUser->institute->id && $currentUser->isInformationManager();
    }

    public function manageOur(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }

    public function create(User $currentUser): bool
    {
        return $currentUser->isContentManager();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(User $currentUser, Tag $tag): bool
    {
        return $currentUser->isContentManager();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function delete(User $currentUser, Tag $tag): bool
    {
        return $currentUser->isContentManager();
    }
}
