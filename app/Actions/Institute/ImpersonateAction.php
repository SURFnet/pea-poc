<?php

declare(strict_types=1);

namespace App\Actions\Institute;

use App\Models\Institute;
use App\Models\User;

class ImpersonateAction
{
    public function execute(User $user, Institute $institute): void
    {
        $user->impersonatedInstitute()->associate($institute);
        $user->save();
    }
}
