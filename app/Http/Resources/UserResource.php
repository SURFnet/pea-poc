<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ContentPage;
use App\Models\CustomField;
use App\Models\Institute;
use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Way2Translate\Models\Translation;

/** @mixin \App\Models\User */
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
                'manageCustomFields'    => $this->can('viewAll', CustomField::class),
                'manageOurTools'        => $this->can('manageOur', Tool::class),
                'manageTranslations'    => $this->can('viewAny', Translation::class),
                'managePages'           => $this->can('canManage', ContentPage::class),
                'impersonateInstitutes' => $this->can('impersonate', Institute::class),

                'viewAllTools'                => $this->can('viewAll', Tool::class),
                'viewAllToolsWithinInstitute' => $this->can('viewAllWithinInstitute', Tool::class),

                'viewAllTags' => $this->can('viewAll', Tag::class),
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
