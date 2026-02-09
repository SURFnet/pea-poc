<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Dto\PiwikDataLayerDto;
use App\Enums\Auth\Role;
use App\Helpers\PiwikHelper;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\CreatesApplication;
use Tests\TestCase;

class PiwikHelperTest extends TestCase
{
    use CreatesApplication;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_returns_piwik_datalayer_array_for_authenticated_user(): void
    {
        $institute = new Institute();
        $institute->short_name = 'ABC';

        $user = new User();
        $user->institute = $institute;
        $user->roles = [Role::INFORMATION_MANAGER, Role::CONTENT_MANAGER];

        Config::set('constants.piwik_key', '12345');

        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $expectedArray = [
            'spvainstelling' => 'ABC',
            'spvarol'        => [Role::INFORMATION_MANAGER, Role::CONTENT_MANAGER],
        ];

        $this->partialMock(PiwikDataLayerDto::class, function ($mock) use ($expectedArray, $user): void {
            $mock->shouldReceive('__construct')
                ->with($user->institute->short_name, $user->roles);
            $mock->shouldReceive('jsonSerialize')
                ->andReturn($expectedArray);
        });

        $result = PiwikHelper::getPiwikDatalayer();

        $this->assertIsArray($result);
        $this->assertSame($expectedArray, $result);
    }

    /** @test */
    public function it_returns_piwik_datalayer_array_without_roles(): void
    {
        $institute = new Institute();
        $institute->short_name = 'ABC';

        $user = new User();
        $user->institute = $institute;
        $user->roles = [];

        Config::set('constants.piwik_key', '12345');

        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $expectedArray = [
            'spvainstelling' => 'ABC',
            'spvarol'        => [],
        ];

        $this->partialMock(PiwikDataLayerDto::class, function ($mock) use ($expectedArray, $user): void {
            $mock->shouldReceive('__construct')
                ->with($user->institute->short_name, $user->roles);
            $mock->shouldReceive('jsonSerialize')
                ->andReturn($expectedArray);
        });

        $result = PiwikHelper::getPiwikDatalayer();

        $this->assertIsArray($result);
        $this->assertSame($expectedArray, $result);
    }

    /** @test */
    public function it_returns_an_empty_array_when_the_user_is_not_signed_in(): void
    {
        Config::set('constants.piwik_key', '12345');

        $expectedArray = [];

        $result = PiwikHelper::getPiwikDatalayer();

        $this->assertIsArray($result);
        $this->assertSame($expectedArray, $result);
    }

    /** @test */
    public function it_returns_an_empty_array_when_the_piwik_key_is_empty(): void
    {
        $expectedArray = [];

        $result = PiwikHelper::getPiwikDatalayer();

        $this->assertIsArray($result);
        $this->assertSame($expectedArray, $result);
    }
}
