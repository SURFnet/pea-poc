<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Enums\InstituteTool\DataClassification;
use App\Enums\InstituteTool\Status;
use App\Models\Tool;
use App\Models\ToolLog;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /** @test */
    public function can_be_done_with_minimal_data(): void
    {
        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool), $this->validRequestData())

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertTrue($this->informationManager->institute->tools()->find($tool)->exists());
    }

    /** @test */
    public function can_save_and_continue_editing(): void
    {
        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->informationManager)
            ->post(
                route('information-manager.tool.store', ['tool' => $tool, 'continue' => true]),
                $this->validRequestData()
            )

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.edit', $tool));

        $this->assertTrue($this->informationManager->institute->tools()->find($tool)->exists());
    }

    /** @test */
    public function all_data_is_correctly_saved_in_the_pivot_table(): void
    {
        $tool = Tool::factory()->published()->create();

        $data = [
            ...$this->validRequestData(),
            'status'        => Status::ALLOWED,
            'conditions_en' => '::conditions_en::',
            'conditions_nl' => '::conditions_en::',

            'links_with_other_tools_en' => '::links_with_other_tools_en::',
            'links_with_other_tools_nl' => '::links_with_other_tools_nl::',
            'sla_url'                   => 'https://sla-url.nl',

            'privacy_contact'         => '::privacy_contact::',
            'privacy_evaluation_url'  => 'https://privacy-evaluation-url.nl',
            'security_evaluation_url' => 'https://security-evaluation-url.nl',
            'data_classification'     => DataClassification::INTERNAL,

            'how_to_login_en'           => '::how_to_login_en::',
            'how_to_login_nl'           => '::how_to_login_nl::',
            'availability_en'           => '::availability_en::',
            'availability_nl'           => '::availability_nl::',
            'licensing_en'              => '::licensing_en::',
            'licensing_nl'              => '::licensing_nl::',
            'request_access_en'         => '::request_access_en::',
            'request_access_nl'         => '::request_access_nl::',
            'instructions_en'           => '::instructions_en::',
            'instructions_nl'           => '::instructions_nl::',
            'instructions_manual_1_url' => 'https://instructions-manual-1-url.nl',
            'instructions_manual_2_url' => 'https://instructions-manual-2-url.nl',
            'instructions_manual_3_url' => 'https://instructions-manual-3-url.nl',

            'faq_en'                     => '::faq_en::',
            'faq_nl'                     => '::faq_nl::',
            'examples_of_usage_en'       => '::examples_of_usage_en::',
            'examples_of_usage_nl'       => '::examples_of_usage_nl::',
            'additional_info_heading_en' => '::additional_info_heading_en::',
            'additional_info_heading_nl' => '::additional_info_heading_nl::',
            'additional_info_text_en'    => '::additional_info_text_en::',
            'additional_info_text_nl'    => '::additional_info_text_nl::',

            'why_unfit_en' => '::why_unfit_en::',
            'why_unfit_nl' => '::why_unfit_nl::',
        ];

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool), $data)

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));
    }

    /** @test */
    public function a_tool_can_only_be_added_when_it_is_published(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool));
    }

    /** @test */
    public function a_tool_can_only_be_added_once_per_institute(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool));
    }

    /** @test */
    public function a_teacher_can_not_store_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->post(route('information-manager.tool.store', $tool));
    }

    /** @test */
    public function a_guest_can_not_store_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route('information-manager.tool.store', $tool));
    }

    /** @test */
    public function a_tool_log_will_be_created(): void
    {
        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool), $this->validRequestData())

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $log = ToolLog::first();

        $this->assertNotNull($log);
        $this->assertEquals($tool->id, $log->tool->id);
        $this->assertEquals($this->informationManager->id, $log->user->id);
        $this->assertEquals($this->informationManager->institute->id, $log->institute->id);
    }

    private function validRequestData(): array
    {
        return [
            'categories'            => [],
            'alternative_tools_ids' => [],
        ];
    }
}
