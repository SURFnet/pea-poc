<?php

declare(strict_types=1);

namespace App\External\Auth\SURF;

use App\Enums\Auth\Role;
use App\Exceptions\SurfConextException;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\User as SURFUser;

class Manager
{
    public function createOrUpdateUser(SURFUser $externalUser): User
    {
        $this->enforceAffiliation($externalUser, ['employee', 'affiliate']);

        $roles = $this->parseSURFRolesOrFail($externalUser);

        $institute = $this->parseInstituteOrFail($externalUser);

        $user = $this->findUser($externalUser);

        if (!$user) {
            $user = new User();
            $user->external_id = $externalUser->getId();
        }

        $user->institute()->associate($institute);
        $user->email = $externalUser->getEmail();
        $user->name = $externalUser->getName();
        $user->roles = $roles;

        $user->save();

        return $user;
    }

    private function findUser(SURFUser $user): ?User
    {
        return User::where('external_id', $user->getId())->first();
    }

    private function enforceAffiliation(SURFUser $externalUser, array $affiliations): void
    {
        foreach ($affiliations as $affiliation) {
            if (in_array($affiliation, $externalUser->eduperson_affiliation ?? [])) {
                return;
            }
        }

        $this->throwSurfConextException(trans('message.error.login.invalid_affiliation'), $externalUser);
    }

    private function parseInstituteOrFail(SURFUser $externalUser): ?Institute
    {
        $institute = Institute::where('domain', $externalUser->schac_home_organization)->first();

        if (!$institute) {
            $this->throwSurfConextException(trans('message.error.login.unknown_institute'), $externalUser);
        }

        return $institute;
    }

    private function parseSURFRolesOrFail(SURFUser $externalUser): array
    {
        // TODO Refactor after socialiteproviders/surfconext finally gets tagged beyond 4.1.2
        $roles = Arr::get($externalUser->user, 'edumember_is_member_of');

        if (!is_array($roles)) {
            $roles = [];
        }

        if (!in_array(config('services.surfconext.roles.' . Role::TEACHER), $roles, true)) {
            $roles[] = config('services.surfconext.roles.teacher');
        }

        $translatedRoles = [];
        foreach (Role::toArray() as $role) {
            if (in_array(config('services.surfconext.roles.' . $role), $roles, true)) {
                $translatedRoles[] = $role;
            }
        }

        if (empty($translatedRoles)) {
            $this->throwSurfConextException(trans('message.error.login.invalid_roles'), $externalUser);
        }

        return $translatedRoles;
    }

    private function throwSurfConextException(string $message, SURFUser $externalUser): void
    {
        Log::error($message, [
            'externalUser.user'       => $externalUser->user,
            'externalUser.attributes' => $externalUser->attributes,
        ]);

        throw new SurfConextException($message);
    }
}
