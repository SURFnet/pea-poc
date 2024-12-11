<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait SearchesLocalizedFields
{
    public function scopeSearchLocalizedField(Builder $query, string $field, string $value, string $table = null): void
    {
        $englishField = $this->getFullColumnName($field, 'en', $table);
        $dutchField = $this->getFullColumnName($field, 'nl', $table);
        $pattern = '%' . $value . '%';

        $query->where($englishField, 'LIKE', $pattern)->orWhere($dutchField, 'LIKE', $pattern);
    }

    private function getFullColumnName(string $field, string $locale, string $table = null): string
    {
        return ($table ? $table . '.' : '') . $field . '_' . $locale;
    }
}
