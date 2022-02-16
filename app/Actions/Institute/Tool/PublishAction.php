<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool;

use App\Models\Institute;
use App\Models\Tool;
use Carbon\Carbon;
use Spatie\QueueableAction\QueueableAction;

class PublishAction
{
    use QueueableAction;

    public function execute(Tool $tool, Institute $institute): void
    {
        $instituteTool = $institute->tools()->find($tool)->pivot;
        $instituteTool->published_at = Carbon::now();
        $instituteTool->save();
    }
}
