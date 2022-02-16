<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool\Prohibited;

use App\Enums\InstituteTool\Status;
use App\Models\Institute;
use App\Models\Tool;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Tool $tool, Institute $institute, array $data): void
    {
        $instituteTool = $institute->tools()->find($tool)->pivot;

        $instituteTool->status = Status::PROHIBITED;

        $instituteTool->update($data);
    }
}
