<?php

declare(strict_types=1);

namespace App\Http\Controllers\Concerns;

use App\Enums\Tags\TagTypes;
use App\Http\Resources\TagResource;
use App\Models\Tag;

trait HasToolTags
{
    private function getToolTags(): array
    {
        $tags = Tag::whereIn('type', [
            TagTypes::FEATURES,
            TagTypes::SOFTWARE_TYPES,
            TagTypes::DEVICES,
            TagTypes::STANDARDS,
            TagTypes::OPERATING_SYSTEMS,
            TagTypes::DATA_PROCESSING_LOCATIONS,
            TagTypes::CERTIFICATIONS,
            TagTypes::WORKING_METHODS,
            TagTypes::TARGET_GROUPS,
            TagTypes::COMPLEXITY,
        ])->get();

        return [
            'features' => TagResource::collection(
                $tags->where('type', TagTypes::FEATURES)
            ),
            'softwareTypes' => TagResource::collection(
                $tags->where('type', TagTypes::SOFTWARE_TYPES)
            ),
            'devices' => TagResource::collection(
                $tags->where('type', TagTypes::DEVICES)
            ),
            'standards' => TagResource::collection(
                $tags->where('type', TagTypes::STANDARDS)
            ),
            'operatingSystems' => TagResource::collection(
                $tags->where('type', TagTypes::OPERATING_SYSTEMS)
            ),
            'dataProcessingLocations' => TagResource::collection(
                $tags->where('type', TagTypes::DATA_PROCESSING_LOCATIONS)
            ),
            'certifications' => TagResource::collection(
                $tags->where('type', TagTypes::CERTIFICATIONS)
            ),
            'workingMethods' => TagResource::collection(
                $tags->where('type', TagTypes::WORKING_METHODS)
            ),
            'targetGroups' => TagResource::collection(
                $tags->where('type', TagTypes::TARGET_GROUPS)
            ),
            'complexities' => TagResource::collection(
                $tags->where('type', TagTypes::COMPLEXITY)
            ),
        ];
    }
}
