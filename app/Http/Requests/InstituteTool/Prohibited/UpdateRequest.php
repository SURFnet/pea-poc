<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTool\Prohibited;

class UpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('updateForInstitute', $this->route('tool'));
    }
}
