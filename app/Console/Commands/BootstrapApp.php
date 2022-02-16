<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BootstrapApp extends Command
{
    /** @var string */
    protected $signature = 'bootstrap:application';

    /** @var string */
    protected $description = 'Bootstraps the application.';

    public function handle(): void
    {
        $this->info('Bootstrapping application...');

        $this->call('bootstrap:institutes');

        $this->info('Bootstrapping done');
    }
}
