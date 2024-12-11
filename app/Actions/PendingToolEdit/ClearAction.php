<?php

declare(strict_types=1);

namespace App\Actions\PendingToolEdit;

use App\Models\Institute;
use App\Models\PendingToolEdit;
use App\Models\Tool;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class ClearAction
{
    use QueueableAction;

    public function execute(Tool $tool, User $user, ?Institute $institute = null): void
    {
        $query = PendingToolEdit::forTool($tool)->forUser($user);
        $query = $institute ? $query->forInstitute($institute) : $query->missingInstitute();

        $query->delete();
    }
}
