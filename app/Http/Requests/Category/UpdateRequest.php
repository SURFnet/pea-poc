<?php

declare(strict_types=1);

namespace App\Http\Requests\Category;

use Illuminate\Validation\Rule;

class UpdateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('category'));
    }

    public function rules(): array
    {
        $uniqueToInstitute = Rule::unique('categories', 'name')
            ->where('institute_id', $this->user()->institute->id)
            ->ignoreModel($this->route('category'));

        return array_merge(parent::rules(), [
            'name' => ['required', 'db_string', $uniqueToInstitute],
        ]);
    }
}
