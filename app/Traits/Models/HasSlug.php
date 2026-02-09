<?php

declare(strict_types=1);

namespace App\Traits\Models;

trait HasSlug
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @see \Illuminate\Database\Eloquent\Model::resolveRouteBinding
     *
     * @param mixed       $value
     * @param string|null $field
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $query = $this->query()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->orWhere('institute_slug', '=', $value);

        if (is_numeric($value)) {
            $query->orWhere('id', '=', $value);
        }

        return $query->first();
    }
}
