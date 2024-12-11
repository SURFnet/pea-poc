<?php

declare(strict_types=1);

namespace Tests\Unit\Policy;

use Modules\Way2Translate\Models\Translation;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    /** @test */
    public function can_be_managed_on_production(): void
    {
        $this->app['env'] = 'production';

        $this->assertTrue($this->admin->can('viewAny', Translation::class));
        $this->assertTrue($this->admin->can('create', Translation::class));
        $this->assertTrue($this->admin->can('view', new Translation()));
        $this->assertTrue($this->admin->can('update', new Translation()));
        $this->assertTrue($this->admin->can('delete', new Translation()));
    }

    /** @test */
    public function can_be_managed_on_accept(): void
    {
        $this->app['env'] = 'accept';

        $this->assertTrue($this->admin->can('viewAny', Translation::class));
        $this->assertTrue($this->admin->can('create', Translation::class));
        $this->assertTrue($this->admin->can('view', new Translation()));
        $this->assertTrue($this->admin->can('update', new Translation()));
        $this->assertTrue($this->admin->can('delete', new Translation()));
    }

    /** @test */
    public function can_be_managed_on_stage(): void
    {
        $this->app['env'] = 'stage';

        $this->assertTrue($this->admin->can('viewAny', Translation::class));
        $this->assertTrue($this->admin->can('create', Translation::class));
        $this->assertTrue($this->admin->can('view', new Translation()));
        $this->assertTrue($this->admin->can('update', new Translation()));
        $this->assertTrue($this->admin->can('delete', new Translation()));
    }

    /** @test */
    public function can_be_managed_on_local(): void
    {
        $this->app['env'] = 'local';

        $this->assertTrue($this->admin->can('viewAny', Translation::class));
        $this->assertTrue($this->admin->can('create', Translation::class));
        $this->assertTrue($this->admin->can('view', new Translation()));
        $this->assertTrue($this->admin->can('update', new Translation()));
        $this->assertTrue($this->admin->can('delete', new Translation()));
    }
}
