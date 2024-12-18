<?php

declare(strict_types=1);

namespace App\Http\Requests\Tool;

use App\Models\Tool;

class StoreRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Tool::class);
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'logo_filename' => ['required', 'image', 'max:' . config('validation.tool.image.max')],
        ];
    }
}
