<?php

declare(strict_types=1);

namespace App\Actions\Tool;

use App\Models\Tool;
use App\Models\User;

class ChangeFollowingStatusAction
{
    public function execute(Tool $tool, User $user): void
    {
        if ($user->isFollowingTool($tool)) {
            $tool->followers()->detach($user);

            return;
        }

        $tool->followers()->attach($user);
    }
}
