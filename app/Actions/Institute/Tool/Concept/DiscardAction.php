<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool\Concept;

use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;

class DiscardAction
{
    public function execute(Tool $tool, Institute $institute): void
    {
        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();
        $concept = $instituteTool->concept;

        if (!$concept) {
            return;
        }

        $concept->delete();
    }
}
