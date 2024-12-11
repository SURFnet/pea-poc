<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\Tool\Tabs;
use App\Helpers\Locale;
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
            'id' => $this->id,

            'institute'        => $this->whenLoaded('institute', fn () => new InstituteResource($this->institute)),
            'title'            => Locale::getLocalizedFieldValue($this->resource, 'title'),
            'title_en'         => $this->title_en,
            'title_nl'         => $this->title_nl,
            'sortkey'          => $this->sortkey,
            'tab_type'         => $this->tab_type,
            'tab_type_display' => Tabs::getTranslation($this->tab_type),

            'created_at' => $this->created_at->toW3cString(),
            'updated_at' => $this->updated_at->toW3cString(),
        ];
    }
}
