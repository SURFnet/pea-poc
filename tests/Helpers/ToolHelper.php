<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;

class ToolHelper
{
    public static function create(
        ?Institute $institute = null,
        bool $published = true,
        array $attributes = [],
        array $attributesInstitute = [],
        array $tags = [],
    ): Tool {
        $tool = Tool::factory()->published($published)->create($attributes);

        if ($tags) {
            $tool->attachTags($tags);
        }

        if (!$institute) {
            return $tool;
        }

        $institute->tools()->attach($tool, $attributesInstitute);

        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->first();
        $instituteTool->update($attributesInstitute);

        return $tool;
    }
}
