<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool\Concept;

use App\Models\ConceptInstituteTool;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;

class SafelyDiscardAction
{
    public function execute(Tool $tool, Institute $institute): bool
    {
        $concept = InstituteTool::forTool($tool)->forInstitute($institute)->first()?->concept;
        if ($concept === null) {
            return false;
        }

        if ($this->conceptWasChangedAfterCreating($concept)) {
            return false;
        }

        (new DiscardAction())->execute($tool, $institute);

        return true;
    }

    private function conceptWasChangedAfterCreating(ConceptInstituteTool $concept): bool
    {
        return $concept->updated_at->isAfter($concept->created_at);
    }
}
