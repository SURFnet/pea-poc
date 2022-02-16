<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Loaders;

use Illuminate\Support\Facades\Log;
use Illuminate\Translation\FileLoader;
use Modules\Way2Translate\Models\Locale;
use Modules\Way2Translate\Models\Translation;
use Throwable;

class TranslationLoader extends FileLoader
{
    /**
     * Load the translations for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     */
    public function load($locale, $group, $namespace = null): array
    {
        if ($this->hasDatabaseTranslations()) {
            return $this->loadFromDatabase($locale, $group, $namespace);
        }

        return $this->loadFromFiles($locale, $group, $namespace);
    }

    private function loadFromFiles(string $locale, string $group, string $namespace): array
    {
        return parent::load($locale, $group, $namespace);
    }

    private function loadFromDatabase(string $locale, string $group, string $namespace): array
    {
        return Translation::getGroupTranslationsForLoader($locale, $group, $namespace);
    }

    /**
     * There are valid reasons why an exception could be thrown.
     *
     * - artisan:discover is being executed where a service provider is using a translation.
     * - migrations have not yet been executed (for example during a CI workflow).
     * - the database is not yet set up (for example during a CI workflow).
     */
    private function hasDatabaseTranslations(): bool
    {
        try {
            $translatedLocales = Locale::getTranslated();

            return $translatedLocales->isNotEmpty();
        } catch (Throwable $throwable) {
            Log::info('Way2Translate: no database translations could be loaded. ' . $throwable->getMessage());

            return false;
        }
    }
}
