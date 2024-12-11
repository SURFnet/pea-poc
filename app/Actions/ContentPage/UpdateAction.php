<?php

declare(strict_types=1);

namespace App\Actions\ContentPage;

use App\Models\ContentPage;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(array $data, ContentPage $contentPage): void
    {
        $contentPage->update($data);
    }
}
