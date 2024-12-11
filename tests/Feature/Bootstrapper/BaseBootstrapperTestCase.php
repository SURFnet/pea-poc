<?php

declare(strict_types=1);

namespace Tests\Feature\Bootstrapper;

use App\Models\Institute;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

abstract class BaseBootstrapperTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Institute::query()->delete();

        $this->setFakeDefaults();
    }

    protected function setFakeDefaults(): void
    {
        Config::set('bootstrap.institutes', [
            [
                'full_name_en' => 'EduID',
                'full_name_nl' => 'EduID',
                'short_name'   => 'EduID',
                'logo'         => 'EduID.png',
                'domain'       => 'eduid.nl',
                'banner'       => 'UT_banner.jpg',
            ],
        ]);
    }
}
