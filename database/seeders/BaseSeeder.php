<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;

abstract class BaseSeeder extends Seeder
{
    private ProgressBar $progressBar;

    abstract protected function handle(): void;

    abstract protected function getProgressBarCount(): int;

    public function run(): void
    {
        $this->progressBar = $this->command->getOutput()->createProgressBar($this->getProgressBarCount());

        $this->handle();

        $this->progressBar->finish();

        $this->command->info('');
    }

    protected function advanceProgressBar(): void
    {
        $this->progressBar->advance();
    }
}
