<?php

declare(strict_types=1);

namespace App\Http\Requests\Experience;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [
            'rating'  => ['required', Rule::in(config('validation.experience.rating.in'))],
            'title'   => ['nullable', 'db_string'],
            'message' => ['nullable', 'string', 'max:' . config('validation.experience.message.max')],
        ];
    }

    public function attributes(): array
    {
        return trans('experience.attributes');
    }
}
