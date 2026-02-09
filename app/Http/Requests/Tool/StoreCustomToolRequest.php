<?php

declare(strict_types=1);

namespace App\Http\Requests\Tool;

use App\Models\Tool;

class StoreCustomToolRequest extends StoreRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('createCustomTool', Tool::class);
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
        ];
    }
}
