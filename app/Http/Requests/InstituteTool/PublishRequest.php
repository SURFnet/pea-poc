<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTool;

use App\Enums\InstituteTool\Status;
use Illuminate\Validation\Rule;

class PublishRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('publishForInstitute', $this->route('tool'));
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'status' => ['required', Rule::in(Status::toArray())],
        ]);
    }
}
