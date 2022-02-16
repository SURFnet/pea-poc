<?php

declare(strict_types=1);

namespace Tests\Feature\Bootstrapper;

class ApplicationTest extends BaseBootstrapperTest
{
    /** @test */
    public function it_works(): void
    {
        $this->artisan('bootstrap:application')

            ->expectsOutput('Bootstrapping application...')
            ->expectsOutput('Bootstrapping done')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_bootstraps_institutes(): void
    {
        $this->artisan('bootstrap:application')

            ->expectsOutput('Bootstrapping institutes...')
            ->expectsOutput('Bootstrapping institutes done');
    }
}
