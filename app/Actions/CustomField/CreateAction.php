<?php

declare(strict_types=1);

namespace App\Actions\CustomField;

use App\Models\CustomField;
use App\Models\Institute;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(array $data, Institute $institute): void
    {
        if (!isset($data['sortkey'])) {
            $data['sortkey'] = $this->getHighestSortkey($institute) + 1;
        }

        $customField = new CustomField($data);

        $customField->institute()->associate($institute);
        $customField->save();
    }

    private function getHighestSortkey(Institute $institute): int
    {
        return $institute->customFields()->max('sortkey') ?? 0;
    }
}
