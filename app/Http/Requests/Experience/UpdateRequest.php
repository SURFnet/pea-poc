<?php

declare(strict_types=1);

namespace App\Http\Requests\Experience;

class UpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('experience'));
    }
}
