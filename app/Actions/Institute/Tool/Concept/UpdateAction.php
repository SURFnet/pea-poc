<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool\Concept;

use App\Actions\PendingToolEdit\ClearAction;
use App\Actions\ToolLog\CreateAction as CreateToolLogAction;
use App\Enums\Tags\TagTypes;
use App\Helpers\WYSIWYG;
use App\Models\ConceptInstituteTool;
use App\Models\InstituteTool;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Support\Arr;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Tool $tool, User $user, array $data, string $newStatus = null): void
    {
        $institute = $user->institute;

        (new ClearAction())->execute($tool, $user, $institute);

        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();

        $concept = $instituteTool->getOrCreateConceptVersion();

        if ($newStatus !== null) {
            $concept->status = $newStatus;
        }

        $concept->update(Arr::except($data, 'custom_fields'));

        if (isset($data['alternative_tools_ids'])) {
            $concept->alternativeTools()->sync($data['alternative_tools_ids']);
        }

        $this->setCustomFields($concept, $data);
        $this->syncTags($concept, $data);

        (new CreateToolLogAction())->execute($tool, $user, $institute);
    }

    private function setCustomFields(ConceptInstituteTool $concept, array $data): void
    {
        if (!isset($data['custom_fields'])) {
            return;
        }

        $concept->customFields()->sync([]);
        foreach ($data['custom_fields'] as $customField) {
            if (WYSIWYG::isEmpty($customField['value_en'])) {
                continue;
            }

            $concept->customFields()->attach($customField['id'], [
                'value_en' => $customField['value_en'],
                'value_nl' => WYSIWYG::isEmpty($customField['value_nl']) ? null : $customField['value_nl'],
            ]);
        }
    }

    private function syncTags(ConceptInstituteTool $concept, array $data): void
    {
        if (isset($data['categories'])) {
            $concept->syncTagsWithType(
                Tag::whereIn('id', $data['categories'])->get(),
                TagTypes::CATEGORIES
            );
        }
    }
}
