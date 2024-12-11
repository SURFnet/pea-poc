<?php

declare(strict_types=1);

namespace App\Http\Requests\CustomField;

use App\Enums\Tool\Tabs;
use App\Models\CustomField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [
            'title_en' => ['required', 'db_string', $this->getUniqueRule('title_en')],
            'title_nl' => ['nullable', 'db_string', $this->getUniqueRule('title_nl')],
            'sortkey'  => ['nullable', 'numeric', 'between:1,20'],
            'tab_type' => ['required', Rule::in(Tabs::toArray())],
        ];
    }

    public function attributes(): array
    {
        return trans('custom-field.attributes');
    }

    private function getUniqueRule(string $attribute): Unique
    {
        $instituteId = $this->user()->institute->id;

        $rule = Rule::unique('custom_fields', $attribute)->where('institute_id', $instituteId);

        /** @var CustomField $customField */
        $customField = $this->route('custom_field');
        if ($customField !== null) {
            $rule = $rule->ignoreModel($customField);
        }

        return $rule;
    }
}
