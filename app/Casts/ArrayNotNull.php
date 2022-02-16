<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ArrayNotNull implements CastsAttributes
{
    /**
     * Make sure the value we get is always an array.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param mixed                               $value
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        return empty($value) ? [] : json_decode($value);
    }

    /**
     * Make sure the value we set is always a not empty array or null.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param mixed                               $value
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        return !empty($value) && is_array($value) ? json_encode($value) : null;
    }
}
