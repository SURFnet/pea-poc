<?php

declare(strict_types=1);

namespace App\Actions\Tool;

use App\Models\Tool;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(array $data): void
    {
        $tool = Tool::create($data);

        $tool->features()->sync($data['features'] ?? []);

        (new StoreImagesAction())->execute($tool, $data);
    }
}
