<?php

declare(strict_types=1);

namespace App\Actions\Tool;

use App\Actions\ToolLog\CreateAction as CreateToolLogAction;
use App\Enums\Tags\TagTypes;
use App\Helpers\ToolPrefillData;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function execute(array $data, User $user): Tool
    {
        ToolPrefillData::replaceWithNull($data);

        $tool = Tool::create($data);

        $tagTypeMapping = [
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

        foreach ($tagTypeMapping as $key => $tagType) {
            if (isset($data[$key])) {
                $tool->syncTagsWithType(Tag::whereIn('id', $data[$key])->get(), $tagType);
            }
        }

        (new StoreImagesAction())->execute($tool, $data);
        (new CreateToolLogAction())->execute($tool, $user);

        return $tool;
    }
}
