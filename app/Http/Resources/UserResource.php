<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Way2Translate\Models\Translation;

/** @extends JsonResource<\App\Models\User> */
class UserResource extends JsonResource
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

            'name'      => $this->name,
            'institute' => new InstituteResource($this->institute),

            'created_at' => $this->created_at->toW3cString(),
            'updated_at' => $this->updated_at->toW3cString(),

            'roles' => $this->getRoles(),

            'permissions' => [
                'manageCategories'   => $this->can('viewAll', Category::class),
                'manageOurTools'     => $this->can('manageOur', Tool::class),
                'manageTranslations' => $this->can('viewAny', Translation::class),

                'viewAllTools'      => $this->can('viewAll', Tool::class),
                'viewAllOurTools'   => $this->can('viewAllOur', Tool::class),
                'viewAllOtherTools' => $this->can('viewAllOther', Tool::class),
            ],
        ];
    }

    private function getRoles(): array
    {
        $possibleRoles = [
            'super_admin'         => $this->isSuperAdmin(),
            'content_manager'     => $this->isContentManager(),
            'information_manager' => $this->isInformationManager(),
            'teacher'             => $this->isTeacher(),
        ];

        return array_keys(array_filter($possibleRoles));
    }
}
