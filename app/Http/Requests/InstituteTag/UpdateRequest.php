<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTag;

class UpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('updateForInstitute', $this->route('tag'));
    }
}
