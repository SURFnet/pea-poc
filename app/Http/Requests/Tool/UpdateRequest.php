<?php

declare(strict_types=1);

namespace App\Http\Requests\Tool;

use Illuminate\Validation\Rule;

class UpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tool'));
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => ['required', Rule::unique('tools')->ignoreModel($this->route('tool')), 'db_string'],
        ]);
    }
}
