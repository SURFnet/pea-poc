<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Institute;
use App\Models\Tag;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class UniqueTagRule implements Rule, DataAwareRule
{
    private array $data;

    public function __construct(
        private readonly string $locale,
        private readonly ?Tag $tag = null,
        private readonly ?Institute $institute = null,
    ) {
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function passes($attribute, $value): bool
    {
        return !$this->tagExists($value);
    }

    private function tagExists(string $tagName): bool
    {
        $query = $this->institute !== null ? Tag::forInstitute($this->institute) : Tag::withoutInstitute();

        if ($this->tag) {
            $query->where('id', '!=', $this->tag->id);
        }

        return $query
            ->where('type', $this->data['type'])
            ->whereSlug(Str::slug($tagName), $this->locale)
            ->exists();
    }

    public function message(): string
    {
        return trans('validation.unique', ['attribute' => 'name']);
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }
}
