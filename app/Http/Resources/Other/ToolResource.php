<?php

declare(strict_types=1);

namespace App\Http\Resources\Other;

use App\Http\Resources\ToolResource as BaseToolResource;
use App\Models\Experience;

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
            'rating'            => $this->rating,
            'total_experiences' => $this->experiences->count(),

            'permissions' => [
                'add_to_collection'         => $request->user()->can('addToInstitute', $this->resource),
                'share_experience'          => $request->user()->can('create', [Experience::class, $this->resource]),
                'see_technical_information' => $request->user()->isInformationManager(),
            ],
        ]);
    }
}
