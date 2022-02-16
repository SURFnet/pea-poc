<?php

declare(strict_types=1);

namespace App\Actions\Tool;

use App\Models\Tool;
use Carbon\Carbon;
use Spatie\QueueableAction\QueueableAction;

class PublishAction
{
    use QueueableAction;

    public function execute(Tool $tool): void
    {
        $tool->published_at = Carbon::now();

        $tool->save();
    }
}
