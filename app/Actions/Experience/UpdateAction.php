<?php

declare(strict_types=1);

namespace App\Actions\Experience;

use App\Models\Experience;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Experience $experience, array $data): void
    {
        $experience->update($data);
    }
}
