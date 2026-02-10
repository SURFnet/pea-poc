<?php

declare(strict_types=1);

namespace App\Http\Resources\InformationManager;

use App\Enums\InstituteTool\Status;
use App\Helpers\Locale;
use App\Http\Resources\BaseToolIndexResource;
use App\Models\InstituteTool;

class CustomToolIndexResource extends BaseToolIndexResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        $tool = $this->getTool();

        $instituteTool = InstituteTool::forTool($tool)
            ->forInstitute($request->user()->institute)
            ->first();

        return [
            ...parent::toArray($request),
            'name'                 => $tool->name,
            'description_short'    => Locale::getLocalizedFieldValue($tool, 'description_short'),
            'description_short_en' => $tool->description_short_en,

            'status'         => $tool->status,
            'status_display' => $tool->status_display,

            'has_tool_concept'  => $this->concept !== null,
            'tool_published_at' => $tool->published_at,

            // Institute Tool
            'has_institute_tool_concept'    => $instituteTool?->concept !== null,
            'institute_tool_status'         => $instituteTool?->status_display,
            'institute_tool_status_display' => $instituteTool?->status_display !== null
                ? Status::getTranslation($instituteTool->status_display)
                : null,

            // URL's
            'institute_tool_edit_url' => route('information-manager.custom-tool.information.edit', $tool),
            'edit_url'                => route('information-manager.custom-tool.edit', $tool),

            'abilities' => [
                'update'          => $request->user()->can('update', $tool),
                'delete'          => $tool->isCustomTool() && $request->user()->can('delete', $tool),
                'publish_concept' => $this->concept !== null && $request->user()->can('update', $tool),
                'discard_concept' => $this->concept !== null && $request->user()->can('update', $tool),

                'update_institute_tool' => $instituteTool !== null && $request->user()->can('update', $tool),
            ],

            'permissions' => [
                'update' => $request->user()->can('update', $tool),
                'view'   => $request->user()->can('view', $tool),
            ],
        ];
    }
}
