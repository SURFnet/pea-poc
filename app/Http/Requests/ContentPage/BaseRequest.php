<?php

declare(strict_types=1);

namespace App\Http\Requests\ContentPage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [
            'slug' => [
                'required',
                'db_string',
                Rule::unique('content_pages')->ignore($this->route('content_page')),
            ],
            'title_en' => ['required', 'db_string'],
            'title_nl' => ['nullable', 'db_string'],
            'body_en'  => ['required', 'db_text'],
            'body_nl'  => ['nullable', 'db_text'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
    }
}
