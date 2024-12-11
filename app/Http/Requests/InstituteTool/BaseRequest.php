<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTool;

use App\Enums\InstituteTool\DataClassification;
use App\Enums\InstituteTool\Status;
use App\Enums\Tags\TagTypes;
use App\Rules\ExistsInTags;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        $institute = $this->user()->institute;

        return [
            'alternative_tools_ids'   => ['nullable', 'array', 'max:4'],
            'alternative_tools_ids.*' => [
                Rule::in($institute->tools()->get()->pluck('id')),
            ],

            'status'        => ['nullable', Rule::in(Status::toArray())],
            'categories'    => ['nullable', 'array'],
            'categories.*'  => [new ExistsInTags(TagTypes::CATEGORIES, $institute)],
            'conditions_en' => ['nullable', 'db_text'],
            'conditions_nl' => ['nullable', 'db_text'],

            'links_with_other_tools_en' => ['nullable', 'db_text'],
            'links_with_other_tools_nl' => ['nullable', 'db_text'],
            'sla_url'                   => ['nullable', 'uri', 'db_string'],

            'privacy_contact'         => ['nullable', 'db_string'],
            'privacy_evaluation_url'  => ['nullable', 'uri', 'db_string'],
            'security_evaluation_url' => ['nullable', 'uri', 'db_string'],
            'data_classification'     => ['nullable', Rule::in(DataClassification::toArray())],

            'how_to_login_en'           => ['nullable', 'db_string'],
            'how_to_login_nl'           => ['nullable', 'db_string'],
            'availability_en'           => ['nullable', 'db_string'],
            'availability_nl'           => ['nullable', 'db_string'],
            'licensing_en'              => ['nullable', 'db_string'],
            'licensing_nl'              => ['nullable', 'db_string'],
            'request_access_en'         => ['nullable', 'db_text'],
            'request_access_nl'         => ['nullable', 'db_text'],
            'instructions_en'           => ['nullable', 'db_text'],
            'instructions_nl'           => ['nullable', 'db_text'],
            'instructions_manual_1_url' => ['nullable', 'uri', 'db_string'],
            'instructions_manual_2_url' => ['nullable', 'uri', 'db_string'],
            'instructions_manual_3_url' => ['nullable', 'uri', 'db_string'],

            'faq_en'                     => ['nullable', 'db_text'],
            'faq_nl'                     => ['nullable', 'db_text'],
            'examples_of_usage_en'       => ['nullable', 'db_text'],
            'examples_of_usage_nl'       => ['nullable', 'db_text'],
            'additional_info_heading_en' => ['nullable', 'db_string'],
            'additional_info_heading_nl' => ['nullable', 'db_string'],
            'additional_info_text_en'    => ['nullable', 'db_text'],
            'additional_info_text_nl'    => ['nullable', 'db_text'],

            'custom_fields'            => ['nullable', 'array'],
            'custom_fields.*.id'       => ['numeric'],
            'custom_fields.*.value_nl' => ['nullable', 'string'],
            'custom_fields.*.value_en' => ['nullable', 'string', 'required_with:custom_fields.*.value_nl'],

            'why_unfit_nl' => ['nullable', 'db_text'],
            'why_unfit_en' => ['nullable', 'db_text'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'why_unfit_nl' => $this->status === Status::DISALLOWED ? $this->why_unfit_nl : null,
            'why_unfit_en' => $this->status === Status::DISALLOWED ? $this->why_unfit_en : null,
        ]);
    }

    public function attributes(): array
    {
        return trans('institute.tool.attributes');
    }
}
