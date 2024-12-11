<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Tag $tag, array $data): void
    {
        $tag->update([
            'name' => $data['name'],
            'type' => $data['type'],
        ]);
    }
}
