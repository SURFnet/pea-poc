<?php

declare(strict_types=1);

namespace App\Actions\CustomTool;

use App\Actions\Tool\CreateAction as CreateToolAction;
use App\Models\Tool;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function __construct(
        private readonly CreateToolAction $createToolAction,
    ) {
    }

    public function execute(array $data, User $user): Tool
    {
        $tool = $this->createToolAction->execute($data, $user);

        $tool->institute()->associate($user->institute->id);

        $tool->save();

        return $tool;
    }
}
