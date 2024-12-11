<?php

declare(strict_types=1);

namespace App\Http\Resources\Other;

use App\Enums\Tags\TagTypes;
use App\Helpers\WYSIWYG;
use App\Http\Resources\TagResource;
use App\Http\Resources\ToolResource as BaseToolResource;
use App\Models\Experience;

class ToolResource extends BaseToolResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        $toolResource = parent::toArray($request);

        $this->setEmptyWysiwygValuesToNull($toolResource);

        return array_merge($toolResource, [
            'total_experiences' => $this->experiences->count(),

            'features'                  => TagResource::collection($this->tagsWithType(TagTypes::FEATURES)),
            'software_types'            => TagResource::collection($this->tagsWithType(TagTypes::SOFTWARE_TYPES)),
            'devices'                   => TagResource::collection($this->tagsWithType(TagTypes::DEVICES)),
            'standards'                 => TagResource::collection($this->tagsWithType(TagTypes::STANDARDS)),
            'operating_systems'         => TagResource::collection($this->tagsWithType(TagTypes::OPERATING_SYSTEMS)),
            'data_processing_locations' => TagResource::collection(
                $this->tagsWithType(TagTypes::DATA_PROCESSING_LOCATIONS)
            ),
            'certifications'  => TagResource::collection($this->tagsWithType(TagTypes::CERTIFICATIONS)),
            'working_methods' => TagResource::collection($this->tagsWithType(TagTypes::WORKING_METHODS)),
            'target_groups'   => TagResource::collection($this->tagsWithType(TagTypes::TARGET_GROUPS)),
            'complexity'      => TagResource::collection($this->tagsWithType(TagTypes::COMPLEXITY)),

            'permissions' => [
                'add_to_collection'         => $request->user()->can('addToInstitute', $this->resource),
                'share_experience'          => $request->user()->can('create', [Experience::class, $this->resource]),
                'see_all_fields'            => $request->user()->can('seeAllFields', $this->resource),
                'submit_request_for_change' => $request->user()->can('submitRequestForChange', $this->resource),
            ],
        ]);
    }

    private function setEmptyWysiwygValuesToNull(array &$toolResource): void
    {
        $wysiwygAttributes = [
            'description_short',
            'addons',
            'personal_data',
            'privacy_analysis',
            'support_for_teachers',
            'availability_surf',
            'accessibility_facilities',
            'description_long',
            'use_for_education',
        ];

        foreach ($wysiwygAttributes as $wysiwygAttribute) {
            $toolResource[$wysiwygAttribute] = WYSIWYG::valueForFrontend($toolResource[$wysiwygAttribute]);
        }
    }
}
