<?php

declare(strict_types=1);

namespace App\Http\Resources\Our;

use App\Enums\InstituteTool\Status;
use App\Http\Resources\ToolIndexResource as BaseToolIndexResource;

class ToolIndexResource extends BaseToolIndexResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'description_short' => $this->description_short,
            'rating'            => $this->rating,

            'institute' => [
                'status'         => $this->pivot->status,
                'status_display' => trans('institute.tool.statuses.' . ($this->pivot->status ?? Status::UNRATED)),
            ],

            'permissions' => [
                'view' => $request->user()->can('viewOur', $this->resource),
            ],
        ]);
    }
}
