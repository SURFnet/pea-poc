<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool\Concept;

use App\Actions\Institute\Tool\AddAction;
use App\Actions\Institute\Tool\Concept\CreateAction;
use App\Models\ConceptInstituteTool;
use App\Models\InstituteTool;
use App\Models\Tool;
use Tests\TestCase;

class PublishTest extends TestCase
{
    private Tool $tool;
    private InstituteTool $instituteTool;
    private ConceptInstituteTool $concept;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tool = Tool::factory()->published()->create();
        $institute = $this->admin->institute;

        (new AddAction())->execute($this->tool, $institute, [], $this->admin);

        $this->instituteTool = InstituteTool::forTool($this->tool)->forInstitute($institute)->firstOrFail();

        (new CreateAction())->execute($this->tool, $institute);

        $this->instituteTool->refresh();

        $this->concept = $this->instituteTool->concept;
    }

    /** @test */
    public function the_data_from_concept_is_copied_to_the_original(): void
    {
        $data = [
            'conditions_en' => 'Modified value',
            'conditions_nl' => 'Aangepaste waarde',
        ];

        $this->concept->update($data);

        $this
            ->actingAs($this->admin)
            ->put(route('information-manager.tool.publish-concept', $this->tool))

            ->assertSessionHasNoErrors();

        $this->assertModelMissing($this->concept);

        $this->instituteTool->refresh();

        $this->assertEquals($data['conditions_en'], $this->instituteTool->conditions_en);
        $this->assertEquals($data['conditions_nl'], $this->instituteTool->conditions_nl);
    }
}
