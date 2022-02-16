<?php

declare(strict_types=1);

namespace App\Actions\Category;

use App\Models\Category;
use Spatie\QueueableAction\QueueableAction;

class DeleteAction
{
    use QueueableAction;

    public function execute(Category $category): void
    {
        $category->delete();
    }
}
