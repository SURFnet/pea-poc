<?php

declare(strict_types=1);

namespace App\Http\Requests\CustomField;

use App\Models\CustomField;

class StoreRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', CustomField::class);
    }
}
