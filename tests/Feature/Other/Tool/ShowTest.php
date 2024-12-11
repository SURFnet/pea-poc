<?php

declare(strict_types=1);

namespace Tests\Feature\Other\Tool;

use App\Enums\Auth\Role;
use App\Enums\InstituteTool\Status;
use App\Models\Experience;
use App\Models\Institute;
use App\Models\Tool;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Show')
            );
    }

    /** @test */
    public function it_contains_the_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('other/tool/Show')
                    ->where('tool.id', $tool->id)
            );
    }

    /** @test */
    public function the_information_manager_has_permission_to_submit_a_request_for_change(): void
    {
        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('other.tool.show', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('tool.permissions.submit_request_for_change', true)
            );
    }

    /** @test */
    public function a_user_with_only_teacher_role_does_not_have_permission_to_see_all_fields(): void
    {
        $tool = Tool::factory()->published()->create();

        $user = User::factory()
            ->withRoles([Role::TEACHER])
            ->create();

        $this
            ->actingAs($user)
            ->get(route('other.tool.show', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('tool.permissions.see_all_fields', false)
            );
    }

    /**
     * @dataProvider nonTeacherRolesDataProvider
     *
     * @test
     */
    public function a_user_with_only_non_teacher_role_has_permission_to_see_all_fields(string $role): void
    {
        $tool = Tool::factory()->published()->create();

        $user = User::factory()
            ->withRoles([$role])
            ->create();

        $this
            ->actingAs($user)
            ->get(route('other.tool.show', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('tool.permissions.see_all_fields', true)
            );
    }

    /**
     * @dataProvider nonTeacherRolesDataProvider
     *
     * @test
     */
    public function a_user_with_teacher_role_and_other_role_has_permission_to_see_all_fields(string $role): void
    {
        $tool = Tool::factory()->published()->create();

        $user = User::factory()
            ->withRoles([Role::TEACHER, $role])
            ->create();

        $this
            ->actingAs($user)
            ->get(route('other.tool.show', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('tool.permissions.see_all_fields', true)
            );
    }

    /** @test */
    public function it_knows_which_institutes_use_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $institutes = [
            ['full_name_en' => 'Institute 001', 'short_name' => 'I-1'],
            ['full_name_en' => 'Institute 002', 'short_name' => 'I-2'],
        ];

        $tool->institutes()->attach(
            Institute::factory()->sequence(...$institutes)->count(2)->create(),
            ['published_at' => now(), 'status' => Status::ALLOWED]
        );

        $this
            ->actingAs($this->informationManager)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                        ->component('other/tool/Show')
                        ->where('tool.id', $tool->id)
                        ->has('institutes', 2)
                        ->where('institutes.0.full_name', $institutes[0]['full_name_en'])
                        ->where('institutes.1.full_name', $institutes[1]['full_name_en'])
            );
    }

    /** @test */
    public function it_knows_the_total_experience_amount_for_a_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        Experience::factory(3)->for($tool)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('other/tool/Show')
                    ->where('tool.total_experiences', 3)
            );
    }

    /** @test */
    public function it_contains_the_back_url_to_other_tools_index(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('other/tool/Show')
                    ->where('backUrl', route('tool.index'))
            );
    }

    /** @test */
    public function it_contains_the_experiences_in_latest_order(): void
    {
        $tool = Tool::factory()->published(true)->create();

        Experience::factory()->sequence(
            [
                'title'      => '::experience-1::',
                'created_at' => Carbon::now()->subWeek(),
            ],
            [
                'title'      => '::experience-2::',
                'created_at' => Carbon::now()->yesterday(),
            ],
            [
                'title'      => '::experience-3::',
                'created_at' => Carbon::now(),
            ],
        )->count(3)->for($tool)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Show')
                    ->where('experiences.0.title', '::experience-3::')
                    ->where('experiences.1.title', '::experience-2::')
                    ->where('experiences.2.title', '::experience-1::')
            );
    }

    /** @test */
    public function experience_has_user_when_created_by_acting_user(): void
    {
        $tool = Tool::factory()->published(true)->create();

        Experience::factory()
            ->for($tool)
            ->for($this->informationManager)
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('experiences.0.user.id', $this->informationManager->id)
            );
    }

    /** @test */
    public function experience_has_user_when_created_by_other_user_from_same_institute_as_acting_user(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $otherUserFromInstitute = User::factory()
            ->for($this->informationManager->institute)
            ->create();

        Experience::factory()
            ->for($tool)
            ->for($otherUserFromInstitute)
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('experiences.0.user.id', $otherUserFromInstitute->id)
            );
    }

    /** @test */
    public function experience_has_no_user_when_created_by_user_from_other_institute_than_acting_user(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $userFromOtherInstitute = User::factory()->create();

        Experience::factory()
            ->for($tool)
            ->for($userFromOtherInstitute)
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('experiences.0.user', null)
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('other.tool.show', $tool));
    }

    /** @test */
    public function the_fields_are_translated(): void
    {
        $this->app->setLocale('nl');

        $tool = Tool::factory()->published(true)->create([
            'addons_en'                    => 'addons_en',
            'addons_nl'                    => 'addons_nl',
            'system_requirements_en'       => 'system_requirements_en',
            'system_requirements_nl'       => 'system_requirements_nl',
            'instructions_manual_1_url_en' => 'https://instructions_manual_1_url_en.nl',
            'instructions_manual_1_url_nl' => 'https://instructions_manual_1_url_nl.nl',
            'instructions_manual_2_url_en' => 'https://instructions_manual_2_url_en.nl',
            'instructions_manual_2_url_nl' => 'https://instructions_manual_2_url_nl.nl',
            'instructions_manual_3_url_en' => 'https://instructions_manual_3_url_en.nl',
            'instructions_manual_3_url_nl' => 'https://instructions_manual_3_url_nl.nl',
            'support_for_teachers_en'      => 'support_for_teachers_en',
            'support_for_teachers_nl'      => 'support_for_teachers_nl',
            'accessibility_facilities_en'  => 'accessibility_facilities_en',
            'accessibility_facilities_nl'  => 'accessibility_facilities_nl',
            'use_for_education_en'         => 'use_for_education_en',
            'use_for_education_nl'         => 'use_for_education_nl',
            'how_does_it_work_en'          => 'how_does_it_work_en',
            'how_does_it_work_nl'          => 'how_does_it_work_nl',
            'description_short_en'         => 'description_short_en',
            'description_short_nl'         => 'description_short_nl',
            'description_long_en'          => 'description_long_en',
            'description_long_nl'          => 'description_long_nl',
        ]);

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('other/tool/Show')
                    ->where('tool.addons', $tool->addons_nl)
                    ->where('tool.system_requirements', $tool->system_requirements_nl)
                    ->where('tool.instructions_manual_1_url', $tool->instructions_manual_1_url_nl)
                    ->where('tool.instructions_manual_2_url', $tool->instructions_manual_2_url_nl)
                    ->where('tool.instructions_manual_3_url', $tool->instructions_manual_3_url_nl)
                    ->where('tool.support_for_teachers', $tool->support_for_teachers_nl)
                    ->where('tool.accessibility_facilities', $tool->accessibility_facilities_nl)
                    ->where('tool.use_for_education', $tool->use_for_education_nl)
                    ->where('tool.how_does_it_work', $tool->how_does_it_work_nl)
                    ->where('tool.description_short', $tool->description_short_nl)
                    ->where('tool.description_long', $tool->description_long_nl)
            );
    }

    /** @test */
    public function english_fields_are_used_as_fallback(): void
    {
        $this->app->setLocale('nl');

        $tool = Tool::factory()->published(true)->create([
            'addons_en'                    => 'addons_en',
            'addons_nl'                    => null,
            'system_requirements_en'       => 'system_requirements_en',
            'system_requirements_nl'       => null,
            'instructions_manual_1_url_en' => 'https://instructions_manual_1_url_en.nl',
            'instructions_manual_1_url_nl' => null,
            'instructions_manual_2_url_en' => null,
            'instructions_manual_2_url_nl' => null,
            'instructions_manual_3_url_en' => 'https://instructions_manual_3_url_en.nl',
            'instructions_manual_3_url_nl' => null,
            'support_for_teachers_en'      => 'support_for_teachers_en',
            'support_for_teachers_nl'      => null,
            'accessibility_facilities_en'  => 'accessibility_facilities_en',
            'accessibility_facilities_nl'  => null,
            'use_for_education_en'         => 'use_for_education_en',
            'use_for_education_nl'         => null,
            'how_does_it_work_en'          => 'how_does_it_work_en',
            'how_does_it_work_nl'          => null,
            'description_short_en'         => 'description_short_en',
            'description_short_nl'         => null,
            'description_long_en'          => 'description_long_en',
            'description_long_nl'          => null,
        ]);

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Show')
                    ->where('tool.addons', $tool->addons_en)
                    ->where('tool.system_requirements', $tool->system_requirements_en)
                    ->where('tool.instructions_manual_1_url', $tool->instructions_manual_1_url_en)
                    ->where('tool.instructions_manual_2_url', $tool->instructions_manual_2_url_en)
                    ->where('tool.instructions_manual_3_url', $tool->instructions_manual_3_url_en)
                    ->where('tool.support_for_teachers', $tool->support_for_teachers_en)
                    ->where('tool.accessibility_facilities', $tool->accessibility_facilities_en)
                    ->where('tool.use_for_education', $tool->use_for_education_en)
                    ->where('tool.how_does_it_work', $tool->how_does_it_work_en)
                    ->where('tool.description_short', $tool->description_short_en)
                    ->where('tool.description_long', $tool->description_long_en)
            );
    }

    public function nonTeacherRolesDataProvider(): array
    {
        $nonTeacherRoles = Arr::where(Role::toArray(), fn ($role) => $role !== Role::TEACHER);

        return Arr::map($nonTeacherRoles, fn ($role) => [$role]);
    }
}
