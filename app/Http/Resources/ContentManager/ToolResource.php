<?php

declare(strict_types=1);

namespace App\Http\Resources\ContentManager;

use App\Http\Resources\ToolResource as BaseToolResource;
use App\Models\Tool;

class ToolResource extends BaseToolResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        /** @var Tool $tool */
        $tool = $this->resource;

        return [
            ...parent::toArray($request),

            'id'           => $tool->id,
            'is_published' => $tool->is_published,

            'description_short_en' => $tool->description_short_en,
            'description_short_nl' => $tool->description_short_nl,

            'permissions' => [
                'publish' => $request->user()->can('publish', $tool),
            ],
        ];
    }
}
