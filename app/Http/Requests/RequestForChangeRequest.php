<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestForChangeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('submitRequestForChange', $this->route('tool'));
    }

    public function rules(): array
    {
        return [
            'request_for_change' => ['required', 'string'],
        ];
    }
}
