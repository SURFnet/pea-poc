<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @extends JsonResource<\App\Models\Experience> */
class ExperienceResource extends JsonResource
{
    /** @param \Illuminate\Http\Request $request */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'user' => new UserResource($this->user),

            'rating'          => $this->rating,
            'title'           => $this->title,
            'message'         => $this->message,
            'message_display' => $this->message_display,

            'created_at' => $this->created_at->toW3cString(),

            'permissions' => [
                'update' => $request->user()->can('update', $this->resource),
                'delete' => $request->user()->can('delete', $this->resource),
            ],
        ];
    }
}
