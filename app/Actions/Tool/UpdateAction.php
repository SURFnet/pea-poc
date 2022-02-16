<?php

declare(strict_types=1);

namespace App\Actions\Tool;

use App\Models\Tool;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Tool $tool, array $data): void
    {
        $tool->update($data);

        $tool->features()->sync($data['features'] ?? []);

        (new StoreImagesAction())->execute($tool, $data);
    }
}
