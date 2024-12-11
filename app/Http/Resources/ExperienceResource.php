<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Experience */
class ExperienceResource extends JsonResource
{
    /** @param \Illuminate\Http\Request $request */
    public function toArray($request): array
    {
        /** @var User $actingUser */
        $actingUser = $request->user();

        return [
            'id' => $this->id,

            'user'      => $actingUser->can('seeUser', $this->resource) ? new UserResource($this->user) : null,
            'institute' => new InstituteResource($this->user->institute),

            'title' => $this->title,

            'message' => $this->message_display,

            'created_at' => $this->created_at->toW3cString(),

            'permissions' => [
                'update' => $actingUser->can('update', $this->resource),
                'delete' => $actingUser->can('delete', $this->resource),
            ],
        ];
    }
}
