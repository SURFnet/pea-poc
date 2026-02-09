<?php

declare(strict_types=1);

use App\Enums\Auth\Role;

return [
    'login'      => 'Login',
    'test-users' => 'Test Users',

    'roles' => [
        'admin'                   => 'Admin',
        Role::CONTENT_MANAGER     => 'Content Manager',
        Role::INFORMATION_MANAGER => 'Information Manager',
        Role::TEACHER             => 'Teacher',
    ],
];
