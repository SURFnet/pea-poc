<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Actions\Tool\SubmitRequestForChangeAction;
use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Mockery\MockInterface;
use Tests\TestCase;

class RequestForChangeTest extends TestCase
{
    /** @test */
    public function a_teacher_can_not_access_the_endpoint(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'A change',
            ]);
    }

    /** @test */
    public function a_content_manager_can_not_access_the_endpoint(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->contentManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'A change',
            ]);
    }

    /** @test */
    public function an_information_manager_can_not_access_the_endpoint_when_tool_is_not_published(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'A change',
            ]);
    }

    /** @test */
    public function an_information_manager_can_access_the_endpoint_when_tool_is_in_institute_collection(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'A change',
            ])

            ->assertRedirect();
    }

    /** @test */
    public function an_information_manager_can_access_the_endpoint_when_tool_is_not_in_institute_collection(): void
    {
        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'A change',
            ])

            ->assertRedirect();
    }

    /** @test */
    public function it_redirects_back_on_validation_error(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $from = route('our.tool.show', $tool);

        $this
            ->from($from)
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool))

            ->assertRedirect($from);
    }

    /** @test */
    public function it_shows_a_validation_error_when_request_for_change_text_is_missing(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool))

            ->assertSessionHasErrors([
                'request_for_change' => trans('validation.required', ['attribute' => 'request for change']),
            ]);
    }

    /** @test */
    public function it_shows_a_validation_error_when_request_for_change_text_is_empty(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => '',
            ])

            ->assertSessionHasErrors([
                'request_for_change' => trans('validation.required', ['attribute' => 'request for change']),
            ]);
    }

    /** @test */
    public function it_shows_no_validation_error_when_request_for_change_text_is_given_and_not_empty(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'My requested change',
            ])

            ->assertSessionHasNoErrors();
    }

    /** @test */
    public function it_calls_the_submit_request_for_change_action(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this->mock(SubmitRequestForChangeAction::class, function (MockInterface $mock): void {
            $mock->shouldReceive('execute');
        });

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'My requested change',
            ])

            ->assertRedirect();
    }

    /** @test */
    public function it_redirects_back_to_our_tools_on_success_when_tool_is_in_institute_collection(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $from = route('our.tool.show', $tool);

        $this
            ->from($from)
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'My requested change',
            ])

            ->assertRedirect($from);
    }

    /** @test */
    public function it_redirects_back_to_other_tools_on_success_when_tool_is_in_institute_collection(): void
    {
        $tool = Tool::factory()->published()->create();

        $from = route('other.tool.show', $tool);

        $this
            ->from($from)
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'My requested change',
            ])

            ->assertRedirect($from);
    }

    /** @test */
    public function it_shows_a_flash_message_on_success(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.request-for-change', $tool), [
                'request_for_change' => 'My requested change',
            ]);

        $this->assertEquals(
            session()->get('flash_notification')->first()->message,
            trans('message.request_for_change_sent'),
        );
    }
}
