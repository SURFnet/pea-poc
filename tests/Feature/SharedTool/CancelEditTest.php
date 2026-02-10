<?php

declare(strict_types=1);

namespace Tests\Feature\SharedTool;

use App\Actions\Tool\Concept\CreateAction;
use App\Models\ConceptTool;
use App\Models\PendingToolEdit;
use App\Models\Tool;
use Tests\Feature\SharedTool\Concerns\HasToolTypeProvider;
use Tests\TestCase;

class CancelEditTest extends TestCase
{
    use HasToolTypeProvider;

    private Tool $tool;
    private ConceptTool $concept;

    protected function createToolWithConcept(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this->tool = $this->baseFactory->published()->create();

        (new CreateAction())->execute($this->tool);

        $this->tool->refresh();
        $this->concept = $this->tool->concept;
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function an_existing_pending_edit_is_deleted(bool $isCustomTool): void
    {
        $this->createToolWithConcept($isCustomTool);

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $this->tool->id,
            'user_id'      => $this->actingUser->id,
            'institute_id' => $isCustomTool ? $this->actingUser->institute_id : null,
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->cancelEditRouteName, $this->tool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route($this->indexRouteName));

        $this->assertNull($pendingEdit->fresh());
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_concept_is_automatically_discarded_when_no_edits_were_made(bool $isCustomTool): void
    {
        $this->createToolWithConcept($isCustomTool);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->cancelEditRouteName, $this->tool));

        $this->assertEquals(
            session()->get('flash_notification')->first()->message,
            trans('message.concept-discarded', [
                'entity' => $this->tool->name,
            ]),
        );
        $this->assertNull($this->tool->refresh()->concept);
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_concept_is_not_discarded_when_it_was_updated_after_creation(bool $isCustomTool): void
    {
        $this->createToolWithConcept($isCustomTool);

        $this->concept->created_at = $this->concept->updated_at->subDay();
        $this->concept->save();

        $this
            ->actingAs($this->admin)
            ->post(route($this->editRouteName, $this->tool));

        $this->assertFalse(session()->has('flash_notification'));
        $this->assertNotNull($this->tool->refresh()->concept);
    }
}
