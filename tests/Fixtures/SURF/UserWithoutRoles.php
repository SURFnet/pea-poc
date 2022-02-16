<?php

declare(strict_types=1);

namespace Tests\Fixtures\SURF;

use SocialiteProviders\Manager\OAuth2\User;

class UserWithoutRoles extends BaseSURFUser
{
    public function user(): User
    {
        $user = $this->baseUser([
            'edumember_is_member_of' => null,
        ]);

        return $this->mapUserToObject($user);
    }
}
