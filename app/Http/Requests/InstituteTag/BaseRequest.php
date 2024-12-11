<?php

declare(strict_types=1);

namespace App\Http\Requests\InstituteTag;

use App\Enums\Tags\TagTypes;
use App\Rules\UniqueTagRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        $user = Auth::user();

        return [
            'description.en' => ['nullable'],
            'description.nl' => ['nullable'],
            'name.en'        => [
                'required',
                'db_string',
                new UniqueTagRule('en', $this->route('tag'), $user->institute),
            ],
            'name.nl' => [
                'nullable',
                'db_string',
                new UniqueTagRule('nl', $this->route('tag'), $user->institute),
            ],
            'type' => ['required', Rule::in(TagTypes::CATEGORIES)],
        ];
    }

    public function attributes()
    {
        return trans('tag.attributes');
    }
}
