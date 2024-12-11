<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Auth\Role;
use App\Enums\InstituteTool\Status;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Models\User;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
class ToolPolicy
{
    public function viewAll(User $currentUser): bool
    {
        return $currentUser->isContentManager();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function viewAllWithinInstitute(User $currentUser): bool
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
        return $tool->isPublishedForInstitute($currentUser->institute);
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

    public function publishConcept(User $currentUser, Tool $tool): bool
    {
        if (!$this->update($currentUser, $tool)) {
            return false;
        }

        return $tool->concept !== null;
    }

    public function discardConcept(User $currentUser, Tool $tool): bool
    {
        return $this->publishConcept($currentUser, $tool);
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

    public function publishConceptForInstitute(User $currentUser, Tool $tool): bool
    {
        if (!$currentUser->isInformationManager() || !$currentUser->institute->hasTool($tool)) {
            return false;
        }

        $instituteTool = InstituteTool::forInstitute($currentUser->institute)->forTool($tool)->first();
        if ($instituteTool?->concept === null) {
            return false;
        }

        return true;
    }

    public function discardConceptForInstitute(User $currentUser, Tool $tool): bool
    {
        return $this->publishConceptForInstitute($currentUser, $tool);
    }

    public function getSupport(User $currentUser, Tool $tool): bool
    {
        $instituteTool = InstituteTool::forInstitute($currentUser->institute)->forTool($tool)->first();

        if (!$currentUser->isTeacher() && !$currentUser->isInformationManager()) {
            return false;
        }

        return in_array($instituteTool->status, [Status::ALLOWED, Status::ALLOWED_UNDER_CONDITIONS]);
    }

    public function submitRequestForChange(User $currentUser, Tool $tool): bool
    {
        if (!$tool->is_published) {
            return false;
        }

        return $currentUser->isInformationManager();
    }

    public function seeAllFields(User $user): bool
    {
        return !$user->isOnlyRole(Role::TEACHER);
    }
}
