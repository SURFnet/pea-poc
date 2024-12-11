<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tag;

use App\Models\Institute;
use App\Models\Tag;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(array $data, Institute $institute): void
    {
        $tag = Tag::create(['name' => $data['name'], 'type' => $data['type']]);
        $tag->institute()->associate($institute);

        if (isset($data['description']['en']) || isset($data['description']['nl'])) {
            $tag->setTranslations('description', $data['description']);
        }

        $tag->save();
    }
}
