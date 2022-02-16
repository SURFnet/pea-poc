<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Generators;

use Exception;
use Illuminate\Filesystem\Filesystem as File;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use JShrink\Minifier;
use Modules\Way2Translate\Models\Translation;

class JsGenerator
{
    protected File $file;

    public function __construct()
    {
        $this->file = App::get('files');
    }

    public function generate(bool $fromDatabase = true): int
    {
        if ($fromDatabase) {
            $messages = $this->getMessagesFromDatabase();
        } else {
            $messages = $this->getMessagesFromFilesystem();
        }

        $target = config('way2translate.js-translations.path-absolute');
        $this->prepareTarget($target);

        $template = $this->file->get(__DIR__ . '/Templates/langjs-with-messages.js');

        $langjs = '';
        if (config('way2translate.js-translations.include-lang-js')) {
            $langjs = $this->file->get(__DIR__ . '/Lib/lang.min.js');
        }

        $template = str_replace('\'{ langjs }\';', $langjs, $template);
        $template = str_replace('\'{ messages }\'', json_encode($messages), $template);

        if (config('way2translate.js-translations.minify-js')) {
            $template = Minifier::minify($template);
        }

        return $this->file->put($target, $template);
    }

    protected function getMessagesFromDatabase(): array
    {
        $messages = [];
        $translations = Translation::all();
        $locales = $translations->unique('locale')->pluck('locale');

        foreach (Translation::getGroupsByNamespace() as $namespace => $groups) {
            foreach ($groups as $group) {
                foreach ($locales as $locale) {
                    $groupName = $group->group;

                    $groupTranslations = $translations
                        ->where('namespace', $namespace)
                        ->where('locale', $locale)
                        ->where('group', $groupName);

                    $groupMessages = [];

                    $keyTranslations = $groupTranslations->pluck('value', 'name')->toArray();
                    foreach ($keyTranslations as $key => $value) {
                        Arr::set($groupMessages, $key, $value);
                    }

                    $messages = $this->addTranslations($messages, $locale, $namespace, $groupName, $groupMessages);
                }
            }
        }

        $this->sortMessages($messages);

        return $messages;
    }

    /** @throws Exception */
    protected function getMessagesFromFilesystem(): array
    {
        $messages = [];
        $locale = Config::get('way2translate.import-locale');
        $folders = Config::get('way2translate.language-folders');

        foreach ($folders as $namespace => $folder) {
            $path = $folder . DIRECTORY_SEPARATOR . $locale;
            if (!$this->file->exists($path)) {
                throw new Exception($path . 'does not exists!');
            }

            foreach ($this->file->allFiles($path) as $file) {
                $pathName = $file->getRelativePathName();
                if ($this->file->extension($pathName) !== 'php') {
                    continue;
                }

                $groupName = $this->getKeyFromPathName($pathName);
                $groupMessages = include $path . DIRECTORY_SEPARATOR . $pathName;

                $messages = $this->addTranslations($messages, $locale, $namespace, $groupName, $groupMessages);
            }
        }

        $this->sortMessages($messages);

        return $messages;
    }

    /** @throws Exception */
    private function addTranslations(
        array $messages,
        string $locale,
        string $namespace,
        string $groupName,
        array $groupMessages
    ): array {
        if ($namespace == '*') {
            $messages[$locale . '.' . $groupName] = $groupMessages;

            return $messages;
        }

        $namespaceParts = explode('.', $namespace);

        if (count($namespaceParts) > 2) {
            throw new Exception('The namespace "' . $namespace . '" is too deep and therefore unsupported.');
        }

        if (count($namespaceParts) == 1) {
            $messages[$locale . '.' . $namespace . '::' . $groupName] = $groupMessages;

            return $messages;
        }

        $firstKey = $locale . '.' . $namespaceParts[0];
        $secondKey = $namespaceParts[1] . '::' . $groupName;

        $messages[$firstKey][$secondKey] = $groupMessages;

        return $messages;
    }

    protected function getKeyFromPathName(string $pathName): string
    {
        $key = substr($pathName, 0, -4);
        $key = str_replace('\\', '.', $key);
        $key = str_replace('/', '.', $key);

        if (Str::startsWith($key, 'vendor')) {
            $key = $this->getVendorKey($key);
        }

        return $key;
    }

    protected function prepareTarget(string $target): void
    {
        $dirname = dirname($target);

        if (!$this->file->exists($dirname)) {
            $this->file->makeDirectory($dirname, 0755, true);
        }
    }

    protected function sortMessages(array &$messages): void
    {
        if (!is_array($messages)) {
            return;
        }

        ksort($messages);

        foreach ($messages as &$value) {
            if (!is_array($value)) {
                return;
            }

            $this->sortMessages($value);
        }
    }

    private function getVendorKey(string $key): string
    {
        $keyParts = explode('.', $key, 4);
        unset($keyParts[0]);

        return $keyParts[2] . '.' . $keyParts[1] . '::' . $keyParts[3];
    }
}
