<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Modules\Way2Translate\Events\ModifiedTranslations;
use Modules\Way2Translate\Models\Language;
use Modules\Way2Translate\Models\Locale;
use Modules\Way2Translate\Models\Translation;

class TranslationsController extends BaseController
{
    /** @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse */
    public function index()
    {
        $this->authorize('viewAny', Translation::class);

        if (!Translation::hasDefaultTranslations()) {
            return redirect()->route('way2translate.missing-translations');
        }

        return view('way2translate::index', [
            'translatedLocales'          => Locale::getTranslated(),
            'nonTranslatedLocalesTarget' => Locale::getNonTranslated()->pluck('name', 'code'),
        ]);
    }

    public function missingTranslations(): View
    {
        $this->authorize('viewAny', Translation::class);

        return view('way2translate::missing-translations');
    }

    public function activate(string $localeCode): RedirectResponse
    {
        $this->authorize('activateLocale', Translation::class);

        Language::activate($localeCode);

        Locale::clearCache();

        return redirect()->route('way2translate.index');
    }

    public function deactivate(string $localeCode): RedirectResponse
    {
        $this->authorize('deactivateLocale', Translation::class);

        if ($localeCode != Config::get('way2translate.default-locale')) {
            Language::deactivate($localeCode);

            Locale::clearCache();
        }

        return redirect()->route('way2translate.index');
    }

    public function add(Request $request): RedirectResponse
    {
        $this->authorize('create', Translation::class);

        $nonTranslatedLocales = Locale::getNonTranslated()->pluck('code')->toArray();

        $this->validate($request, [
            'locale_target' => 'required|in:' . implode(',', $nonTranslatedLocales),
        ]);

        $localeTarget = $request->get('locale_target');

        $translations = Translation::where('locale', Config::get('way2translate.default-locale'))->get();
        if (empty($translations)) {
            return redirect()->route('way2translate.index');
        }

        foreach ($translations as $translation) {
            $newTranslation = $translation->replicate();
            $newTranslation->locale = $localeTarget;
            $newTranslation->value = '';
            $newTranslation->save();
        }

        Language::addByLocale($localeTarget);

        Locale::clearCache();

        return redirect()->route('way2translate.index');
    }

    /** @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse */
    public function group(string $localeCode, string $group = '', string $namespace = '')
    {
        $this->authorize('updateGroup', Translation::class);

        $locale = Locale::getByCode($localeCode);
        if (empty($locale)) {
            abort(404);
        }

        if (empty($group) || empty($namespace)) {
            $translation = Translation::where('locale', $localeCode)->first();

            return redirect()->route('way2translate.group.index', [
                $localeCode,
                $translation->group,
                $translation->namespace,
            ]);
        }

        return view('way2translate::group', [
            'locale'            => $locale,
            'group'             => $group,
            'namespace'         => $namespace,
            'groupsByNamespace' => Translation::getGroupsByNamespace(),
            'translations'      => Translation::getGroupTranslations($localeCode, $group, $namespace),
        ]);
    }

    public function groupSave(Request $request, string $localeCode, string $group, string $namespace): RedirectResponse
    {
        $this->authorize('updateGroup', Translation::class);

        if (!empty($request->get('value'))) {
            foreach ($request->get('value') as $id => $value) {
                Translation::where('id', $id)->update(['value' => $value]);
            }
        }

        Translation::clearGroupCache($localeCode, $group, $namespace);

        Event::dispatch(new ModifiedTranslations($localeCode, $group, $namespace));

        Locale::clearCache();

        if (config('way2translate.js-translations.auto-generate')) {
            Artisan::call('w2w:export-translations-js');
        }

        return redirect()->route('way2translate.group.index', [
            $localeCode,
            $group,
            $namespace,
        ]);
    }
}
