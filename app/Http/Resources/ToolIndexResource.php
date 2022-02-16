<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Traits\Resources\WithImage;
use Illuminate\Http\Resources\Json\JsonResource;

/** @extends JsonResource<\App\Models\Tool> */
class ToolIndexResource extends JsonResource
{
    use WithImage;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'categories' => $this->whenLoaded('categories', fn () => CategoryResource::collection($this->categories)),

            'image_url' => $this->getImageUrl($this->image_filename, true),
        ];
    }
}
