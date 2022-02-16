<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @extends JsonResource<\App\Models\Category> */
class CategoryResource extends JsonResource
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

            'institute'             => $this->whenLoaded('institute', fn () => new InstituteResource($this->institute)),
            'name'                  => $this->name,
            'description'           => $this->description,
            'description_truncated' => Str::limit($this->description, 100),

            'created_at' => $this->created_at->toW3cString(),
            'updated_at' => $this->updated_at->toW3cString(),
        ];
    }
}
