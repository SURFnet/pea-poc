<?php

declare(strict_types=1);

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        $uniqueToInstitute = Rule::unique('categories', 'name')->where('institute_id', $this->user()->institute->id);

        return [
            'name'        => ['required', 'db_string', $uniqueToInstitute],
            'description' => ['required', 'string', 'max:1024'],
        ];
    }

    public function attributes(): array
    {
        return trans('category.attributes');
    }
}
