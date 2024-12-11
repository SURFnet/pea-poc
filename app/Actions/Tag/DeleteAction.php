<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;
use Spatie\QueueableAction\QueueableAction;

class DeleteAction
{
    use QueueableAction;

    public function execute(Tag $tag): void
    {
        $tag->delete();
    }
}
