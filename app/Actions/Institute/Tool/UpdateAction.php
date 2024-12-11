<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool;

use App\Actions\PendingToolEdit\ClearAction;
use App\Actions\ToolLog\CreateAction as CreateToolLogAction;
use App\Enums\Tags\TagTypes;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Tool $tool, Institute $institute, array $data, User $user): void
    {
        (new ClearAction())->execute($tool, $user, $institute);

        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();

        $instituteTool->update($data);

        if (isset($data['alternative_tools_ids'])) {
            $instituteTool->alternativeTools()->sync($data['alternative_tools_ids']);
        }

        if (isset($data['categories'])) {
            $instituteTool->syncTagsWithType(
                Tag::whereIn('id', $data['categories'])->get(),
                TagTypes::CATEGORIES
            );
        }

        (new CreateToolLogAction())->execute($tool, $user, $institute);
    }
}
