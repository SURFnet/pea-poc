<?php

declare(strict_types=1);

namespace App\Http\Resources\Other;

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

            'permissions' => [
                'view' => $request->user()->can('viewOther', $this->resource),
            ],
        ]);
    }
}
