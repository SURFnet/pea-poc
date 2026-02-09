<?php

declare(strict_types=1);

namespace Tests\Feature\SharedTool\Concept;

use App\Actions\Tool\Concept\CreateAction;
use App\Actions\Tool\NotifyStakeholdersAction;
use App\Models\ConceptTool;
use App\Models\Tool;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Tests\Feature\SharedTool\Concerns\HasToolTypeProvider;
use Tests\TestCase;

class PublishTest extends TestCase
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
    public function the_data_from_concept_is_copied_to_the_original(bool $isCustomTool): void
    {
        $this->createToolWithConcept($isCustomTool);

        $data = [
            'name' => 'Modified Name',
        ];

        $this->concept->update($data);

        $this
            ->actingAs($this->actingUser)
            ->put(route($this->publishConceptRouteName, $this->tool))

            ->assertSessionHasNoErrors();

        $this->assertModelMissing($this->concept);

        $this->tool->refresh();
        $this->assertEquals($data['name'], $this->tool->name);
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function it_does_not_notify_stakeholders_when_nothing_is_updated(bool $isCustomTool): void
    {
        $this->createToolWithConcept($isCustomTool);

        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldNotReceive('execute');
        });

        $this
            ->actingAs($this->actingUser)
            ->put(route($this->publishConceptRouteName, $this->tool), $this->tool->toArray());
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function it_notifies_stakeholders_when_fillable_is_updated(bool $isCustomTool): void
    {
        $this->createToolWithConcept($isCustomTool);

        $data = [
            'name' => 'Modified Name',
        ];

        $this->concept->update($data);

        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldReceive('execute')->once();
        });

        $this
            ->actingAs($this->actingUser)
            ->put(route('content-manager.tool.publish-concept', $this->tool));
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function it_notifies_stakeholders_when_logo_is_updated(bool $isCustomTool): void
    {
        $this->createToolWithConcept($isCustomTool);

        $this->concept->logo_filename = UploadedFile::fake()->image('image1.jpg');
        $this->concept->save();

        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldReceive('execute')->once();
        });

        $this
            ->actingAs($this->actingUser)
            ->put(route('content-manager.tool.publish-concept', $this->tool));
    }
}
