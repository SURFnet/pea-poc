<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\ViewComposers\AppComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', AppComposer::class);
    }

    public function register(): void
    {
        $this->app->singleton(AppComposer::class);
    }
}
