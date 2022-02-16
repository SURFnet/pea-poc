<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool\Prohibited;

use App\Models\Institute;
use App\Models\Tool;
use Spatie\QueueableAction\QueueableAction;

class AddAction
{
    use QueueableAction;

    public function execute(Tool $tool, Institute $institute, array $data): void
    {
        $tool->institutes()->attach($institute);

        (new UpdateAction())->execute($tool, $institute, $data);
    }
}
