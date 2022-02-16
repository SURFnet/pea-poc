<?php

declare(strict_types=1);

namespace App\Http\Resources\ContentManager;

use App\Http\Resources\ToolResource as BaseToolResource;

class ToolResource extends BaseToolResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'is_published' => $this->is_published,

            'permissions' => [
                'publish' => $request->user()->can('publish', $this->resource),
            ],
        ]);
    }
}
