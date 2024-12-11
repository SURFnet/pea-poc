<?php

declare(strict_types=1);

namespace App\Actions\ContentPage;

use App\Models\ContentPage;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(array $data): void
    {
        $contentPage = new ContentPage($data);
        $contentPage->save();
    }
}
