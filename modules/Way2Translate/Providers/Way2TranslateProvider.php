<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Providers;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider;
use Modules\Way2Translate\Console\Commands\ExportTranslationsJsCommand;
use Modules\Way2Translate\Console\Commands\ImportTranslationsCommand;
use Modules\Way2Translate\Middleware\NonEditableLanguages;
use Modules\Way2Translate\Services\CacheService;

class Way2TranslateProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerProviders();

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/way2translate.php',
            'way2translate'
        );

        if (!$this->app->runningInConsole()) {
            $this->loadLocales();
        }
    }

    private function registerProviders(): void
    {
        // Third party Service Providers...
        $this->app->register(LaravelLocalizationServiceProvider::class);

        // Way2Translate Service Providers...
        $this->app->register(TranslationServiceProvider::class);
    }

    /**
     * Place all the available locales in a new config entry and overwrite the available locales
     * with our activated locales.
     */
    private function loadLocales(): void
    {
        // ensure our table is present, otherwise, fall back to the default functionality
        if (!Schema::hasTable('way2translate_languages')) {
            return;
        }

        $allLocales = config('laravellocalization.supportedLocales');
        config(['way2translate.locales' => $allLocales]);

        $availableLocales = [];

        $dbActivatedLocales =
            DB::table('way2translate_languages')
                ->whereNotNull('activated_at')
                ->select('locale')
                ->get();

        if (!empty($dbActivatedLocales)) {
            foreach ($dbActivatedLocales as $activatedLocale) {
                if (!isset($allLocales[$activatedLocale->locale])) {
                    continue;
                }

                $availableLocales[$activatedLocale->locale] = $allLocales[$activatedLocale->locale];
            }
        }

        // we must have the active locale
        $defaultLocale = config('way2translate.default-locale');
        if (!isset($availableLocales[$defaultLocale])) {
            $availableLocales[$defaultLocale] = $allLocales[$defaultLocale];
        }

        config(['app.locale' => $defaultLocale]);

        config(['laravellocalization.supportedLocales' => $availableLocales]);
    }

    public function boot(Router $router, CacheService $cacheService): void
    {
        if (! App::routesAreCached()) {
            require __DIR__ . '/../routes.php';
        }

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'Way2Translate');

        $this->commands([
            ImportTranslationsCommand::class,
            ExportTranslationsJsCommand::class,
        ]);

        $this->loadViewsFrom(__DIR__ . '/../Views', 'way2translate');

        $this->registerBreadcrumbs();

        $router->aliasMiddleware('non-editable-languages', NonEditableLanguages::class);

        View::composer('way2translate::*', function ($view): void {
            $themesPath = 'way2translate::themes';
            $themeName = config('way2translate.theme.name');

            $view->with('themeName', $themeName);
            $view->with('themesPath', $themesPath);
            $view->with('themePath', $themesPath . '.' . $themeName);
            $view->with('themeComponentPath', $themesPath . '.' . $themeName . '.components');
        });

        if (!$this->app->runningInConsole()) {
            $cacheService->enforceCacheIntegrity();
        }
    }

    private function registerBreadcrumbs(): void
    {
        $registerBreadcrumbs = config('way2translate.register-breadcrumbs');
        $languagesAreEditable = config('way2translate.editable-languages');
        $breadcrumbsExist = class_exists('Breadcrumbs');

        if (!$registerBreadcrumbs || !$languagesAreEditable || !$breadcrumbsExist) {
            return;
        }

        Breadcrumbs::register('way2translate.index', function ($breadcrumbs): void {
            $breadcrumbs->push(
                trans('Way2Translate::page.manage-translations'),
                route('way2translate.index')
            );
        });

        Breadcrumbs::register('way2translate.group.index', function ($breadcrumbs, $localeCode, $group = null): void {
            $breadcrumbs->parent('way2translate.index');
            $breadcrumbs->push(
                trans('Way2Translate::page.group-translations'),
                route('way2translate.group.index', [$localeCode, $group])
            );
        });
    }
}
