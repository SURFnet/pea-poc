<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool\Prohibited;

use App\Enums\InstituteTool\Status;
use App\Models\InstituteTool;
use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /** @test */
    public function can_be_done_without_additional_data(): void
    {
        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.prohibited.store', $tool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertTrue($this->informationManager->institute->tools()->find($tool)->exists());
    }

    /** @test */
    public function all_data_is_correctly_saved_in_the_pivot_table(): void
    {
        $alternativeTool = Tool::factory()->published()->create();
        InstituteTool::factory()
            ->for($alternativeTool)
            ->for($this->informationManager->institute)
            ->published()
            ->create();
        $tool = Tool::factory()->published()->create();

        $data = [
            'why_unfit'           => '::why-unfit::',
            'alternative_tool_id' => $alternativeTool->id,
        ];

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.prohibited.store', $tool), $data)

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertDatabaseHas('institute_tool', array_merge($data, [
            'status' => Status::PROHIBITED,
        ]));
    }

    /** @test */
    public function a_prohibited_tool_can_not_be_set_as_alternative(): void
    {
        $alternativeTool = Tool::factory()->published()->create();
        InstituteTool::factory()
            ->for($alternativeTool)
            ->for($this->informationManager->institute)
            ->published()
            ->create(['status' => Status::PROHIBITED]);
        $tool = Tool::factory()->published()->create();

        $data = [
            'alternative_tool_id' => $alternativeTool->id,
        ];

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.prohibited.store', $tool), $data)

            ->assertSessionHasErrors(['alternative_tool_id']);

        $this->assertDatabaseMissing('institute_tool', array_merge($data, [
            'status' => Status::PROHIBITED,
        ]));
    }

    /** @test */
    public function a_tool_can_only_be_added_when_it_is_published(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.prohibited.store', $tool));
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
            ->post(route('information-manager.tool.prohibited.store', $tool));
    }

    /** @test */
    public function a_teacher_can_not_store_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->post(route('information-manager.tool.prohibited.store', $tool));
    }

    /** @test */
    public function a_guest_can_not_store_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route('information-manager.tool.prohibited.store', $tool));
    }
}
