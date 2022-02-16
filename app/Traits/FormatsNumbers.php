<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\Format;

/**
 * Has global accessor and mutator for getting and setting certain number attributes
 * in the right format.
 *
 * Installation / usage:
 * - Add this trait to a model
 * - Add a protected $numberAttributes array to the model, either empty, or with the attributes that need to be mutated
 * - Add a protected $intAttributes array to the model, either empty, or with the attributes that need to be mutated
 * - For the validation rule, you can use: "self::getNumberRegexRule()" in the validation function
 */
trait FormatsNumbers
{
    /** Global accessor. Converts certain numbers to strings. */
    public function getAttributeValue(string $key): string
    {
        if (in_array($key, $this->intAttributes)) {
            return Format::numberToString($this->getRawOriginal($key), 0);
        }

        if (in_array($key, $this->numberAttributes)) {
            return Format::numberToString($this->getRawOriginal($key));
        }

        return parent::getAttributeValue($key);
    }

    /** Global mutator. Converts certain strings to numbers. */
    public function setAttribute(string $key, $value): void
    {
        if (in_array($key, $this->numberAttributes) && gettype($value) == 'string') {
            $value = Format::stringToNumber($value);
        }

        parent::setAttribute($key, $value);
    }

    protected static function getNumberRegexRule(): string
    {
        return 'regex:@^[0-9,\\.]+$@';
    }
}
