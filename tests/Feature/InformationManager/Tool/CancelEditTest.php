<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Actions\Institute\Tool\AddAction;
use App\Actions\Institute\Tool\Concept\CreateAction;
use App\Models\ConceptInstituteTool;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\PendingToolEdit;
use App\Models\Tool;
use App\Models\User;
use Tests\TestCase;

class CancelEditTest extends TestCase
{
    private Tool $tool;
    private Institute $institute;
    private ConceptInstituteTool $concept;
    private InstituteTool $instituteTool;
    private PendingToolEdit $pendingEdit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tool = Tool::factory()->published()->create();
        $this->institute = $this->informationManager->institute;

        (new AddAction())->execute($this->tool, $this->institute, [], $this->informationManager);

        $this->instituteTool = InstituteTool::forTool($this->tool)->forInstitute($this->institute)->firstOrFail();

        (new CreateAction())->execute($this->tool, $this->institute);

        $this->instituteTool->refresh();

        $this->concept = $this->instituteTool->concept;

        $this->pendingEdit = $this->createPendingEdit($this->tool, $this->informationManager);
    }

    /** @test */
    public function an_existing_pending_edit_is_deleted(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.cancel-edit', $this->tool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertNull($this->pendingEdit->fresh());
    }

    /** @test */
    public function a_concept_is_automatically_discarded_when_no_edits_were_made(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.cancel-edit', $this->tool));

        $this->assertEquals(
            session()->get('flash_notification')->first()->message,
            trans('message.concept-discarded', [
                'entity' => $this->tool->name,
            ]),
        );
        $this->assertNull($this->instituteTool->refresh()->concept);
    }

    /** @test */
    public function a_concept_is_not_discarded_when_it_was_updated_after_creation(): void
    {
        $this->concept->created_at = $this->concept->updated_at->subDay();
        $this->concept->save();

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.cancel-edit', $this->tool));

        $this->assertFalse(session()->has('flash_notification'));
        $this->assertNotNull($this->instituteTool->refresh()->concept);
    }

    private function createPendingEdit(Tool $tool, User $user): PendingToolEdit
    {
        return PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $user->id,
            'institute_id' => $user->institute->id,
        ])->create();
    }
}
