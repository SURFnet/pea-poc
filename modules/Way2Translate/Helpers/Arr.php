<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Helpers;

use Illuminate\Support\Arr as IlluminateArr;

class Arr
{
    public static function undot(array $dottedArray): array
    {
        $unDotted = [];
        foreach ($dottedArray as $key => $value) {
            IlluminateArr::set($unDotted, $key, $value);
        }

        return $unDotted;
    }
}
