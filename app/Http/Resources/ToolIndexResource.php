<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\InstituteTool\Status;
use App\Helpers\Locale;

class ToolIndexResource extends BaseToolIndexResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        $viewPermission = $this->institute_tool_id ? 'viewOur' : 'viewOther';

        $data = [
            ...parent::toArray($request),

            'description_short' => Locale::getLocalizedFieldValue($this->resource, 'description_short'),
            'total_experiences' => $this->experiences->count(),

            'permissions' => [
                'view' => $request->user()->can($viewPermission, $this->resource),
            ],
        ];

        if (!$this->institute_tool_id) {
            return $data;
        }

        return [
            ...$data,
            'institute' => [
                'status'         => $this->status_institute,
                'status_display' => Status::getTranslation($this->status_institute ?? Status::UNRATED),
            ],
        ];
    }
}
