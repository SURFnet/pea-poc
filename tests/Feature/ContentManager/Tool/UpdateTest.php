<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool;

use App\Enums\Tool\Status;
use App\Models\PendingToolEdit;
use App\Models\Tool;
use App\Models\ToolLog;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function can_be_done_with_minimal_data(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'name'                 => '::edited_name::',
                'description_short_en' => '::edited_description_short_en::',
                'description_short_nl' => '::edited_description_short_nl::',
                'logo_filename'        => UploadedFile::fake()->image('logo.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $tool->refresh();

        $this->assertNotNull($tool->concept);

        $this->assertDatabaseHas('concept_tools', [
            'tool_id' => $tool->id,

            'name'                 => '::edited_name::',
            'description_short_en' => '::edited_description_short_en::',
            'description_short_nl' => '::edited_description_short_nl::',
        ]);
    }

    /** @test */
    public function can_save_and_continue_editing(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', ['tool' => $tool, 'continue' => true]), [
                ...$this->validRequestData($tool),
                'name'                 => '::edited_name::',
                'description_short_en' => '::edited_description_short_en::',
                'description_short_nl' => '::edited_description_short_nl::',
                'logo_filename'        => UploadedFile::fake()->image('logo.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.edit', $tool));

        $tool->refresh();

        $this->assertNotNull($tool->concept);

        $this->assertDatabaseHas('concept_tools', [
            'tool_id' => $tool->id,

            'name'                 => '::edited_name::',
            'description_short_en' => '::edited_description_short_en::',
            'description_short_nl' => '::edited_description_short_nl::',
        ]);
    }

    /** @test */
    public function images_can_be_uploaded(): void
    {
        /** @var Tool $tool */
        $tool = Tool::factory()->published(false)->create([
            'logo_filename'    => null,
            'image_1_filename' => null,
            'image_2_filename' => null,
        ]);

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'name'                 => '::name::',
                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',

                'logo_filename'    => UploadedFile::fake()->image('logo.jpg'),
                'image_1_filename' => UploadedFile::fake()->image('image_1.jpg'),
                'image_2_filename' => UploadedFile::fake()->image('image_2.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $tool = $tool->refresh();
        $concept = $tool->concept;
        $this->assertNotNull($concept);

        foreach (Tool::$images as $imageField) {
            $this->assertNotEmpty($concept->$imageField);
            $this->assertTrue(Storage::disk(Tool::$disk)->exists($concept->$imageField));

            Storage::disk(Tool::$disk)->delete($concept->$imageField);
        }
    }

    /** @test */
    public function files_other_than_images_can_not_be_uploaded(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'name'                 => '::name::',
                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',

                'logo_filename'    => UploadedFile::fake()->create('file1.zip'),
                'image_1_filename' => UploadedFile::fake()->image('file2.docx'),
                'image_2_filename' => UploadedFile::fake()->image('file3.exe'),
            ])

            ->assertSessionHasErrors([
                'logo_filename',
                'image_1_filename',
                'image_2_filename',
            ]);
    }

    /** @test */
    public function existing_images_are_not_deleted_when_also_used_by_original(): void
    {
        $tool = Tool::factory()->published(false)->withImages()->create();

        $oldData = $tool->only(Tool::$images);

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'name'                 => '::name::',
                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',

                'logo_filename'    => UploadedFile::fake()->image('image1.jpg'),
                'image_1_filename' => UploadedFile::fake()->image('image2.jpg'),
                'image_2_filename' => UploadedFile::fake()->image('image3.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $tool = $tool->refresh();

        foreach ($oldData as $imageField => $value) {
            $this->assertTrue(Storage::disk(Tool::$disk)->exists($value));

            Storage::disk(Tool::$disk)->delete($tool->$imageField);
        }
    }

    /** @test */
    public function after_updating_the_status_remains_unchanged(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'name'                 => '::name::',
                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertEquals(Status::CONCEPT, $tool->refresh()->status);
    }

    /** @test */
    public function the_name_needs_to_be_unique(): void
    {
        Tool::factory()->create([
            'name' => 'not-unique',
        ]);

        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'name' => 'not-unique',
            ])

            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_does_not_save_prefill_values_in_the_database(): void
    {
        $tool = Tool::factory()->create([
            'privacy_analysis'     => trans('tool.prefills.privacy_analysis'),
            'use_for_education_en' => trans('tool.prefills.use_for_education', [], 'en'),
            'use_for_education_nl' => trans('tool.prefills.use_for_education', [], 'nl'),
        ]);

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'privacy_analysis'     => trans('tool.prefills.privacy_analysis'),
                'use_for_education_en' => trans('tool.prefills.use_for_education', [], 'en'),
                'use_for_education_nl' => trans('tool.prefills.use_for_education', [], 'nl'),
            ]);

        $this->assertDatabaseHas('concept_tools', [
            'tool_id'              => $tool->id,
            'privacy_analysis'     => null,
            'use_for_education_en' => null,
            'use_for_education_nl' => null,
        ]);
    }

    /** @test */
    public function it_saves_other_values_than_prefill_values_in_prefilled_fields_in_the_database(): void
    {
        $tool = Tool::factory()->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'privacy_analysis'     => '::privacy_analysis::',
                'use_for_education_en' => '::use_for_education_en::',
                'use_for_education_nl' => '::use_for_education_nl::',
            ]);

        $this->assertDatabaseHas('concept_tools', [
            'tool_id'              => $tool->id,
            'privacy_analysis'     => '::privacy_analysis::',
            'use_for_education_en' => '::use_for_education_en::',
            'use_for_education_nl' => '::use_for_education_nl::',
        ]);
    }

    /** @test */
    public function can_be_done_with_all_data(): void
    {
        /** @var Tool $tool */
        $tool = Tool::factory()->published(false)->create([
            'name'                 => '::name::',
            'description_short_en' => '::description_short_en::',
            'description_short_nl' => '::description_short_nl::',
        ]);

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'name'         => '::name::',
                'supplier'     => '::supplier::',
                'supplier_url' => 'https://supplier-url.nl',

                'logo_filename'    => UploadedFile::fake()->create('logo.jpg'),
                'image_1_filename' => UploadedFile::fake()->create('image_1.jpg'),
                'image_2_filename' => UploadedFile::fake()->create('image_2.jpg'),

                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',
                'addons_en'            => '::addons_en::',
                'addons_nl'            => '::addons_nl::',

                'system_requirements_en' => '::system_requirements_en::',
                'system_requirements_nl' => '::system_requirements_nl::',

                'supplier_country' => 'NL',

                'personal_data_en' => '::personal_data_en::',
                'personal_data_nl' => '::personal_data_nl::',

                'privacy_policy_url'                  => 'https://privacy-policy-url.nl',
                'model_processor_agreement_url'       => 'https://model-processor-agreement-url.nl',
                'privacy_analysis'                    => '::privacy_analysis::',
                'supplier_agrees_with_surf_standards' => true,
                'dtia_by_external_url'                => 'https://dtia-by-external-url.nl',
                'dpia_by_external_url'                => 'https://dpia-by-external-url.nl',
                'jurisdiction'                        => '::jurisdiction::',

                'instructions_manual_1_url_en' => 'https://instructions-manual-1-url-en.nl',
                'instructions_manual_1_url_nl' => 'https://instructions-manual-1-url-nl.nl',
                'instructions_manual_2_url_en' => 'https://instructions-manual-2-url-en.nl',
                'instructions_manual_2_url_nl' => 'https://instructions-manual-2-url-nl.nl',
                'instructions_manual_3_url_en' => 'https://instructions-manual-3-url-en.nl',
                'instructions_manual_3_url_nl' => 'https://instructions-manual-3-url-nl.nl',
                'support_for_teachers_en'      => '::support_for_teachers_en::',
                'support_for_teachers_nl'      => '::support_for_teachers_nl::',
                'availability_surf'            => '::availability_surf::',
                'accessibility_facilities_en'  => '::accessibility_facilities_en::',
                'accessibility_facilities_nl'  => '::accessibility_facilities_nl::',

                'description_long_en'  => '::description_long_en::',
                'description_long_nl'  => '::description_long_nl::',
                'use_for_education_en' => '::use_for_education_en::',
                'use_for_education_nl' => '::use_for_education_nl::',
                'how_does_it_work_en'  => '::how_does_it_work_en::',
                'how_does_it_work_nl'  => '::how_does_it_work_nl::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertDatabaseHas('concept_tools', [
            'tool_id' => $tool->id,

            'name'         => '::name::',
            'supplier'     => '::supplier::',
            'supplier_url' => 'https://supplier-url.nl',

            'description_short_en' => '::description_short_en::',
            'description_short_nl' => '::description_short_nl::',
            'addons_en'            => '::addons_en::',
            'addons_nl'            => '::addons_nl::',

            'system_requirements_en' => '::system_requirements_en::',
            'system_requirements_nl' => '::system_requirements_nl::',

            'supplier_country' => 'NL',

            'personal_data_en' => '::personal_data_en::',
            'personal_data_nl' => '::personal_data_nl::',

            'privacy_policy_url'                  => 'https://privacy-policy-url.nl',
            'model_processor_agreement_url'       => 'https://model-processor-agreement-url.nl',
            'privacy_analysis'                    => '::privacy_analysis::',
            'supplier_agrees_with_surf_standards' => true,
            'dtia_by_external_url'                => 'https://dtia-by-external-url.nl',
            'dpia_by_external_url'                => 'https://dpia-by-external-url.nl',
            'jurisdiction'                        => '::jurisdiction::',

            'instructions_manual_1_url_en' => 'https://instructions-manual-1-url-en.nl',
            'instructions_manual_1_url_nl' => 'https://instructions-manual-1-url-nl.nl',
            'instructions_manual_2_url_en' => 'https://instructions-manual-2-url-en.nl',
            'instructions_manual_2_url_nl' => 'https://instructions-manual-2-url-nl.nl',
            'instructions_manual_3_url_en' => 'https://instructions-manual-3-url-en.nl',
            'instructions_manual_3_url_nl' => 'https://instructions-manual-3-url-nl.nl',
            'support_for_teachers_en'      => '::support_for_teachers_en::',
            'support_for_teachers_nl'      => '::support_for_teachers_nl::',
            'availability_surf'            => '::availability_surf::',
            'accessibility_facilities_en'  => '::accessibility_facilities_en::',
            'accessibility_facilities_nl'  => '::accessibility_facilities_nl::',

            'description_long_en'  => '::description_long_en::',
            'description_long_nl'  => '::description_long_nl::',
            'use_for_education_en' => '::use_for_education_en::',
            'use_for_education_nl' => '::use_for_education_nl::',
            'how_does_it_work_en'  => '::how_does_it_work_en::',
            'how_does_it_work_nl'  => '::how_does_it_work_nl::',
        ]);
    }

    /** @test */
    public function a_guest_can_not_update_a_tool(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $tool = Tool::factory()->create();

        $this
            ->put(route('content-manager.tool.update', $tool));
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
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'name'                 => '::edited_name::',
                'description_short_en' => '::edited_description_short_en::',
                'description_short_nl' => '::edited_description_short_nl::',
            ])

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
            ->put(route('content-manager.tool.update', $tool), [
                ...$this->validRequestData($tool),
                'name'                 => '::edited_name::',
                'description_short_en' => '::edited_description_short_en::',
                'description_short_nl' => '::edited_description_short_nl::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $tool = Tool::latest()->first();
        $log = ToolLog::first();

        $this->assertNotNull($log);
        $this->assertEquals($tool->id, $log->tool->id);
        $this->assertEquals($this->admin->id, $log->user->id);
        $this->assertEquals(null, $log->institute);
    }

    private function validRequestData(Tool $tool): array
    {
        return [
            'name'                      => $tool->name,
            'description_short_en'      => $tool->description_short_en,
            'description_short_nl'      => $tool->description_short_nl,
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
