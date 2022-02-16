<?php

declare(strict_types=1);

namespace App\QueryBuilder\Filters;

use App\Enums\InstituteTool\Status;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class InstituteToolStatusFilter implements Filter
{
    /**
     * @param string $value
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(Builder $query, $value, string $property): void
    {
        switch ($value) {
            case Status::UNRATED:
                $query->whereNull('institute_tool.status');
                break;

            case Status::UNPUBLISHED:
                $query->whereNull('institute_tool.published_at');
                break;

            default:
                $query->whereNotNull('institute_tool.status');
                $query->whereNotNull('institute_tool.published_at');
                $query->where('institute_tool.status', $value);
        }
    }
}
