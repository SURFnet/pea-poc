<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool\Concept;

use App\Enums\Tags\TagTypes;
use App\Models\ConceptInstituteTool;
use App\Models\CustomField;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;
use Illuminate\Support\Arr;

class CreateAction
{
    public function execute(Tool $tool, Institute $institute): void
    {
        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();

        $concept = new ConceptInstituteTool();

        $concept->fill(Arr::only($instituteTool->toArray(), $concept->getFillable()));

        $concept->originalVersion()->associate($instituteTool);

        $concept->save();

        $concept->alternativeTools()->sync($instituteTool->alternativeTools()->pluck('id'));

        $this->syncTags($instituteTool, $concept);

        $this->copyCustomFields($instituteTool, $concept);
    }

    private function syncTags(InstituteTool $instituteTool, ConceptInstituteTool $concept): void
    {
        $concept->syncTagsWithType(
            $instituteTool->categories(),
            TagTypes::CATEGORIES
        );
    }

    private function copyCustomFields(InstituteTool $instituteTool, ConceptInstituteTool $concept): void
    {
        $instituteTool->customFields()->each(function (CustomField $customField) use ($concept): void {
            $concept->customFields()->attach($customField, [
                'value_en' => $customField->pivot->value_en,
                'value_nl' => $customField->pivot->value_nl,
            ]);
        });
    }
}
