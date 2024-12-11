<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tag;

use App\Models\Tag;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Tag $tag, array $data): void
    {
        $tag->setTranslations('name', $data['name']);

        if (isset($data['description'])) {
            $tag->setTranslations('description', $data['description']);
        }

        $tag->type = $data['type'];

        $tag->save();
    }
}
