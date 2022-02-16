<?php

declare(strict_types=1);

namespace App\Http\Requests\Tool;

class PublishRequest extends UpdateRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('publish', $this->route('tool'));
    }
}
