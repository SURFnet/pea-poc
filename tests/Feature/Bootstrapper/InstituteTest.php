<?php

declare(strict_types=1);

namespace Tests\Feature\Bootstrapper;

class InstituteTest extends BaseBootstrapperTest
{
    /** @test */
    public function it_works(): void
    {
        $this->artisan('bootstrap:institutes')

            ->expectsOutput('Bootstrapping institutes...')
            ->expectsOutput('Bootstrapping institutes done')
            ->assertExitCode(0);
    }

    /** @test */
    public function the_institutes_are_created(): void
    {
        $this->artisan('bootstrap:institutes');

        $this->assertDatabaseHas('institutes', [
            'domain' => 'eduid.nl',
        ]);
    }
}
