<?php

declare(strict_types=1);

namespace App\Http\Resources\ContentManager;

use App\Http\Resources\FeatureResource;
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
            'status'         => $this->status,
            'status_display' => $this->status_display,

            'features' => $this->whenLoaded('features', fn () => FeatureResource::collection($this->features)),

            'permissions' => [
                'update' => $request->user()->can('update', $this->resource),
            ],
        ]);
    }
}
