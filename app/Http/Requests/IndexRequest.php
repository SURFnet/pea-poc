<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search'   => ['nullable', 'string'],
            'page'     => ['nullable', 'string'],
            'sortBy'   => ['nullable', 'string'],
            'sortDesc' => ['nullable', 'string', Rule::in(['true', 'false'])],
        ];
    }
}
