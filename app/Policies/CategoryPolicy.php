<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAll(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }

    public function create(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(User $currentUser, Category $category): bool
    {
        return $currentUser->isInformationManager() && $category->institute()->is($currentUser->institute);
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function delete(User $currentUser, Category $category): bool
    {
        return $this->update($currentUser, $category);
    }
}
