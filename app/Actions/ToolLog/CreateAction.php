<?php

declare(strict_types=1);

namespace App\Actions\ToolLog;

use App\Models\Institute;
use App\Models\Tool;
use App\Models\ToolLog;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(Tool $tool, User $user, ?Institute $institute = null): void
    {
        $edit = new ToolLog();

        $edit->tool()->associate($tool);
        $edit->user()->associate($user);
        $edit->institute()->associate($institute);

        $edit->save();
    }
}
