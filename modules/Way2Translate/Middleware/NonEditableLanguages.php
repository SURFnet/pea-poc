<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Modules\Way2Translate\Models\Locale;
use Modules\Way2Translate\Models\Translation;

class NonEditableLanguages
{
    /**
     * Check for missing translations first, then whether the languages are editable.
     *
     * If languages are not editable, the language management routes should not be available.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Translation::hasDefaultTranslations()) {
            return redirect()->route('way2translate.missing-translations');
        }

        if (!Config::get('way2translate.editable-languages')) {
            return redirect()->route('way2translate.group.index', Locale::getDefaultLanguageCode());
        }

        return $next($request);
    }
}
