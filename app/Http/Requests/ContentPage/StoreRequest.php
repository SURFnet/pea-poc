<?php

declare(strict_types=1);

namespace App\Http\Requests\ContentPage;

use App\Models\ContentPage;

class StoreRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', ContentPage::class);
    }
}
