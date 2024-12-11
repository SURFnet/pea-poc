<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DefaultSortScope implements Scope
{
    public function __construct(private readonly string $sortField)
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->orderBy($this->sortField);
    }
}
