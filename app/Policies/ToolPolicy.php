<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\InstituteTool\Status;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Models\User;

class ToolPolicy
{
    public function viewAll(User $currentUser): bool
    {
        return $currentUser->isContentManager();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function viewAllOther(User $currentUser): bool
    {
        return true;
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function viewAllOur(User $currentUser): bool
    {
        return true;
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function viewOther(User $currentUser, Tool $tool): bool
    {
        return true;
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function viewOur(User $currentUser, Tool $tool): bool
    {
        return $currentUser->institute->hasPublishedTool($tool);
    }

    public function create(User $currentUser): bool
    {
        return $currentUser->isContentManager();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(User $currentUser, Tool $tool): bool
    {
        return $currentUser->isContentManager();
    }

    public function publish(User $currentUser, Tool $tool): bool
    {
        return !$tool->is_published && $currentUser->isContentManager();
    }

    public function manageOur(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }

    public function addToInstitute(User $currentUser, Tool $tool): bool
    {
        return $tool->is_published
            && $currentUser->isInformationManager()
            && !$currentUser->institute->hasTool($tool);
    }

    public function updateForInstitute(User $currentUser, Tool $tool): bool
    {
        return $tool->is_published
            && $currentUser->isInformationManager()
            && $currentUser->institute->hasTool($tool);
    }

    public function publishForInstitute(User $currentUser, Tool $tool): bool
    {
        return $tool->is_published
            && $currentUser->isInformationManager()
            && $currentUser->institute->hasTool($tool);
    }

    public function getSupport(User $currentUser, Tool $tool): bool
    {
        /** @var InstituteTool $instituteTool */
        $instituteTool = $currentUser->institute->tools()->find($tool)->pivot;

        if (!$currentUser->isTeacher() && !$currentUser->isInformationManager()) {
            return false;
        }

        return in_array($instituteTool->status, [Status::RECOMMENDED, Status::SUPPORTED]);
    }
}
