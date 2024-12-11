<?php

declare(strict_types=1);

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Modules\Way2Translate\Controllers\TranslationsController;
use Modules\Way2Translate\Generators\JsGenerator;
use Modules\Way2Translate\Helpers\Route as RouteHelper;

Route::group(
    [
        'middleware' => ['web', 'auth'],
        'prefix'     => 'way2translate',
        'as'         => 'way2translate.',
    ],
    function (): void {
        Route::group(
            [
                'middleware' => ['non-editable-languages'],
            ],
            function (): void {
                Route::get('', [TranslationsController::class, 'index'])
                    ->name('index');

                Route::get('/activate/{localeCode}', [TranslationsController::class, 'activate'])
                    ->name('activate');

                Route::get('/deactivate/{localeCode}', [TranslationsController::class, 'deactivate'])
                    ->name('deactivate');

                Route::post('/add', [TranslationsController::class, 'add'])
                    ->name('add');
            }
        );

        Route::get('/missing-translations', [TranslationsController::class, 'missingTranslations'])
            ->name('missing-translations');

        Route::group(
            [
                'prefix' => 'group/{localeCode}',
                'as'     => 'group.',
            ],
            function (): void {
                Route::get('/{group?}/{namespace?}', [TranslationsController::class, 'group'])
                    ->name('index');

                Route::post('/{group}/{namespace}', [TranslationsController::class, 'groupSave'])
                    ->name('save');
            }
        );
    }
);
Route::group(
    [
        'prefix' => 'way2translate',
        'as'     => 'way2translate.',
    ],
    function (): void {
        Route::get('lang', function () {
            if (App::environment('local')) {
                $lang = JsGenerator::fromFileOrDatabase();
            } else {
                $lang = Cache::remember(
                    RouteHelper::getCacheKey(),
                    60000,
                    function () {
                        return (new JsGenerator())->content();
                    }
                );
            }

            $response = response($lang, 200);
            $response->header('Content-Type', 'application/javascript');

            return $response;
        })
            ->name('lang');
    }
);
