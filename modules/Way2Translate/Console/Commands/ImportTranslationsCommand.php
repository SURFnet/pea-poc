<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Modules\Way2Translate\Models\Language;
use Modules\Way2Translate\Models\Locale;
use Modules\Way2Translate\Models\Translation;

/**
 * Import the translations from the filesystem into the database.
 *
 * Heavily inspired by:
 * https://github.com/hpolthof/laravel-translations-db/blob/master/src/Console/Commands/FetchCommand.php
 */
class ImportTranslationsCommand extends Command
{
    /** @var string */
    protected $signature = 'w2w:import-translations';

    /** @var string */
    protected $description = 'Import the translations from the filesystem into the database.';

    /** @var string */
    private $importLocale;

    /** @var array */
    private $langPaths;

    /** @var array */
    private $allTranslationLocales;

    public function handle(): void
    {
        $this->importLocale = Config::get('way2translate.import-locale');
        $this->langPaths = $this->getLanguageFolders();

        $languages = Language::pluck('locale')->toArray();
        if (!in_array($this->importLocale, $languages)) {
            $languages[] = $this->importLocale;
        }

        $this->allTranslationLocales = $languages;

        $this->setInitialInLatestImportState();

        foreach ($this->langPaths as $namespace => $langPath) {
            $this->storeDirectory($namespace, $langPath . '/' . $this->importLocale);
        }

        $this->clearNotInLatestImport();

        Locale::clearCache();

        $this->info('Import done');
    }

    private function setInitialInLatestImportState(): void
    {
        Translation::where('id', '>', '0')->update(['in_latest_import' => false]);
    }

    private function clearNotInLatestImport(): void
    {
        Translation::where('in_latest_import', false)->delete();
    }

    private function storeDirectory(string $namespace, string $path): void
    {
        $groups = $this->getCleanedGroups($path);
        foreach ($groups as $group) {
            $this->storeGroup($namespace, $path, $group);
        }
    }

    private function storeGroup(string $namespace, string $path, string $group): void
    {
        $filePath = $path . '/' . $group . '.php';

        $keys = require $filePath;

        $keys = $this->flattenArray($keys);
        foreach ($keys as $name => $value) {
            $this->storeTranslation($namespace, $group, $name, $value);
        }

        Translation::clearGroupCache($this->importLocale, $namespace, $group);

        $this->info('Processed ' . $filePath);
    }

    private function storeTranslation(string $namespace, string $group, string $name, string $value): void
    {
        // ensure this new translation is added to all languages
        foreach ($this->allTranslationLocales as $locale) {
            if (!Translation::missingTranslation($locale, $namespace, $group, $name)) {
                continue;
            }

            $translation = new Translation();
            $translation->namespace = $namespace;
            $translation->locale = $locale;
            $translation->group = $group;
            $translation->name = $name;

            if ($locale != $this->importLocale) {
                $translation->value = '';
            } else {
                $translation->value = $value;
            }

            $translation->in_latest_import = true;
            $translation->save();

            $this->info('Imported "' . $namespace . '::' . $group . '.' . $name . '" translation.');
        }

        // mark the translation as imported
        Translation::where('namespace', $namespace)
            ->where('group', $group)
            ->where('name', $name)
            ->update(['in_latest_import' => true]);
    }

    private function getLanguageFolders(): array
    {
        $folders = [];

        foreach (Config::get('way2translate.language-folders') as $namespace => $folder) {
            if (!File::isDirectory($folder)) {
                $this->info('Skipping "' . $folder . ' - the folder does not exist.');

                continue;
            }

            $folders[$namespace] = $folder;
        }

        return $folders;
    }

    protected function flattenArray(array $keys, string $prefix = ''): array
    {
        $result = [];
        foreach ($keys as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $prefix . $key . '.'));
            } else {
                $result[$prefix . $key] = $value;
            }
        }

        return $result;
    }

    protected function getCleanedGroups(string $path): array
    {
        $groups = File::files($path);
        foreach ($groups as &$group) {
            $group = $this->cleanGroup((string) $group, $path);
        }

        return $groups;
    }

    protected function cleanGroup(string $group, string $path): ?string
    {
        $clean = str_replace($path . '/', '', $group);
        if (preg_match('/^(.*?)\.php$/sm', $clean, $match)) {
            return $match[1];
        }

        return null;
    }
}
