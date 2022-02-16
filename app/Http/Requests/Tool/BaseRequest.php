<?php

declare(strict_types=1);

namespace App\Http\Requests\Tool;

use App\Enums\Tool\AuthenticationMethod;
use App\Enums\Tool\StoredData;
use App\Enums\Tool\SupportedStandard;
use App\Models\Feature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [
            'name'              => ['required', Rule::unique('tools'), 'db_string'],
            'description_short' => ['required', 'string'],
            'image_filename'    => ['nullable', 'image', 'max:' . config('validation.tool.image.max')],

            'features'   => ['nullable', 'array'],
            'features.*' => [Rule::in(Feature::pluck('id'))],

            'supported_standards'   => ['nullable', 'array'],
            'supported_standards.*' => [Rule::in(SupportedStandard::toArray())],
            'additional_standards'  => ['nullable', 'db_string'],

            'authentication_methods'   => ['nullable', 'array'],
            'authentication_methods.*' => [Rule::in(AuthenticationMethod::toArray())],

            'stored_data'       => ['nullable', 'array'],
            'stored_data.*'     => [Rule::in(StoredData::toArray())],
            'other_stored_data' => ['nullable', 'string'],

            'european_data_storage'           => ['boolean'],
            'surf_standards_framework_agreed' => ['boolean'],
            'has_processing_agreement'        => ['boolean'],

            'description_long_1'           => ['nullable', 'string'],
            'description_1_image_filename' => ['nullable', 'image', 'max:' . config('validation.tool.image.max')],

            'description_long_2'           => ['nullable', 'string'],
            'description_2_image_filename' => ['nullable', 'image', 'max:' . config('validation.tool.image.max')],

            'info_url' => ['nullable', 'url', 'db_string'],
        ];
    }

    public function attributes(): array
    {
        return trans('tool.attributes');
    }
}
