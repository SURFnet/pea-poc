<?php

declare(strict_types=1);

namespace App\Actions\Tool;

use App\Models\Tool;
use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;

class StoreImagesAction
{
    use QueueableAction;

    public function execute(Tool $tool, array $data): void
    {
        foreach (Tool::$images as $imageField) {
            if (!isset($data[$imageField])) {
                continue;
            }

            if ($tool->$imageField) {
                Storage::disk(Tool::$disk)->delete($tool->$imageField);
            }

            $tool->$imageField = basename(
                $data[$imageField]->store(Tool::$disk)
            );
        }

        $tool->save();
    }
}
