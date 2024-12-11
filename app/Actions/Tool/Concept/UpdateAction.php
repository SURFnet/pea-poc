<?php

declare(strict_types=1);

namespace App\Actions\Tool\Concept;

use App\Actions\PendingToolEdit\ClearAction;
use App\Actions\ToolLog\CreateAction as CreateToolLogAction;
use App\Enums\Tags\TagTypes;
use App\Helpers\ToolPrefillData;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Tool $tool, User $user, array $data): void
    {
        (new ClearAction())->execute($tool, $user);

        $concept = $tool->getOrCreateConceptVersion();

        ToolPrefillData::replaceWithNull($data);

        $concept->update($data);

        $dataKeysWithTagTypes = [
            'features'                  => TagTypes::FEATURES,
            'software_types'            => TagTypes::SOFTWARE_TYPES,
            'devices'                   => TagTypes::DEVICES,
            'standards'                 => TagTypes::STANDARDS,
            'operating_systems'         => TagTypes::OPERATING_SYSTEMS,
            'data_processing_locations' => TagTypes::DATA_PROCESSING_LOCATIONS,
            'certifications'            => TagTypes::CERTIFICATIONS,
            'working_methods'           => TagTypes::WORKING_METHODS,
            'target_groups'             => TagTypes::TARGET_GROUPS,
            'complexity'                => TagTypes::COMPLEXITY,
        ];

        foreach ($dataKeysWithTagTypes as $dataKey => $tagType) {
            $concept->syncTagsWithType(Tag::whereIn('id', $data[$dataKey] ?? [])->get(), $tagType);
        }

        (new StoreImagesAction())->execute($tool, $data);
        (new CreateToolLogAction())->execute($tool, $user);
    }
}
