<?php

declare(strict_types=1);

namespace App\Actions\Tool;

use App\Helpers\File;
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

            $tool->$imageField = File::store($data[$imageField], Tool::$disk);
        }

        $tool->save();
    }
}
