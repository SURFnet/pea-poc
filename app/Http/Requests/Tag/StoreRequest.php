<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag;

use App\Models\Tag;

class StoreRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Tag::class);
    }
}
