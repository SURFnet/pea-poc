<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Auth\Role;
use App\Enums\InstituteTool\Status;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Models\User;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
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

    public function view(User $currentUser, Tool $tool): bool
    {
        // Currently only viewing Custom Tools is supported
        if (!$tool->isCustomTool()) {
            return false;
        }

        if ($currentUser->isContentManager()) {
            return true;
        }

        return $currentUser->isInformationManager()
            && $currentUser->institute->hasCustomTool($tool);
    }

    public function create(User $currentUser): bool
    {
        return $currentUser->isContentManager();
    }

    public function createCustomTool(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }

    public function update(User $currentUser, Tool $tool): bool
    {
        if ($tool->isCustomTool()) {
            return $currentUser->isInformationManager()
                && $currentUser->institute->hasCustomTool($tool);
        }

        return $currentUser->isContentManager();
    }

    public function delete(User $currentUser, Tool $tool): bool
    {
        if (!$tool->isCustomTool()) {
            return false;
        }

        return $currentUser->isInformationManager();
    }

    public function publish(User $currentUser, Tool $tool): bool
    {
        if ($tool->is_published) {
            return false;
        }

        if ($tool->isCustomTool()) {
            return $currentUser->isInformationManager()
                && $currentUser->institute->hasCustomTool($tool);
        }

        return $currentUser->isContentManager();
    }

    public function publishConcept(User $currentUser, Tool $tool): bool
    {
        if ($tool->concept === null) {
            return false;
        }

        return $this->update($currentUser, $tool);
    }

    public function discardConcept(User $currentUser, Tool $tool): bool
    {
        if ($tool->isCustomTool()) {
            return false;
        }

        return $this->publishConcept($currentUser, $tool);
    }

    public function manageOur(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }

    public function addToInstitute(User $currentUser, Tool $tool): bool
    {
        if ($tool->isCustomTool()) {
            return false;
        }

        return $tool->is_published
            && $currentUser->isInformationManager()
            && !$currentUser->institute->hasTool($tool);
    }

    public function updateForInstitute(User $currentUser, Tool $tool): bool
    {
        if ($tool->isCustomTool()) {
            return false;
        }

        return $tool->is_published
            && $currentUser->isInformationManager()
            && $currentUser->institute->hasTool($tool);
    }

    public function publishForInstitute(User $currentUser, Tool $tool): bool
    {
        if (!$tool->is_published || !$currentUser->isInformationManager()) {
            return false;
        }

        if ($tool->isCustomTool()) {
            return $currentUser->institute->hasCustomTool($tool);
        }

        return $currentUser->institute->hasTool($tool);
    }

    public function publishConceptForInstitute(User $currentUser, Tool $tool, InstituteTool $instituteTool = null): bool
    {
        if (!$currentUser->isInformationManager()) {
            return false;
        }

        if ($instituteTool === null) {
            $instituteTool = InstituteTool::forInstitute($currentUser->institute)
                ->forTool($tool)
                ->first();
        }

        if ($instituteTool->concept === null) {
            return false;
        }

        if ($tool->isCustomTool()) {
            return $currentUser->institute->hasCustomTool($tool);
        }

        return $currentUser->institute->hasTool($tool);
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
        if (!$tool->is_published || $tool->isCustomTool()) {
            return false;
        }

        return $currentUser->isInformationManager();
    }

    public function convert(User $currentUser, Tool $tool): bool
    {
        if (!$tool->isCustomTool()) {
            return false;
        }

        return $currentUser->isContentManager();
    }

    public function seeAllFields(User $user): bool
    {
        return !$user->isOnlyRole(Role::TEACHER);
    }
}
