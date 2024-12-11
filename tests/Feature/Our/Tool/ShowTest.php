<?php

declare(strict_types=1);

namespace Tests\Feature\Our\Tool;

use App\Enums\Auth\Role;
use App\Models\Experience;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
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

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Show')
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_for_tools_that_are_not_published_for_the_institute(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => null,
        ]);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool));
    }

    /** @test */
    public function the_page_can_not_be_visited_for_tools_the_institute_does_not_own(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool));
    }

    /** @test */
    public function it_contains_the_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('our/tool/Show')
                    ->where('tool.id', $tool->id)
            );
    }

    /** @test */
    public function the_information_manager_has_permission_to_submit_a_request_for_change(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

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

        $user->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($user)
            ->get(route('our.tool.show', $tool))

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

        $user->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($user)
            ->get(route('our.tool.show', $tool))

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

        $user->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($user)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('tool.permissions.see_all_fields', true)
            );
    }

    /** @test */
    public function it_knows_the_total_experience_amount_for_a_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        Experience::factory(3)->for($tool)->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('our/tool/Show')
                    ->where('tool.total_experiences', 3)
            );
    }

    /** @test */
    public function it_contains_the_back_url_to_our_tools_index(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('our/tool/Show')
                    ->where('backUrl', route('tool.index'))
            );
    }

    /** @test */
    public function it_contains_the_experiences_in_latest_order(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

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
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Show')
                    ->where('experiences.0.title', '::experience-3::')
                    ->where('experiences.1.title', '::experience-2::')
                    ->where('experiences.2.title', '::experience-1::')
            );
    }

    /** @test */
    public function experience_has_user_when_created_by_acting_user(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        Experience::factory()
            ->for($tool)
            ->for($this->informationManager)
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('experiences.0.user.id', $this->informationManager->id)
            );
    }

    /** @test */
    public function experience_has_user_when_created_by_other_user_from_same_institute_as_acting_user(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $otherUserFromInstitute = User::factory()
            ->for($this->informationManager->institute)
            ->create();

        Experience::factory()
            ->for($tool)
            ->for($otherUserFromInstitute)
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('experiences.0.user.id', $otherUserFromInstitute->id)
            );
    }

    /** @test */
    public function experience_has_no_user_when_created_by_user_from_other_institute_than_acting_user(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $userFromOtherInstitute = User::factory()->create();

        Experience::factory()
            ->for($tool)
            ->for($userFromOtherInstitute)
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

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
            ->get(route('our.tool.show', $tool));
    }

    /** @test */
    public function the_fields_are_translated(): void
    {
        $this->app->setLocale('nl');

        $tool = Tool::factory()->published(true)->create();

        $instituteTool = InstituteTool::factory()
            ->for($tool)
            ->for($this->informationManager->institute)
            ->create([
                'conditions_en'              => 'conditions_en',
                'conditions_nl'              => 'conditions_nl',
                'links_with_other_tools_en'  => 'links_with_other_tools_en',
                'links_with_other_tools_nl'  => 'links_with_other_tools_nl',
                'how_to_login_en'            => 'how_to_login_en',
                'how_to_login_nl'            => 'how_to_login_nl',
                'availability_en'            => 'availability_en',
                'availability_nl'            => 'availability_nl',
                'licensing_en'               => 'licensing_en',
                'licensing_nl'               => 'licensing_nl',
                'instructions_en'            => 'instructions_en',
                'instructions_nl'            => 'instructions_nl',
                'faq_en'                     => 'faq_en',
                'faq_nl'                     => 'faq_nl',
                'examples_of_usage_en'       => 'examples_of_usage_en',
                'examples_of_usage_nl'       => 'examples_of_usage_nl',
                'additional_info_heading_en' => 'additional_info_heading_en',
                'additional_info_heading_nl' => 'additional_info_heading_nl',
                'additional_info_text_en'    => 'additional_info_text_en',
                'additional_info_text_nl'    => 'additional_info_text_nl',
            ]);

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Show')
                    ->where('tool.institute.conditions', $instituteTool->conditions_nl)
                    ->where('tool.institute.links_with_other_tools', $instituteTool->links_with_other_tools_nl)
                    ->where('tool.institute.how_to_login', $instituteTool->how_to_login_nl)
                    ->where('tool.institute.availability', $instituteTool->availability_nl)
                    ->where('tool.institute.licensing', $instituteTool->licensing_nl)
                    ->where('tool.institute.instructions', $instituteTool->instructions_nl)
                    ->where('tool.institute.faq', $instituteTool->faq_nl)
                    ->where('tool.institute.examples_of_usage', $instituteTool->examples_of_usage_nl)
                    ->where('tool.institute.additional_info_heading', $instituteTool->additional_info_heading_nl)
                    ->where('tool.institute.additional_info_text', $instituteTool->additional_info_text_nl)
            );
    }

    /** @test */
    public function english_fields_are_used_as_fallback(): void
    {
        $this->app->setLocale('nl');

        $tool = Tool::factory()->published(true)->create();

        $instituteTool = InstituteTool::factory()
            ->for($tool)
            ->for($this->informationManager->institute)
            ->create([
                'conditions_en'              => 'conditions_en',
                'conditions_nl'              => null,
                'links_with_other_tools_en'  => 'links_with_other_tools_en',
                'links_with_other_tools_nl'  => null,
                'how_to_login_en'            => 'how_to_login_en',
                'how_to_login_nl'            => null,
                'availability_en'            => 'availability_en',
                'availability_nl'            => null,
                'licensing_en'               => 'licensing_en',
                'licensing_nl'               => null,
                'instructions_en'            => 'instructions_en',
                'instructions_nl'            => null,
                'faq_en'                     => 'faq_en',
                'faq_nl'                     => null,
                'examples_of_usage_en'       => 'examples_of_usage_en',
                'examples_of_usage_nl'       => null,
                'additional_info_heading_en' => 'additional_info_heading_en',
                'additional_info_heading_nl' => null,
                'additional_info_text_en'    => 'additional_info_text_en',
                'additional_info_text_nl'    => null,
            ]);

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Show')
                    ->where('tool.institute.conditions', $instituteTool->conditions_en)
                    ->where('tool.institute.links_with_other_tools', $instituteTool->links_with_other_tools_en)
                    ->where('tool.institute.how_to_login', $instituteTool->how_to_login_en)
                    ->where('tool.institute.availability', $instituteTool->availability_en)
                    ->where('tool.institute.licensing', $instituteTool->licensing_en)
                    ->where('tool.institute.instructions', $instituteTool->instructions_en)
                    ->where('tool.institute.faq', $instituteTool->faq_en)
                    ->where('tool.institute.examples_of_usage', $instituteTool->examples_of_usage_en)
                    ->where('tool.institute.additional_info_heading', $instituteTool->additional_info_heading_en)
                    ->where('tool.institute.additional_info_text', $instituteTool->additional_info_text_en)
            );
    }

    /**
     * @test
     *
     * @dataProvider localesDataProvider
     */
    public function it_has_the_correct_institute_tool_tooltips_for_any_locale(string $locale): void
    {
        $this->app->setLocale($locale);

        $tool = Tool::factory()->published(true)->create();

        InstituteTool::factory()
            ->for($tool)
            ->for($this->informationManager->institute)
            ->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $localeSuffix = '_' . $locale;

        $expectedTooltips = [
            'status'                    => trans('institute.tool.tooltip.status'),
            'conditions'                => trans('institute.tool.tooltip.conditions' . $localeSuffix),
            'faq'                       => trans('institute.tool.tooltip.faq' . $localeSuffix),
            'examples_of_usage'         => trans('institute.tool.tooltip.examples_of_usage' . $localeSuffix),
            'additional_info_heading'   => trans('institute.tool.tooltip.additional_info_heading' . $localeSuffix),
            'additional_info_text'      => trans('institute.tool.tooltip.additional_info_text' . $localeSuffix),
            'how_to_login'              => trans('institute.tool.tooltip.how_to_login' . $localeSuffix),
            'availability'              => trans('institute.tool.tooltip.availability' . $localeSuffix),
            'licensing'                 => trans('institute.tool.tooltip.licensing' . $localeSuffix),
            'request_access'            => trans('institute.tool.tooltip.request_access' . $localeSuffix),
            'instructions'              => trans('institute.tool.tooltip.instructions' . $localeSuffix),
            'instructions_manual_1_url' => trans('institute.tool.tooltip.instructions_manual_1_url'),
            'instructions_manual_2_url' => trans('institute.tool.tooltip.instructions_manual_2_url'),
            'instructions_manual_3_url' => trans('institute.tool.tooltip.instructions_manual_3_url'),
            'links_with_other_tools'    => trans('institute.tool.tooltip.links_with_other_tools' . $localeSuffix),
            'sla_url'                   => trans('institute.tool.tooltip.sla_url'),
            'privacy_contact'           => trans('institute.tool.tooltip.privacy_contact'),
            'privacy_evaluation_url'    => trans('institute.tool.tooltip.privacy_evaluation_url'),
            'security_evaluation_url'   => trans('institute.tool.tooltip.security_evaluation_url'),
            'data_classification'       => trans('institute.tool.tooltip.data_classification'),
            'categories'                => trans('institute.tool.tooltip.categories'),
            'why_unfit'                 => trans('institute.tool.tooltip.why_unfit' . $localeSuffix),
        ];

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('tool.institute.tooltips', $expectedTooltips)
            );
    }

    public static function localesDataProvider(): array
    {
        return [
            ['en'],
            ['nl'],
        ];
    }

    public function nonTeacherRolesDataProvider(): array
    {
        $nonTeacherRoles = Arr::where(Role::toArray(), fn ($role) => $role !== Role::TEACHER);

        return Arr::map($nonTeacherRoles, fn ($role) => [$role]);
    }
}
