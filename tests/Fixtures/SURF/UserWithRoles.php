<?php

declare(strict_types=1);

namespace Tests\Fixtures\SURF;

use SocialiteProviders\Manager\OAuth2\User;

class UserWithRoles extends BaseSURFUser
{
    public function user(): User
    {
        $user = $this->baseUser();

        return $this->mapUserToObject($user);
    }
}
