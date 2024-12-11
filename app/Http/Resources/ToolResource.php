<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\Tags\TagTypes;
use App\Helpers\Country;
use App\Helpers\Format;
use App\Helpers\Locale;
use App\Models\Tool;
use App\Traits\Resources\WithImage;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Tool */
class ToolResource extends JsonResource
{
    use WithImage;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        /** @var Tool $tool */
        $tool = $this->resource;
        $shortDescription = Locale::getLocalizedFieldValue($tool, 'description_short');

        $supplierCountry = $tool->supplier_country;
        $dataProcessingLocation = $tool->tagsWithType(TagTypes::DATA_PROCESSING_LOCATIONS)->pluck('id');

        return [
            'id' => $tool->id,

            'name'                            => $tool->name,
            'supplier'                        => $tool->supplier,
            'supplier_url'                    => $tool->supplier_url,
            'description_short'               => $shortDescription,
            'description_short_stripped_tags' => Format::asSimpleHtml($shortDescription),
            'features'                        => $tool->tagsWithType(TagTypes::FEATURES)->pluck('id'),
            'addons'                          => Locale::getLocalizedFieldValue($tool, 'addons'),
            'logo_filename'                   => $tool->logo_filename,
            'logo_url'                        => $this->getImageUrl($tool->logo_filename, true),
            'image_1_filename'                => $this->image_1_filename,
            'image_1_url'                     => $this->getImageUrl($tool->image_1_filename),
            'image_2_filename'                => $tool->image_2_filename,
            'image_2_url'                     => $this->getImageUrl($tool->image_2_filename),

            'software_types' => $tool->tagsWithType(TagTypes::SOFTWARE_TYPES)->pluck('id'),
            'devices'        => $tool->tagsWithType(TagTypes::DEVICES)->pluck('id'),

            'system_requirements' => Locale::getLocalizedFieldValue($tool, 'system_requirements'),

            'standards'         => $tool->tagsWithType(TagTypes::STANDARDS)->pluck('id'),
            'operating_systems' => $tool->tagsWithType(TagTypes::OPERATING_SYSTEMS)->pluck('id'),

            'supplier_country'                    => $supplierCountry,
            'supplier_country_display'            => $supplierCountry ? Country::getName($supplierCountry) : null,
            'personal_data'                       => Locale::getLocalizedFieldValue($tool, 'personal_data'),
            'data_processing_locations'           => $dataProcessingLocation,
            'privacy_policy_url'                  => $tool->privacy_policy_url,
            'model_processor_agreement_url'       => $tool->model_processor_agreement_url,
            'privacy_analysis'                    => $tool->privacy_analysis,
            'supplier_agrees_with_surf_standards' => $tool->supplier_agrees_with_surf_standards,
            'certifications'                      => $tool->tagsWithType(TagTypes::CERTIFICATIONS)->pluck('id'),
            'dtia_by_external_url'                => $tool->dtia_by_external_url,
            'dpia_by_external_url'                => $tool->dpia_by_external_url,
            'jurisdiction'                        => $tool->jurisdiction,

            'instructions_manual_1_url' => Locale::getLocalizedFieldValue($tool, 'instructions_manual_1_url'),
            'instructions_manual_2_url' => Locale::getLocalizedFieldValue($tool, 'instructions_manual_2_url'),
            'instructions_manual_3_url' => Locale::getLocalizedFieldValue($tool, 'instructions_manual_3_url'),
            'support_for_teachers'      => Locale::getLocalizedFieldValue($tool, 'support_for_teachers'),
            'availability_surf'         => $tool->availability_surf,
            'accessibility_facilities'  => Locale::getLocalizedFieldValue($tool, 'accessibility_facilities'),
            'description_long'          => Locale::getLocalizedFieldValue($tool, 'description_long'),
            'use_for_education'         => Locale::getLocalizedFieldValue($tool, 'use_for_education'),

            'working_methods'  => $tool->tagsWithType(TagTypes::WORKING_METHODS)->pluck('id'),
            'target_groups'    => $tool->tagsWithType(TagTypes::TARGET_GROUPS)->pluck('id'),
            'how_does_it_work' => Locale::getLocalizedFieldValue($tool, 'how_does_it_work'),

            'complexity' => $tool->tagsWithType(TagTypes::COMPLEXITY)->pluck('id'),

            'updated_at' => $tool->updated_at->toW3cString(),
        ];
    }
}
