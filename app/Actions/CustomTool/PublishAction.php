<?php

declare(strict_types=1);

namespace App\Actions\CustomTool;

use App\Actions\Institute\Tool\AddAction as AddInstituteToolAction;
use App\Models\Tool;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class PublishAction
{
    use QueueableAction;

    public function __construct(
        private readonly AddInstituteToolAction $addInstituteToolAction,
    ) {
    }

    public function execute(Tool $tool, User $user, array $data): void
    {
        $this->addInstituteToolAction->execute($tool, $user->institute, $data, $user);
    }
}
