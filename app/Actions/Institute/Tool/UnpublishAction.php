<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool;

use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;
use Spatie\QueueableAction\QueueableAction;

class UnpublishAction
{
    use QueueableAction;

    public function execute(Tool $tool, Institute $institute): void
    {
        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();
        $instituteTool->published_at = null;
        $instituteTool->save();
    }
}
