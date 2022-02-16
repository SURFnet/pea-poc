<?php

declare(strict_types=1);

namespace App\Contracts\Validation;

use Illuminate\Contracts\Validation\Rule as IlluminateRule;

interface Rule extends IlluminateRule
{
    public static function addToLaravelValidation(): void;
}
