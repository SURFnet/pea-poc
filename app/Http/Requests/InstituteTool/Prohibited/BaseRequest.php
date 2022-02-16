<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTool\Prohibited;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        $alternativeTools = $this->route('tool')->getAvailableAlternativeTools($this->user()->institute);

        return [
            'why_unfit'           => ['nullable', 'string'],
            'alternative_tool_id' => ['nullable', Rule::in($alternativeTools->pluck('id'))],
        ];
    }

    public function attributes(): array
    {
        return trans('tool.attributes');
    }
}
