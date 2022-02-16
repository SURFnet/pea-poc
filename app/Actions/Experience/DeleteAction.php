<?php

declare(strict_types=1);

namespace App\Actions\Experience;

use App\Models\Experience;
use Spatie\QueueableAction\QueueableAction;

class DeleteAction
{
    use QueueableAction;

    public function execute(Experience $experience): void
    {
        $experience->delete();
    }
}
