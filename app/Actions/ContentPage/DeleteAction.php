<?php

declare(strict_types=1);

namespace App\Actions\ContentPage;

use App\Models\ContentPage;
use Spatie\QueueableAction\QueueableAction;

class DeleteAction
{
    use QueueableAction;

    public function execute(ContentPage $contentPage): void
    {
        $contentPage->delete();
    }
}
