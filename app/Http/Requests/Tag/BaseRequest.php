<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag;

use App\Enums\Tags\TagTypes;
use App\Rules\UniqueTagRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [
            'name.en' => ['required', 'db_string', new UniqueTagRule('en', $this->route('tag'))],
            'name.nl' => ['nullable', 'db_string', new UniqueTagRule('nl', $this->route('tag'))],
            'type'    => ['required', Rule::in(TagTypes::getTagsTypesToArrayExcept([TagTypes::CATEGORIES]))],
        ];
    }

    public function attributes()
    {
        return trans('tag.attributes');
    }
}
