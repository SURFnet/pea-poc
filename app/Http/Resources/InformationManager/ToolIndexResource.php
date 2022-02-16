<?php

declare(strict_types=1);

namespace App\Http\Resources\InformationManager;

use App\Enums\InstituteTool\Status;
use App\Http\Resources\ToolIndexResource as BaseToolIndexResource;
use Illuminate\Support\Str;

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
            'description_short' => Str::limit($this->description_short, 30),

            'rating' => $this->rating,

            'institute' => [
                'status'         => $this->pivot->status_display,
                'status_display' => trans('institute.tool.statuses.' . $this->pivot->status_display),
            ],

            'permissions' => [
                'update' => $request->user()->can('update', $this->resource),
                'view'   => $request->user()->can('viewOther', $this->resource),
            ],

            'edit_url' => $this->pivot->status === Status::PROHIBITED
                ? route('information-manager.tool.prohibited.edit', $this)
                : route('information-manager.tool.edit', $this),
        ]);
    }
}
