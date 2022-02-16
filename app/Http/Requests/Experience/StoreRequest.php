<?php

declare(strict_types=1);

namespace App\Http\Requests\Experience;

use App\Models\Experience;

class StoreRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [Experience::class, $this->route('tool')]);
    }
}
