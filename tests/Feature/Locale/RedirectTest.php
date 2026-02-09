<?php

declare(strict_types=1);

namespace Tests\Feature\Locale;

use App\Enums\Auth\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class RedirectTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        $this->setRoutingLocale();

        parent::setUp();

        $this->user = User::factory()->teacher()->create([
            'name' => 'PAQT ' . Role::TEACHER,
        ]);
    }

    /**
     * @see: https://github.com/mcamara/laravel-localization?tab=readme-ov-file#testing
     */
    private function setRoutingLocale(): void
    {
        putenv(\Mcamara\LaravelLocalization\LaravelLocalization::ENV_ROUTE_KEY);
    }

    /**
     * @test
     *
     * @dataProvider localesDataProvider
     */
    public function the_user_is_redirected_to_the_correct_locale(string $locale): void
    {
        $this
            ->actingAs($this->user)
            ->session(['locale' => $locale])
            ->get('')
            ->assertRedirect($locale);
    }

    public static function localesDataProvider(): array
    {
        return [
            ['en'],
            ['nl'],
        ];
    }
}
