<?php

declare(strict_types=1);

namespace App\Actions\Tool\Concept;

use App\Enums\Tags\TagTypes;
use App\Models\ConceptTool;
use App\Models\Tool;
use Illuminate\Support\Arr;

class CreateAction
{
    public function execute(Tool $tool): void
    {
        $concept = new ConceptTool();

        $concept->fill(Arr::only($tool->toArray(), $concept->getFillable()));

        foreach (ConceptTool::$images as $imageField) {
            $concept->$imageField = $tool->$imageField;
        }

        $concept->originalVersion()->associate($tool);

        $concept->save();

        $concept->syncTagsWithType($tool->features(), TagTypes::FEATURES);
        $concept->syncTagsWithType($tool->softwareType(), TagTypes::SOFTWARE_TYPES);
        $concept->syncTagsWithType($tool->devices(), TagTypes::DEVICES);
        $concept->syncTagsWithType($tool->standards(), TagTypes::STANDARDS);
        $concept->syncTagsWithType($tool->operatingSystem(), TagTypes::OPERATING_SYSTEMS);
        $concept->syncTagsWithType($tool->dataProcessingLocation(), TagTypes::DATA_PROCESSING_LOCATIONS);
        $concept->syncTagsWithType($tool->certification(), TagTypes::CERTIFICATIONS);
        $concept->syncTagsWithType($tool->workingMethods(), TagTypes::WORKING_METHODS);
        $concept->syncTagsWithType($tool->targetGroup(), TagTypes::TARGET_GROUPS);
        $concept->syncTagsWithType($tool->complexity(), TagTypes::COMPLEXITY);
    }
}
