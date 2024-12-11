<?php

declare(strict_types=1);

namespace App\Actions\Tool\Concept;

use App\Helpers\File;
use App\Models\Tool;
use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;

class StoreImagesAction
{
    use QueueableAction;

    public function execute(Tool $tool, array $data): void
    {
        $concept = $tool->concept;

        foreach (Tool::$images as $imageField) {
            if (!isset($data[$imageField])) {
                continue;
            }

            if ($concept->$imageField && $concept->$imageField !== $tool->$imageField) {
                Storage::disk(Tool::$disk)->delete($concept->$imageField);
            }

            $concept->$imageField = File::store($data[$imageField], Tool::$disk);
        }

        $concept->save();
    }
}
