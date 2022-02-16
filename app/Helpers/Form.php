<?php

declare(strict_types=1);

namespace App\Helpers;

class Form
{
    /**
     * This function adds posted values to the select options.
     *
     * This ensures that newly added options (for example using a select2 tag field)
     * can be pre-selected after form validations fails.
     *
     * @param mixed $oldValues
     */
    public static function appendNewOldValues(array $options, $oldValues): array
    {
        if (empty($oldValues) || !is_array($oldValues)) {
            return $options;
        }

        foreach ($oldValues as $oldValue) {
            if (!isset($options[$oldValue])) {
                $options[$oldValue] = $oldValue;
            }
        }

        return $options;
    }

    /**
     * When Laravel returns validation errors for array inputs,
     * it uses a different notation.
     * This method can be used to convert the notation
     * so that our form components still work.
     */
    public static function arrayNameToError(string $name): string
    {
        $name = str_replace('[', '.', $name);
        $name = str_replace(']', '', $name);

        return $name;
    }
}
