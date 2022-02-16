<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Providers;

use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;
use Modules\Way2Translate\Loaders\TranslationLoader;

class TranslationServiceProvider extends ServiceProvider
{
    protected function registerLoader(): void
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });
    }
}
