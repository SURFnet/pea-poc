<?php

declare(strict_types=1);

namespace App\Providers;

use Barryvdh\Debugbar\Facade as Debugbar;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        // For more information: https://laravel-news.com/laravel-5-4-key-too-long-error
        Schema::defaultStringLength(255);

        $this->registerBladeComponents();
    }

    public function register(): void
    {
        if ($this->app->environment(config('constants.environment.development'))) {
            $this->app->register(TelescopeServiceProvider::class);
        }

        if ($this->app->environment(config('constants.environment.testing'))) {
            Debugbar::disable();
        }

        Carbon::setLocale(config('app.locale'));
    }

    private function registerBladeComponents(): void
    {
        Blade::aliasComponent('components.attribute-list.wrapper', 'attributelist');
        Blade::aliasComponent('components.attribute-list.item', 'attribute');
    }
}
