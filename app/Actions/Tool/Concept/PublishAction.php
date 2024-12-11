<?php

declare(strict_types=1);

namespace App\Actions\Tool\Concept;

use App\Actions\Tool\NotifyStakeholdersAction;
use App\Enums\Tags\TagTypes;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Spatie\QueueableAction\QueueableAction;

class PublishAction
{
    use QueueableAction;

    public function __construct(
        private readonly NotifyStakeholdersAction $emailStakeholdersAction,
        private readonly DiscardAction $discardAction,
    ) {
    }

    public function execute(Tool $tool): void
    {
        $dataBeforePublish = $tool->replicate();

        $tool->fill(Arr::only($tool->concept->toArray(), $tool->getFillable()));

        foreach (Tool::$images as $imageField) {
            $tool->$imageField = $tool->concept->$imageField;
        }

        $tool->updated_at = Carbon::now();

        $tool->save();

        $tool->syncTagsWithType($tool->concept->features(), TagTypes::FEATURES);
        $tool->syncTagsWithType($tool->concept->softwareType(), TagTypes::SOFTWARE_TYPES);
        $tool->syncTagsWithType($tool->concept->devices(), TagTypes::DEVICES);
        $tool->syncTagsWithType($tool->concept->standards(), TagTypes::STANDARDS);
        $tool->syncTagsWithType($tool->concept->operatingSystem(), TagTypes::OPERATING_SYSTEMS);
        $tool->syncTagsWithType($tool->concept->dataProcessingLocation(), TagTypes::DATA_PROCESSING_LOCATIONS);
        $tool->syncTagsWithType($tool->concept->certification(), TagTypes::CERTIFICATIONS);
        $tool->syncTagsWithType($tool->concept->workingMethods(), TagTypes::WORKING_METHODS);
        $tool->syncTagsWithType($tool->concept->targetGroup(), TagTypes::TARGET_GROUPS);
        $tool->syncTagsWithType($tool->concept->complexity(), TagTypes::COMPLEXITY);

        $tool->refresh();

        if ($this->hasDataChanged($dataBeforePublish, $tool)) {
            $this->emailStakeholdersAction->execute($tool);
        }

        $this->discardAction->execute($tool);
    }

    private function hasDataChanged(Tool $toolBefore, Tool $toolAfter): bool
    {
        $attributes = [
            ...$toolBefore->getFillable(),
            ...Tool::$images,
        ];

        foreach ($attributes as $attribute) {
            if ($toolBefore->{$attribute} !== $toolAfter->{$attribute}) {
                return true;
            }
        }

        $tagsBefore = $toolBefore->tags()->orderBy('id')->pluck('id')->toArray();
        $tagsAfter = $toolAfter->tags()->orderBy('id')->pluck('id')->toArray();

        return $tagsBefore !== $tagsAfter;
    }
}
