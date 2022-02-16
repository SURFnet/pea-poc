<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTool;

class StoreRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('addToInstitute', $this->route('tool'));
    }
}
