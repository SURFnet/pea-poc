<?php

declare(strict_types=1);

namespace App\Http\Requests\HomepageInformation;

use App\Models\Institute;

class EditRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('editHomepage', Institute::class);
    }
}
