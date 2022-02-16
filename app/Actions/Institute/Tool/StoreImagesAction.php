<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool;

use App\Models\InstituteTool;
use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;

class StoreImagesAction
{
    use QueueableAction;

    public function execute(InstituteTool $instituteTool, array $data): void
    {
        foreach (InstituteTool::$images as $imageField) {
            if (!isset($data[$imageField])) {
                continue;
            }

            if ($instituteTool->$imageField) {
                Storage::disk(InstituteTool::$disk)->delete($instituteTool->$imageField);
            }

            $instituteTool->$imageField = basename(
                $data[$imageField]->store(InstituteTool::$disk)
            );
        }

        $instituteTool->save();
    }
}
