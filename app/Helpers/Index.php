<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Http\Requests\IndexRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Index
{
    /** @param \Illuminate\Database\Eloquent\Builder|\Spatie\QueryBuilder\QueryBuilder $data */
    public static function forTable(
        $data,
        IndexRequest $request,
        ?int $perPage = null
    ): LengthAwarePaginator {
        if (empty($perPage)) {
            $perPage = config('pagination.per_page');
        }

        return $data
            ->paginate($perPage)
            ->appends($request->query());
    }
}
