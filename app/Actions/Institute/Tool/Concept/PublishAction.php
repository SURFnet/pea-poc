<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool\Concept;

use App\Enums\Tags\TagTypes;
use App\Models\ConceptInstituteTool;
use App\Models\CustomField;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Spatie\QueueableAction\QueueableAction;

class PublishAction
{
    use QueueableAction;

    public function __construct(
        private readonly DiscardAction $discardAction,
    ) {
    }

    public function execute(Tool $tool, Institute $institute): void
    {
        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();
        $concept = $instituteTool->concept;

        $instituteTool->fill(Arr::only($concept->toArray(), $instituteTool->getFillable()));
        $instituteTool->updated_at = Carbon::now();

        $instituteTool->save();

        $instituteTool->alternativeTools()->sync($concept->alternativeTools()->pluck('id'));

        $this->syncTags($concept, $instituteTool);

        $this->copyCustomFields($concept, $instituteTool);

        $this->discardAction->execute($tool, $institute);
    }

    private function syncTags(ConceptInstituteTool $concept, InstituteTool $instituteTool): void
    {
        $instituteTool->syncTagsWithType(
            $concept->categories(),
            TagTypes::CATEGORIES
        );
    }

    private function copyCustomFields(ConceptInstituteTool $concept, InstituteTool $instituteTool): void
    {
        $instituteTool->customFields()->sync([]);

        $concept->customFields()->each(function (CustomField $customField) use ($instituteTool): void {
            $instituteTool->customFields()->attach($customField, [
                'value_en' => $customField->pivot->value_en,
                'value_nl' => $customField->pivot->value_nl,
            ]);
        });
    }
}
