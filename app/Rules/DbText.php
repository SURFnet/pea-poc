<?php

declare(strict_types=1);

namespace App\Rules;

use App\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DbText implements Rule
{
    private int $length;

    public function __construct()
    {
        $this->length = config('validation.db_text.length');
    }

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

        return is_string($value) && (strlen($value) <= $this->length);
    }

    public static function addToLaravelValidation(): void
    {
        Validator::extend('db_text', self::class . '@passes');

        $length = config('validation.db_text.length');
        Validator::replacer('db_text', function ($message) use ($length) {
            return str_replace(':length', (string) $length, $message);
        });
    }

    public function message(): string
    {
        return trans('validation.db_string', ['length' => $this->length]);
    }
}
