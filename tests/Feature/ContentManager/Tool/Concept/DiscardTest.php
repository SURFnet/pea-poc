<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool\Concept;

use App\Actions\Tool\Concept\CreateAction;
use App\Models\ConceptTool;
use App\Models\Tool;
use Tests\TestCase;

class DiscardTest extends TestCase
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
    public function the_concept_version_can_be_discarded(): void
    {
        $this->assertModelExists($this->concept);

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.discard-concept', $this->tool))

            ->assertSessionHasNoErrors();

        $this->assertModelMissing($this->concept);
    }
}
