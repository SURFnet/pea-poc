<?php

declare(strict_types=1);

namespace App\Rules;

use App\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class VATNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return true;
        }

        return preg_match(config('validation.regex.vat_number'), $value);
    }

    public static function addToLaravelValidation(): void
    {
        Validator::extend('vat_number', self::class . '@passes');
    }

    public function message(): string
    {
        return trans('validation.vat_number');
    }
}
