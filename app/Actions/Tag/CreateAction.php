<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(array $data): void
    {
        Tag::create(['name' => $data['name'], 'type' => $data['type']]);
    }
}
