<?php

declare(strict_types=1);

namespace App\Http\Requests\HomepageInformation;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [
            'homepage_title_en' => ['nullable'],
            'homepage_body_en'  => ['nullable', 'db_text'],
            'homepage_title_nl' => ['nullable'],
            'homepage_body_nl'  => ['nullable', 'db_text'],
        ];
    }
}
