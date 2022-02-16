<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleList extends Command
{
    /** @var string */
    protected $signature = 'schedule:list';

    /** @var string */
    protected $description = 'Show a list of scheduled tasks with detailed information.';

    /** @var Schedule */
    private $schedule;

    public function __construct(Schedule $schedule)
    {
        parent::__construct();

        $this->schedule = $schedule;
    }

    public function handle(): void
    {
        $scheduledTasks = collect($this->schedule->events())
            ->map(function ($event) {
                $expression = CronExpression::factory($event->expression);

                return [
                    'command'        => $event->command,
                    'expression'     => $event->expression,
                    'next-execution' => $expression->getNextRunDate()->format('c'),
                ];
            });

        $this->table(['command', 'cronjob schedule', 'next execution'], $scheduledTasks);
    }
}
