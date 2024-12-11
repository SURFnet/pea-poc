<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool\Concept;

use App\Actions\Institute\Tool\AddAction;
use App\Actions\Institute\Tool\Concept\CreateAction;
use App\Models\ConceptInstituteTool;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;
use Tests\TestCase;

class DiscardTest extends TestCase
{
    private Tool $tool;
    private Institute $institute;
    private ConceptInstituteTool $concept;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tool = Tool::factory()->published()->create();
        $this->institute = $this->admin->institute;

        (new AddAction())->execute($this->tool, $this->institute, [], $this->admin);

        $instituteTool = InstituteTool::forTool($this->tool)->forInstitute($this->institute)->firstOrFail();

        (new CreateAction())->execute($this->tool, $this->institute);

        $instituteTool->refresh();

        $this->concept = $instituteTool->concept;
    }

    /** @test */
    public function the_concept_version_can_be_discarded(): void
    {
        $this->assertModelExists($this->concept);

        $this
            ->actingAs($this->admin)
            ->put(route('information-manager.tool.discard-concept', $this->tool))

            ->assertSessionHasNoErrors();

        $this->assertModelMissing($this->concept);
    }
}
