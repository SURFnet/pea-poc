<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Institute;
use App\Models\Tag as TagModel;
use Illuminate\Contracts\Validation\Rule;

class ExistsInTags implements Rule
{
    public function __construct(public string $tagType, public ?Institute $institute = null)
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function passes($attribute, $value): bool
    {
        return TagModel::query()
            ->where('id', $value)
            ->where('type', $this->tagType)
            ->when($this->institute, fn ($query) => $query->where('institute_id', $this->institute->id))
            ->exists();
    }

    public function message(): string
    {
        return trans('validation.exists', ['attribute' => 'tag']);
    }
}
