<?php

declare(strict_types=1);

namespace App\Http\Requests\Experience;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [
            'title'   => ['db_string', 'required'],
            'message' => ['nullable', 'string', 'max:' . config('validation.experience.message.max')],
        ];
    }

    public function attributes(): array
    {
        return trans('experience.attributes');
    }
}
