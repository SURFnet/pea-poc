<?php

declare(strict_types=1);

namespace App\Http\Requests\Tool;

use App\Enums\Tags\TagTypes;
use App\Helpers\Country;
use App\Rules\ExistsInTags;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('tools'), 'db_string'],

            'supplier'             => ['nullable', 'db_string'],
            'supplier_url'         => ['nullable', 'uri', 'db_string'],
            'description_short_en' => ['required', 'db_text'],
            'description_short_nl' => ['required', 'db_text'],
            'features'             => ['nullable', 'array'],
            'features.*'           => [new ExistsInTags(TagTypes::FEATURES)],
            'addons_en'            => ['nullable', 'db_text'],
            'addons_nl'            => ['nullable', 'db_text'],
            'logo_filename'        => ['nullable', 'image', 'max:' . config('validation.tool.image.max')],
            'image_1_filename'     => ['nullable', 'image', 'max:' . config('validation.tool.image.max')],
            'image_2_filename'     => ['nullable', 'image', 'max:' . config('validation.tool.image.max')],

            'software_types'         => ['nullable', 'array'],
            'software_types.*'       => [new ExistsInTags(TagTypes::SOFTWARE_TYPES)],
            'devices'                => ['nullable', 'array'],
            'devices.*'              => [new ExistsInTags(TagTypes::DEVICES)],
            'system_requirements_en' => ['nullable', 'db_string'],
            'system_requirements_nl' => ['nullable', 'db_string'],
            'standards'              => ['nullable', 'array'],
            'standards.*'            => [new ExistsInTags(TagTypes::STANDARDS)],
            'operating_systems'      => ['nullable', 'array'],
            'operating_systems.*'    => [new ExistsInTags(TagTypes::OPERATING_SYSTEMS)],

            'supplier_country'                    => ['nullable', 'string', Rule::in(Country::getCodes())],
            'personal_data_en'                    => ['nullable', 'db_text'],
            'personal_data_nl'                    => ['nullable', 'db_text'],
            'data_processing_locations'           => ['nullable', 'array'],
            'data_processing_locations.*'         => [new ExistsInTags(TagTypes::DATA_PROCESSING_LOCATIONS)],
            'privacy_policy_url'                  => ['nullable', 'uri', 'db_string'],
            'model_processor_agreement_url'       => ['nullable', 'uri', 'db_string'],
            'privacy_analysis'                    => ['nullable', 'db_text'],
            'supplier_agrees_with_surf_standards' => ['boolean'],
            'certifications'                      => ['nullable', 'array'],
            'certifications.*'                    => [new ExistsInTags(TagTypes::CERTIFICATIONS)],
            'dtia_by_external_url'                => ['nullable', 'uri', 'db_string'],
            'dpia_by_external_url'                => ['nullable', 'uri', 'db_string'],
            'jurisdiction'                        => ['nullable', 'db_string'],

            'instructions_manual_1_url_en' => ['nullable', 'uri', 'db_string'],
            'instructions_manual_1_url_nl' => ['nullable', 'uri', 'db_string'],
            'instructions_manual_2_url_en' => ['nullable', 'uri', 'db_string'],
            'instructions_manual_2_url_nl' => ['nullable', 'uri', 'db_string'],
            'instructions_manual_3_url_en' => ['nullable', 'uri', 'db_string'],
            'instructions_manual_3_url_nl' => ['nullable', 'uri', 'db_string'],
            'support_for_teachers_en'      => ['nullable', 'db_text'],
            'support_for_teachers_nl'      => ['nullable', 'db_text'],
            'availability_surf'            => ['nullable', 'db_text'],
            'accessibility_facilities_en'  => ['nullable', 'db_text'],
            'accessibility_facilities_nl'  => ['nullable', 'db_text'],

            'description_long_en'  => ['nullable', 'string'],
            'description_long_nl'  => ['nullable', 'string'],
            'use_for_education_en' => ['nullable', 'db_text'],
            'use_for_education_nl' => ['nullable', 'db_text'],
            'working_methods'      => ['nullable', 'array'],
            'working_methods.*'    => [new ExistsInTags(TagTypes::WORKING_METHODS)],
            'target_groups'        => ['nullable', 'array'],
            'target_groups.*'      => [new ExistsInTags(TagTypes::TARGET_GROUPS)],

            'how_does_it_work_en' => ['nullable', 'db_string'],
            'how_does_it_work_nl' => ['nullable', 'db_string'],
            'complexity'          => ['nullable', 'array'],
            'complexity.*'        => [new ExistsInTags(TagTypes::COMPLEXITY)],
        ];
    }

    public function attributes(): array
    {
        return trans('tool.attributes');
    }
}
