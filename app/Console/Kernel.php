<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /** @var array */
    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule
            ->command('activitylog:clean')
            ->daily()
            ->thenPing(config('heartbeat.activity-log-clean'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
