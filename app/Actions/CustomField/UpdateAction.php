<?php

declare(strict_types=1);

namespace App\Actions\CustomField;

use App\Models\CustomField;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(CustomField $customField, array $data): void
    {
        $customField->update($data);
    }
}
