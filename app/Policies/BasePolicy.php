<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $currentUser): bool
    {
        return $currentUser->isSuperAdmin();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function view(User $currentUser, Model $target): bool
    {
        return $currentUser->isSuperAdmin();
    }

    public function create(User $currentUser): bool
    {
        return $currentUser->isSuperAdmin();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(User $currentUser, Model $target): bool
    {
        return $currentUser->isSuperAdmin();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function delete(User $currentUser, Model $target): bool
    {
        return $currentUser->isSuperAdmin();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function restore(User $currentUser, Model $target): bool
    {
        return $currentUser->isSuperAdmin();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function forceDelete(User $currentUser, Model $target): bool
    {
        return $currentUser->isSuperAdmin();
    }
}
