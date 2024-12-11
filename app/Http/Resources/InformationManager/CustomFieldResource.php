<?php

declare(strict_types=1);

namespace App\Http\Resources\InformationManager;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CustomField */
class CustomFieldResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return [
            'id'       => $this->id,
            'title_en' => $this->title_en,
            'title_nl' => $this->title_nl,
        ];
    }
}
