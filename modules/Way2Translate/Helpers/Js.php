<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Helpers;

use Illuminate\Support\Facades\App;

class Js
{
    public static function getLangUrl(): string
    {
        $filePath = config('way2translate.js-translations.path-absolute');
        $fileRelative = config('way2translate.js-translations.path-relative');

        $langFileModified = '';
        if (App::get('files')->exists($filePath)) {
            $langFileModified = App::get('files')->lastModified($filePath);
        }

        $assetUrl = asset($fileRelative) . '?t=' . $langFileModified;

        return $assetUrl;
    }
}
