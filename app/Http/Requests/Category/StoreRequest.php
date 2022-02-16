<?php

declare(strict_types=1);

namespace App\Http\Requests\Category;

use App\Models\Category;

class StoreRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Category::class);
    }
}
