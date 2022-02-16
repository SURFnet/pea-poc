<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool;

use App\Models\Institute;
use App\Models\Tool;
use Spatie\QueueableAction\QueueableAction;

class UnpublishAction
{
    use QueueableAction;

    public function execute(Tool $tool, Institute $institute): void
    {
        $instituteTool = $institute->tools()->find($tool)->pivot;
        $instituteTool->published_at = null;
        $instituteTool->save();
    }
}
