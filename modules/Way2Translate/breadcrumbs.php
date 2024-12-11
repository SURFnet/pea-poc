<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Breadcrumbs
|--------------------------------------------------------------------------
| Ensure the Diglactic breadcrumbs package is installed:
| $ composer require diglactic/laravel-breadcrumbs
|
| Register these breadcrumbs in ./routes/breadcrumbs.php like so:
| require base_path('modules/Way2Translate/breadcrumbs.php');
|
*/

if (!class_exists(\Diglactic\Breadcrumbs\Breadcrumbs::class)) {
    return;
}

\Diglactic\Breadcrumbs\Breadcrumbs::for(
    'way2translate.index',
    function ($breadcrumbs): void {
        $breadcrumbs->push(
            trans('Way2Translate::page.manage-translations'),
            route('way2translate.index')
        );
    }
);

\Diglactic\Breadcrumbs\Breadcrumbs::for(
    'way2translate.group.index',
    function ($breadcrumbs, $localeCode, $group = null): void {
        $breadcrumbs->parent('way2translate.index');
        $breadcrumbs->push(
            trans('Way2Translate::page.group-translations'),
            route('way2translate.group.index', [$localeCode, $group])
        );
    }
);

\Diglactic\Breadcrumbs\Breadcrumbs::for(
    'way2translate.missing-translations',
    function ($breadcrumbs): void {
        $breadcrumbs->push(
            trans('Way2Translate::page.missing-translations'),
            route('way2translate.missing-translations')
        );
    }
);
