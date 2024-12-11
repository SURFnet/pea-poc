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

            'id' => $tool->id,

            'status'         => $tool->status,
            'status_display' => $tool->status_display,

            'features' => TagResource::collection($tool->features()),

            'has_concept' => $concept !== null,

            'permissions' => [
                'update' => $request->user()->can('update', $tool),
            ],
        ];
    }

    protected function getConceptTool(): ?ConceptTool
    {
        return $this->resource->concept;
    }
}
