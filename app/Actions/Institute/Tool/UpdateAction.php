<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool;

use App\Models\Institute;
use App\Models\Tool;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Tool $tool, Institute $institute, array $data): void
    {
        $instituteTool = $institute->tools()->find($tool)->pivot;

        $instituteTool->update($data);

        (new StoreImagesAction())->execute($instituteTool, $data);

        (new SetCategoriesAction())->execute($tool, $institute, $data['categories'] ?? []);
    }
}
