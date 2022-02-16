<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Console\Commands;

use Illuminate\Console\Command;
use Modules\Way2Translate\Generators\JsGenerator;
use Modules\Way2Translate\Models\Translation;

class ExportTranslationsJsCommand extends Command
{
    /** @var string */
    protected $signature = 'w2w:export-translations-js';

    /** @var string */
    protected $description = 'Exports the translations to a javascript file.';

    public function handle(): void
    {
        $fromDatabase = Translation::hasDefaultTranslations();

        if ($fromDatabase) {
            $this->info('Exporting translations from the database.');
        } else {
            $this->info('Exporting translations from the filesystem.');
        }

        $generator = new JsGenerator();
        $generator->generate($fromDatabase);

        $this->info(
            'Export finished. "' . config('way2translate.js-translations.path-relative') . '" has been generated.'
        );
    }
}
