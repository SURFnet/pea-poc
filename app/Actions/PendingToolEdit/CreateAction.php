<?php

declare(strict_types=1);

namespace App\Actions\PendingToolEdit;

use App\Models\Institute;
use App\Models\PendingToolEdit;
use App\Models\Tool;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(Tool $tool, User $user, ?Institute $institute = null): void
    {
        $edit = new PendingToolEdit();

        $edit->tool()->associate($tool);
        $edit->user()->associate($user);
        $edit->institute()->associate($institute);

        $edit->save();
    }
}
