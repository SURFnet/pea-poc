<?php

declare(strict_types=1);

namespace App\External\Auth\SURF;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\SURFconext\SURFconextExtendSocialite;

class SURFServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Event::listen(SocialiteWasCalled::class, SURFconextExtendSocialite::class);

        $this->loadRoutesFrom(__DIR__ . '/Interfaces/Http/Routes/web.php');
    }
}
