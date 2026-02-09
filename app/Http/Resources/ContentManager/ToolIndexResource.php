<?php

declare(strict_types=1);

namespace App\Http\Resources\ContentManager;

use App\Http\Resources\BaseToolIndexResource;
use App\Http\Resources\TagResource;
use App\Models\ConceptTool;

class ToolIndexResource extends BaseToolIndexResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        $tool = $this->getTool();
        $concept = $this->getConceptTool();

        return [
            ...$this->getToolData($concept ?? $tool),

            'slug' => $tool->slug,
            'id'   => $tool->id,

            'status'         => $tool->status,
            'status_display' => $tool->status_display,

            'features' => TagResource::collection($tool->features()),

            'has_concept' => $concept !== null,

            'is_custom' => $tool->institute_id !== null,

            'permissions' => [
                'update'           => $request->user()->can('update', $tool),
                'view_custom_tool' => $request->user()->can('view', $tool),
                'convert'          => $request->user()->can('convert', $tool),
            ],
        ];
    }

    protected function getConceptTool(): ?ConceptTool
    {
        return $this->resource->concept;
    }
}
