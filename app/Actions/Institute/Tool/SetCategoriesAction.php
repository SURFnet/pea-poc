<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool;

use App\Models\Institute;
use App\Models\Tool;
use Spatie\QueueableAction\QueueableAction;

class SetCategoriesAction
{
    use QueueableAction;

    public function execute(Tool $tool, Institute $institute, array $categories): void
    {
        // TODO Refactor categories to be related to InstituteTool instead
        $currentCategories = $institute->categories()->forTool($tool)->pluck('id');
        $tool->categories()->detach($currentCategories);

        if (!empty($categories)) {
            $tool->categories()->attach($categories);
        }
    }
}
