<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;

class Model
{
    public static function asSelect(
        Collection $data,
        string $identifier,
        string $label,
        bool $sortByLabel = true
    ): array {
        if ($sortByLabel) {
            $data = $data->sortBy($label);
        }

        return $data->pluck($label, $identifier)->toArray();
    }
}
