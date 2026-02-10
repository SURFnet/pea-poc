<?php

declare(strict_types=1);

namespace App\Actions\CustomTool;

use App\Models\Tool;

class ConvertToGenericToolAction
{
    public function execute(Tool $tool): Tool
    {
        $tool->institute()->dissociate();

        $tool->published_at = now();

        $tool->save();

        return $tool;
    }
}
