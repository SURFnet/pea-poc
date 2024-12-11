<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool\Concept;

use App\Actions\Tool\Concept\CreateAction;
use App\Actions\Tool\NotifyStakeholdersAction;
use App\Models\ConceptTool;
use App\Models\Tool;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Tests\TestCase;

class PublishTest extends TestCase
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
    public function the_data_from_concept_is_copied_to_the_original(): void
    {
        $data = [
            'name' => 'Modified Name',
        ];

        $this->concept->update($data);

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish-concept', $this->tool))

            ->assertSessionHasNoErrors();

        $this->assertModelMissing($this->concept);

        $this->tool->refresh();
        $this->assertEquals($data['name'], $this->tool->name);
    }

    /** @test */
    public function it_does_not_notify_stakeholders_when_nothing_is_updated(): void
    {
        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldNotReceive('execute');
        });

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish-concept', $this->tool), $this->tool->toArray());
    }

    /** @test */
    public function it_notifies_stakeholders_when_fillable_is_updated(): void
    {
        $data = [
            'name' => 'Modified Name',
        ];

        $this->concept->update($data);

        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldReceive('execute')->once();
        });

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish-concept', $this->tool));
    }

    /** @test */
    public function it_notifies_stakeholders_when_logo_is_updated(): void
    {
        $this->concept->logo_filename = UploadedFile::fake()->image('image1.jpg');
        $this->concept->save();

        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldReceive('execute')->once();
        });

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish-concept', $this->tool));
    }
}
