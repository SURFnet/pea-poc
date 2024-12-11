<?php

declare(strict_types=1);

namespace App\Http\Resources\Our;

use App\Enums\InstituteTool\Status;
use App\Helpers\Locale;
use App\Http\Resources\BaseToolIndexResource;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Traits\Resources\WithImage;
use Illuminate\Http\Request;

class AlternativeToolResource extends BaseToolIndexResource
{
    use WithImage;

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'name'              => $this->resource->name,
            'description_short' => Locale::getLocalizedFieldValue($this->resource, 'description_short'),
            'total_experiences' => $this->experiences()->count(),
            'institute'         => $this->getInstituteData($request),
        ]);
    }

    private function getInstituteData(Request $request): array
    {
        /** @var Tool $tool */
        $tool = $this->resource;
        $institute = $request->user()->institute;

        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->first();
        if ($instituteTool === null) {
            $instituteTool = new InstituteTool();
        }

        return [
             'status'         => $instituteTool->status ?? Status::UNRATED,
             'status_display' => Status::getTranslation($instituteTool->status ?? Status::UNRATED),
         ];
    }
}
