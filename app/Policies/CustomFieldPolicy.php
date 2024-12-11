<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CustomField;
use App\Models\User;

class CustomFieldPolicy
{
    public function viewAll(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }

    public function create(User $currentUser): bool
    {
        return $currentUser->isInformationManager();
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(User $currentUser, CustomField $customField): bool
    {
        return $currentUser->isInformationManager() && $customField->institute()->is($currentUser->institute);
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function delete(User $currentUser, CustomField $customField): bool
    {
        return $this->update($currentUser, $customField);
    }
}
