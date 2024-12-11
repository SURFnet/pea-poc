<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool;

use App\Actions\Tool\NotifyStakeholdersAction;
use App\Enums\Tags\TagTypes;
use App\Models\PendingToolEdit;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\ToolLog;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Tests\TestCase;

class PublishTest extends TestCase
{
    /** @test */
    public function a_tool_can_be_published(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), $this->validRequestData($tool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertTrue($tool->refresh()->is_published);
    }

    /** @test */
    public function changes_are_persisted_when_publishing(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), [
                ...$this->validRequestData($tool),
                'name'                 => '::edited_name::',
                'description_short_en' => '::edited_description_short_en::',
                'description_short_nl' => '::edited_description_short_nl::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertDatabaseHas('tools', [
            'id' => $tool->id,

            'name'                 => '::edited_name::',
            'description_short_en' => '::edited_description_short_en::',
            'description_short_nl' => '::edited_description_short_nl::',
        ]);
    }

    /** @test */
    public function a_tool_can_not_be_published_again(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), $this->validRequestData($tool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));
    }

    /** @test */
    public function an_existing_pending_edit_is_deleted(): void
    {
        $tool = Tool::factory()->published(false)->create();
        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->admin->id,
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), $this->validRequestData($tool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertNull($pendingEdit->fresh());
    }

    /** @test */
    public function a_tool_log_will_be_created(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), $this->validRequestData($tool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $log = ToolLog::first();

        $this->assertNotNull($log);
        $this->assertEquals($tool->id, $log->tool->id);
        $this->assertEquals($this->admin->id, $log->user->id);
        $this->assertEquals(null, $log->institute);
    }

    /** @test */
    public function it_does_not_notify_stakeholders_when_nothing_is_updated(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldNotReceive('execute');
        });

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), $this->validRequestData($tool));
    }

    /** @test */
    public function it_notifies_stakeholders_when_fillable_is_updated(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldReceive('execute')->once();
        });

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), [
                ...$this->validRequestData($tool),
                'name' => ':: some other value ::',
            ]);
    }

    /** @test */
    public function it_notifies_stakeholders_when_logo_is_updated(): void
    {
        $tool = Tool::factory()->published(false)->create([
            'logo_filename'    => UploadedFile::fake()->image('old-logo.jpg'),
            'image_1_filename' => null,
            'image_2_filename' => null,
        ]);

        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldReceive('execute')->once();
        });

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), [
                ...$this->validRequestData($tool),
                'name'                 => $tool->name,
                'description_short_en' => $tool->description_short_en,
                'description_short_nl' => $tool->description_short_nl,
                'logo_filename'        => UploadedFile::fake()->image('new-logo.jpg'),
            ]);
    }

    /**
     * @test
     *
     * @dataProvider fieldsAndTagTypesDataprovider
     */
    public function it_notifies_stakeholders_when_tags_are_updated(string $attribute, string $tagType): void
    {
        $tool = Tool::factory()->published(false)->create();

        $otherTag = Tag::factory()->create([
            'type' => $tagType,
            'name' => 'Other tag',
        ]);

        $this->mock(NotifyStakeholdersAction::class, function (MockInterface $mock): void {
            $mock->shouldReceive('execute')->once();
        });

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), [
                ...$this->validRequestData($tool),
                $attribute => [$otherTag->id],
            ]);
    }

    public function fieldsAndTagTypesDataprovider(): array
    {
        return [
            ['features', TagTypes::FEATURES],
            ['software_types', TagTypes::SOFTWARE_TYPES],
            ['devices', TagTypes::DEVICES],
            ['standards', TagTypes::STANDARDS],
            ['operating_systems', TagTypes::OPERATING_SYSTEMS],
            ['data_processing_locations', TagTypes::DATA_PROCESSING_LOCATIONS],
            ['certifications', TagTypes::CERTIFICATIONS],
            ['working_methods', TagTypes::WORKING_METHODS],
            ['target_groups', TagTypes::TARGET_GROUPS],
            ['complexity', TagTypes::COMPLEXITY],
        ];
    }

    private function validRequestData(Tool $tool): array
    {
        return [
            ...$tool->toArray(),
            'features'                  => $tool->features()->pluck('id')->toArray(),
            'software_types'            => $tool->softwareType()->pluck('id')->toArray(),
            'devices'                   => $tool->devices()->pluck('id')->toArray(),
            'standards'                 => $tool->standards()->pluck('id')->toArray(),
            'operating_systems'         => $tool->operatingSystem()->pluck('id')->toArray(),
            'data_processing_locations' => $tool->dataProcessingLocation()->pluck('id')->toArray(),
            'certifications'            => $tool->certification()->pluck('id')->toArray(),
            'working_methods'           => $tool->workingMethods()->pluck('id')->toArray(),
            'target_groups'             => $tool->targetGroup()->pluck('id')->toArray(),
            'complexity'                => $tool->complexity()->pluck('id')->toArray(),
        ];
    }
}
