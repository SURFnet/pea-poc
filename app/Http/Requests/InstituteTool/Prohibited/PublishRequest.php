<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTool\Prohibited;

class PublishRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('publishForInstitute', $this->route('tool'));
    }
}
