<?php

declare(strict_types=1);

namespace App\Http\Resources\ContentManager;

use App\Http\Resources\ToolResource as BaseToolResource;
use App\Models\ConceptTool;

class ConceptToolResource extends BaseToolResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        /** @var ConceptTool $concept */
        $concept = $this->resource;

        return [
            ...parent::toArray($request),

            'id'           => $concept->originalVersion->id,
            'is_published' => $concept->originalVersion->is_published,

            'description_short_en' => $concept->description_short_en,
            'description_short_nl' => $concept->description_short_nl,
            'addons_en'            => $concept->addons_en,
            'addons_nl'            => $concept->addons_nl,

            'system_requirements_en' => $concept->system_requirements_en,
            'system_requirements_nl' => $concept->system_requirements_nl,

            'personal_data_en' => $concept->personal_data_en,
            'personal_data_nl' => $concept->personal_data_nl,

            'instructions_manual_1_url_en' => $concept->instructions_manual_1_url_en,
            'instructions_manual_1_url_nl' => $concept->instructions_manual_1_url_nl,
            'instructions_manual_2_url_en' => $concept->instructions_manual_2_url_en,
            'instructions_manual_2_url_nl' => $concept->instructions_manual_2_url_nl,
            'instructions_manual_3_url_en' => $concept->instructions_manual_3_url_en,
            'instructions_manual_3_url_nl' => $concept->instructions_manual_3_url_nl,
            'support_for_teachers_en'      => $concept->support_for_teachers_en,
            'support_for_teachers_nl'      => $concept->support_for_teachers_nl,
            'accessibility_facilities_en'  => $concept->accessibility_facilities_en,
            'accessibility_facilities_nl'  => $concept->accessibility_facilities_nl,
            'description_long_en'          => $concept->description_long_en,
            'description_long_nl'          => $concept->description_long_nl,
            'use_for_education_en'         => $concept->use_for_education_en,
            'use_for_education_nl'         => $concept->use_for_education_nl,
            'how_does_it_work_en'          => $concept->how_does_it_work_en,
            'how_does_it_work_nl'          => $concept->how_does_it_work_nl,

            'updated_at' => $this->updated_at->toW3cString(),

            'permissions' => [
                'publish' => $request->user()->can('publish', $concept->originalVersion),
            ],
        ];
    }
}
