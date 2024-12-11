<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UriRule implements Rule
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

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return false;
        }

        return in_array(parse_url($value, PHP_URL_SCHEME), config('validation.url.allowed_protocols'));
    }

    public static function addToLaravelValidation(): void
    {
        Validator::extend('uri', self::class . '@passes');
    }

    public function message(): string
    {
        return trans('validation.uri');
    }
}
