<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTool;

use App\Enums\InstituteTool\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [
            'categories'   => ['nullable', 'array'],
            'categories.*' => [Rule::in($this->user()->institute->categories->pluck('id'))],

            'description_1'                => ['nullable', 'string'],
            'description_1_image_filename' => ['nullable', 'image', 'max:' . config('validation.tool.image.max')],

            'description_2'                => ['nullable', 'string'],
            'description_2_image_filename' => ['nullable', 'image', 'max:' . config('validation.tool.image.max')],

            'extra_information_title' => ['nullable', 'string'],
            'extra_information'       => ['nullable', 'string'],

            'support_title_1' => ['nullable', 'db_string'],
            'support_email_1' => ['nullable', 'email', 'db_string'],
            'support_title_2' => ['nullable', 'db_string'],
            'support_email_2' => ['nullable', 'email', 'db_string'],

            'manual_title_1' => ['nullable', 'db_string'],
            'manual_url_1'   => ['nullable', 'url', 'db_string'],
            'manual_title_2' => ['nullable', 'db_string'],
            'manual_url_2'   => ['nullable', 'url', 'db_string'],

            'video_title_1' => ['nullable', 'db_string'],
            'video_url_1'   => ['nullable', 'url', 'db_string'],
            'video_title_2' => ['nullable', 'db_string'],
            'video_url_2'   => ['nullable', 'url', 'db_string'],

            'status' => ['nullable', Rule::in(Status::toArray())],
        ];
    }

    public function attributes(): array
    {
        return trans('tool.attributes');
    }
}
