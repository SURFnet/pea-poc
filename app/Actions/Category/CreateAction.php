<?php

declare(strict_types=1);

namespace App\Actions\Category;

use App\Models\Category;
use App\Models\Institute;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(array $data, Institute $institute): void
    {
        $category = new Category($data);
        $category->institute()->associate($institute);
        $category->save();
    }
}
