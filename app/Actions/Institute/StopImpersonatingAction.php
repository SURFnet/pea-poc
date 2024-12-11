<?php

declare(strict_types=1);

namespace App\Actions\Institute;

use App\Models\User;

class StopImpersonatingAction
{
    public function execute(User $user): void
    {
        $user->impersonatedInstitute()->disassociate();
        $user->save();
    }
}
