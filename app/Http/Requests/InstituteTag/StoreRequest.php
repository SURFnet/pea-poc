<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTag;

use App\Models\Tag;

class StoreRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('createForInstitute', Tag::class);
    }
}
