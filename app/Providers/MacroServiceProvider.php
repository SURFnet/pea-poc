<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->addOrderByArray();

        $this->addWhereLike();
    }

    private function addOrderByArray(): void
    {
        Builder::macro('orderByArray', function (string $attribute, array $customOrder) {
            /** @var Builder */
            $builder = $this;

            $orderedValues = implode(', ', array_map(
                fn ($value) => DB::getPdo()->quote($value),
                $customOrder
            ));

            return $builder->orderByRaw("FIELD($attribute, $orderedValues)");
        });
    }

    /** Source: https://freek.dev/1182-searching-models-using-a-where-like-query-in-laravel */
    private function addWhereLike(): void
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            /** @var Builder */
            $builder = $this;

            $builder->where(function (Builder $query) use ($attributes, $searchTerm): void {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        Str::contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm): void {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas(
                                $relationName,
                                function (Builder $query) use ($relationAttribute, $searchTerm): void {
                                    $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                                }
                            );
                        },
                        function (Builder $query) use ($attribute, $searchTerm): Builder {
                            return $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });
    }
}
