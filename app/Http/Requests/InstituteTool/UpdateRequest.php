<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTool;

use App\Enums\InstituteTool\Status;
use Illuminate\Validation\Rule;

class UpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('updateForInstitute', $this->route('tool'));
    }

    public function rules(): array
    {
        $rules = parent::rules();

        if ($this->route('tool')->isPublishedForInstitute($this->user()->institute)) {
            $rules['status'] = ['required', Rule::in(Status::toArray())];
        }

        return $rules;
    }
}
