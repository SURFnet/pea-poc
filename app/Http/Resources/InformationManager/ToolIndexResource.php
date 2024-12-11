<?php

declare(strict_types=1);

namespace App\Http\Resources\InformationManager;

use App\Enums\InstituteTool\Status;
use App\Helpers\Locale;
use App\Http\Resources\BaseToolIndexResource;
use App\Http\Resources\TagResource;
use App\Models\ConceptInstituteTool;
use App\Models\Institute;
use App\Models\InstituteTool;
use Illuminate\Http\Request;

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
        $instituteTool = $this->getInstituteTool($request);

        return [
            ...parent::toArray($request),
            ...$this->getInstituteToolData($instituteTool->concept ?? $instituteTool),
            ...[
                'name'              => $tool->name,
                'description_short' => Locale::getLocalizedFieldValue($tool, 'description_short'),

                'description_short_en' => $tool->description_short_en,

                'total_experiences' => $tool->experiences()->count(),

                'has_concept' => $instituteTool->concept !== null,

                'permissions' => [
                    'update' => $request->user()->can('updateForInstitute', $tool),
                    'view'   => $request->user()->can('viewOther', $tool),
                ],
            ],
        ];
    }

    protected function getInstitute(Request $request): Institute
    {
        return $request->user()->institute;
    }

    private function getInstituteTool(Request $request): InstituteTool
    {
        return InstituteTool::forTool($this->getTool())
            ->forInstitute($this->getInstitute($request))
            ->first();
    }

    private function getInstituteToolData(InstituteTool|ConceptInstituteTool $instituteTool): array
    {
        $tool = $this->getTool();

        return [
            'institute' => [
                'status'         => $instituteTool->status_display,
                'status_display' => Status::getTranslation($instituteTool->status_display),
                'categories'     => TagResource::collection($instituteTool->categories()),
            ],

            'edit_url' => route('information-manager.tool.edit', $tool),
        ];
    }
}
