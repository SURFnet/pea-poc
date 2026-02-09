<?php

declare(strict_types=1);

namespace Tests\Feature\SharedTool\Concept;

use App\Actions\Tool\Concept\CreateAction;
use Tests\Feature\SharedTool\Concerns\HasToolTypeProvider;
use Tests\TestCase;

class DiscardTest extends TestCase
{
    use HasToolTypeProvider;

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function the_concept_version_can_be_discarded(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $tool = $this->baseFactory->published()->create();

        (new CreateAction())->execute($tool);

        $tool->refresh();

        $concept = $tool->concept;

        $this->assertModelExists($concept);

        $this
            ->actingAs($this->actingUser)
            ->put(route($this->discardConceptRouteName, $tool))

            ->assertSessionHasNoErrors();

        $this->assertModelMissing($concept);
    }
}
