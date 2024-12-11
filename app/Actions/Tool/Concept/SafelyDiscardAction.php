<?php

declare(strict_types=1);

namespace App\Actions\Tool\Concept;

use App\Models\ConceptTool;
use App\Models\Tool;

class SafelyDiscardAction
{
    public function execute(Tool $tool): bool
    {
        $concept = $tool->concept;
        if ($concept === null) {
            return false;
        }

        if ($this->conceptWasChangedAfterCreating($concept)) {
            return false;
        }

        (new DiscardAction())->execute($tool);

        return true;
    }

    private function conceptWasChangedAfterCreating(ConceptTool $concept): bool
    {
        return $concept->updated_at->isAfter($concept->created_at);
    }
}
