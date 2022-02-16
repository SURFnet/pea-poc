<?php

declare(strict_types=1);

namespace App\Actions\Experience;

use App\Models\Experience;
use App\Models\Tool;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(Tool $tool, User $user, array $data): void
    {
        $experience = new Experience($data);

        $experience->user()->associate($user);
        $experience->tool()->associate($tool);

        $experience->save();
    }
}
