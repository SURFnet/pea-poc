<?php

declare(strict_types=1);

namespace App\External\Auth\SURF;

use App\Enums\Auth\Role;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Arr;
use Laravel\Socialite\Contracts\User as SURFUser;

class Manager
{
    public function createOrUpdateUser(SURFUser $externalUser): User
    {
        $user = $this->findUser($externalUser);

        // TODO Refactor after socialiteproviders/surfconext gets new version (beyond 4.1.2)
        $roles = $this->parseSURFRoles(Arr::get($externalUser->user, 'edumember_is_member_of'));

        if (empty($roles)) {
            throw new AuthorizationException();
        }

        if (!$user) {
            $user = new User();
            $user->external_id = $externalUser->getId();
        }

        $user->institute()->associate($this->findInstitute($externalUser->schac_home_organization));
        $user->name = $externalUser->getName();
        $user->roles = $roles;

        $user->save();

        return $user;
    }

    private function findUser(SURFUser $user): ?User
    {
        return User::where('external_id', $user->getId())->first();
    }

    private function findInstitute(string $domain): Institute
    {
        return Institute::where('domain', $domain)->firstOrFail();
    }

    private function parseSURFRoles(?array $roles): array
    {
        if (empty($roles)) {
            return [];
        }

        $translatedRoles = [];
        foreach (Role::toArray() as $role) {
            if (in_array(config('services.surfconext.roles.' . $role), $roles, true)) {
                $translatedRoles[] = $role;
            }
        }

        return $translatedRoles;
    }
}
