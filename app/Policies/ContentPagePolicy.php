<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class ContentPagePolicy extends BasePolicy
{
    public function canManage(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
