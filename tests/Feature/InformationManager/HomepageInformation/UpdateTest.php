<?php

declare(strict_types=1);

namespace Feature\InformationManager\HomepageInformation;

use App\Models\Institute;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function can_be_updated(): void
    {
        $data = [
            'homepage_title_en' => '::edited_homepage_title::',
            'homepage_body_en'  => '::edited_homepage_body::',
            'homepage_title_nl' => '::edited_homepage_title::',
            'homepage_body_nl'  => '::edited_homepage_body::',
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(
                route(
                    'information-manager.homepage-information.update',
                ),
                $data
            )
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(
                route(
                    'information-manager.homepage-information.edit',
                )
            );

        $this->assertDatabaseHas(
            'institutes',
            array_merge($data, [
                'id' => $this->informationManager->institute->id,
            ])
        );
    }

    /** @test */
    public function a_guest_cannot_update_a_homepage(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $data = [
            'homepage_title_en' => '::edited_homepage_title::',
            'homepage_body_en'  => '::edited_homepage_body::',
            'homepage_title_nl' => '::edited_homepage_title::',
            'homepage_body_nl'  => '::edited_homepage_body::',
        ];

        $institute = Institute::factory()->create();

        $this
            ->put(
                route(
                    'information-manager.homepage-information.update',
                ),
                $data
            )
            ->assertForbidden();
    }

    /** @test */
    public function fields_can_be_empty(): void
    {
        $data = [
            'homepage_title_en' => null,
            'homepage_body_en'  => null,
            'homepage_title_nl' => null,
            'homepage_body_nl'  => null,
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(
                route(
                    'information-manager.homepage-information.update',
                ),
                $data
            )
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(
                route(
                    'information-manager.homepage-information.edit',
                )
            );

        $this->assertDatabaseHas(
            'institutes',
            array_merge($data, [
                'id' => $this->informationManager->institute->id,
            ])
        );
    }
}
