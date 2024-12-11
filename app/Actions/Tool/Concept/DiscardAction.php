<?php

declare(strict_types=1);

namespace App\Actions\Tool\Concept;

use App\Models\ConceptTool;
use App\Models\Tool;
use Illuminate\Support\Facades\Storage;

class DiscardAction
{
    public function execute(Tool $tool): void
    {
        $concept = $tool->concept;
        if (!$concept) {
            return;
        }

        foreach (Tool::$images as $imageField) {
            if ($concept->$imageField && $concept->$imageField !== $tool->$imageField) {
                Storage::disk(ConceptTool::$disk)->delete($concept->$imageField);
            }
        }

        $concept->delete();
    }
}
