<?php

declare(strict_types=1);

namespace Tests\Fixtures\SURF;

use Illuminate\Support\Arr;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Contracts\User;
use SocialiteProviders\Manager\OAuth2\User as OAuth2User;

abstract class BaseSURFUser extends AbstractUser
{
    abstract public function user(): User;

    public function stateless(): self
    {
        return $this;
    }

    protected function baseUser(array $overrides = []): array
    {
        return array_merge([
            'id'                      => null,
            'name'                    => 'Devops PAQT',
            'email'                   => null,
            'avatar'                  => null,
            'nickname'                => null,
            'acr'                     => 'urn:oasis:names:tc:SAML:2.0:ac:classes:unspecified',
            'eduperson_affiliation'   => ['employee'],
            'schac_home_organization' => 'eduid.nl',
            'sub'                     => 'asdd3d3qdw3dqd3q3dq3d',
            'uids'                    => ['asdaa33-dcdf-4234-21b5-2dasdasd3211f3feb'],
            'updated_at'              => 1636371429,
            'edumember_is_member_of'  => array_values(config('services.surfconext.roles')),
        ], $overrides);
    }

    protected function mapUserToObject(array $user): OAuth2User
    {
        return (new OAuth2User())->setRaw($user)->map([
            'id'                           => Arr::get($user, 'sub'),
            'name'                         => Arr::get($user, 'name'),
            'nickname'                     => Arr::get($user, 'nickname'),
            'email'                        => Arr::get($user, 'email'),
            'avatar'                       => Arr::get($user, 'picture'),
            'sub'                          => Arr::get($user, 'sub'),
            'preferred_username'           => Arr::get($user, 'preferred_username'),
            'given_name'                   => Arr::get($user, 'given_name'),
            'family_name'                  => Arr::get($user, 'family_name'),
            'schac_home_organization'      => Arr::get($user, 'schac_home_organization'),
            'schac_home_organization_type' => Arr::get($user, 'schac_home_organization_type'),
            'eduperson_affiliation'        => Arr::get($user, 'eduperson_affiliation'),
            'eduperson_scoped_affiliation' => Arr::get($user, 'eduperson_scoped_affiliation'),
            'eduperson_targeted_id'        => Arr::get($user, 'eduperson_targeted_id'),
            'uids'                         => Arr::get($user, 'uids'),
            'schac_personal_unique_code'   => Arr::get($user, 'schac_personal_unique_code'),
            'eduperson_principal_name'     => Arr::get($user, 'eduperson_principal_name'),
            'eduperson_entitlement'        => Arr::get($user, 'eduperson_entitlement'),
        ]);
    }
}
