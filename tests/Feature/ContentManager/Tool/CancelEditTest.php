<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool;

use App\Actions\Tool\Concept\CreateAction;
use App\Models\ConceptTool;
use App\Models\PendingToolEdit;
use App\Models\Tool;
use Tests\TestCase;

class CancelEditTest extends TestCase
{
    private Tool $tool;
    private ConceptTool $concept;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tool = Tool::factory()->published()->create();

        (new CreateAction())->execute($this->tool);

        $this->tool->refresh();
        $this->concept = $this->tool->concept;
    }

    /** @test */
    public function an_existing_pending_edit_is_deleted(): void
    {
        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $this->tool->id,
            'user_id'      => $this->admin->id,
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->post(route('content-manager.tool.cancel-edit', $this->tool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertNull($pendingEdit->fresh());
    }

    /** @test */
    public function a_concept_is_automatically_discarded_when_no_edits_were_made(): void
    {
        $this
            ->actingAs($this->admin)
            ->post(route('content-manager.tool.cancel-edit', $this->tool));

        $this->assertEquals(
            session()->get('flash_notification')->first()->message,
            trans('message.concept-discarded', [
                'entity' => $this->tool->name,
            ]),
        );
        $this->assertNull($this->tool->refresh()->concept);
    }

    /** @test */
    public function a_concept_is_not_discarded_when_it_was_updated_after_creation(): void
    {
        $this->concept->created_at = $this->concept->updated_at->subDay();
        $this->concept->save();

        $this
            ->actingAs($this->admin)
            ->post(route('content-manager.tool.cancel-edit', $this->tool));

        $this->assertFalse(session()->has('flash_notification'));
        $this->assertNotNull($this->tool->refresh()->concept);
    }
}
