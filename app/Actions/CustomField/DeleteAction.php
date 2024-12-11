<?php

declare(strict_types=1);

namespace App\Actions\CustomField;

use App\Models\CustomField;
use Spatie\QueueableAction\QueueableAction;

class DeleteAction
{
    use QueueableAction;

    public function execute(CustomField $customField): void
    {
        $customField->delete();
    }
}
