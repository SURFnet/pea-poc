<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool\Prohibited;

use App\Enums\InstituteTool\Status;
use App\Models\InstituteTool;
use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function can_be_done_without_additional_data(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.prohibited.update', $tool))

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
        $institute = $this->informationManager->institute;

        $tool = Tool::factory()->published()->create();
        $institute->tools()->attach($tool, [
            'why_unfit'           => '::old-why-unfit',
            'alternative_tool_id' => null,
        ]);

        $data = [
            'why_unfit'           => '::why-unfit::',
            'alternative_tool_id' => $alternativeTool->id,
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.prohibited.update', $tool), $data)

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
        $institute = $this->informationManager->institute;

        $tool = Tool::factory()->published()->create();
        $institute->tools()->attach($tool, [
            'alternative_tool_id' => null,
        ]);

        $data = [
            'alternative_tool_id' => $alternativeTool->id,
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.prohibited.update', $tool), $data)

            ->assertSessionHasErrors('alternative_tool_id');

        $this->assertDatabaseMissing('institute_tool', array_merge($data, [
            'status' => Status::PROHIBITED,
        ]));
    }

    /** @test */
    public function a_fit_tool_can_be_changed_to_prohibited(): void
    {
        $alternativeTool = Tool::factory()->published()->create();
        InstituteTool::factory()
            ->for($alternativeTool)
            ->for($this->informationManager->institute)
            ->published()
            ->create();
        $institute = $this->informationManager->institute;

        $tool = Tool::factory()->published()->create();
        $institute->tools()->attach($tool, [
            'status'              => Status::RECOMMENDED,
            'why_unfit'           => null,
            'alternative_tool_id' => null,
        ]);

        $data = [
            'why_unfit'           => '::why-unfit::',
            'alternative_tool_id' => $alternativeTool->id,
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.prohibited.update', $tool), $data)

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertDatabaseHas('institute_tool', array_merge($data, [
            'status' => Status::PROHIBITED,
        ]));
    }

    /** @test */
    public function a_tool_can_only_be_updated_when_it_is_published(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.prohibited.update', $tool));
    }

    /** @test */
    public function only_our_tools_can_be_updated(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.prohibited.update', $tool));
    }

    /** @test */
    public function a_teacher_can_not_update_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->put(route('information-manager.tool.prohibited.update', $tool));
    }

    /** @test */
    public function a_guest_can_not_update_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->put(route('information-manager.tool.prohibited.update', $tool));
    }
}
