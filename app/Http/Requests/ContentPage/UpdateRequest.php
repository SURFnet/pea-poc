<?php

declare(strict_types=1);

namespace App\Http\Requests\ContentPage;

class UpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('content_page'));
    }
}
