<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PendingToolEdit */
class PendingToolEditResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return [
            'user'       => new UserResource($this->user),
            'created_at' => $this->created_at->toW3cString(),
        ];
    }
}
