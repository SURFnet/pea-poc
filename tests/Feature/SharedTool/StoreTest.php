<?php

declare(strict_types=1);

// phpcs:disable Generic.Files.LineLength.TooLong

namespace Tests\Feature\SharedTool;

use App\Enums\Tool\Status;
use App\Models\Tool;
use App\Models\ToolLog;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\SharedTool\Concerns\HasToolTypeProvider;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use HasToolTypeProvider;

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function can_be_done_with_minimal_data(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName), [
                ...$this->validRequestData(),
                'name'                 => '::name::',
                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',
                'logo_filename'        => UploadedFile::fake()->image('logo.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route($this->indexRouteName));

        $this->assertDatabaseHas('tools', [
            'name'                 => '::name::',
            'institute_id'         => $isCustomTool ? $this->actingUser->institute_id : null,
            'description_short_en' => '::description_short_en::',
            'description_short_nl' => '::description_short_nl::',
        ]);
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function can_save_and_continue_editing(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $response = $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName, ['continue' => true]), [
                ...$this->validRequestData(),
                'name'                 => '::name::',
                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',
                'logo_filename'        => UploadedFile::fake()->image('logo.jpg'),
            ]);

        $tool = Tool::where('name', '::name::')->first();

        $response
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route($this->editRouteName, $tool));

        $this->assertDatabaseHas('tools', [
            'name'                 => '::name::',
            'description_short_en' => '::description_short_en::',
            'description_short_nl' => '::description_short_nl::',
        ]);
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function images_can_be_uploaded(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName), [
                ...$this->validRequestData(),
                'name'                 => '::name::',
                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',

                'logo_filename'    => UploadedFile::fake()->image('image1.jpg'),
                'image_1_filename' => UploadedFile::fake()->image('image2.jpg'),
                'image_2_filename' => UploadedFile::fake()->image('image3.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route($this->indexRouteName));

        $tool = Tool::latest()->first();

        foreach (Tool::$images as $imageField) {
            $this->assertNotEmpty($tool->$imageField);
            $this->assertTrue(Storage::disk(Tool::$disk)->exists($tool->$imageField));

            Storage::disk(Tool::$disk)->delete($tool->$imageField);
        }
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function files_other_than_images_can_not_be_uploaded(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName), [
                ...$this->validRequestData(),
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

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function after_creating_it_gets_the_concept_status(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName), [
                ...$this->validRequestData(),
                'name'                 => '::name::',
                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',
                'logo_filename'        => UploadedFile::fake()->create('logo.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route($this->indexRouteName));

        $this->assertEquals(Status::CONCEPT, Tool::latest()->first()->status);
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function the_name_needs_to_be_unique(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Tool::factory()->create([
            'name' => '::name::',
        ]);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName), [
                ...$this->validRequestData(),
                'name' => '::name::',
            ])

            ->assertSessionHasErrors(['name']);
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function it_does_not_save_prefill_values_in_the_database(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName), [
                ...$this->validRequestData(),
                'privacy_analysis'     => trans('tool.prefills.privacy_analysis'),
                'use_for_education_en' => trans('tool.prefills.use_for_education', [], 'en'),
                'use_for_education_nl' => trans('tool.prefills.use_for_education', [], 'nl'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route($this->indexRouteName));

        $this->assertDatabaseHas('tools', [
            'privacy_analysis'     => null,
            'use_for_education_en' => null,
            'use_for_education_nl' => null,
        ]);
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function it_saves_other_values_than_prefill_values_in_prefilled_fields_in_the_database(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName), [
                ...$this->validRequestData(),
                'privacy_analysis'     => '::privacy_analysis::',
                'use_for_education_en' => '::use_for_education_en::',
                'use_for_education_nl' => '::use_for_education_nl::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route($this->indexRouteName));

        $this->assertDatabaseHas('tools', [
            'privacy_analysis'     => '::privacy_analysis::',
            'use_for_education_en' => '::use_for_education_en::',
            'use_for_education_nl' => '::use_for_education_nl::',
        ]);
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function can_be_done_with_all_data(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName), [
                ...$this->validRequestData(),
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
            ->assertRedirect(route($this->indexRouteName));

        $this->assertDatabaseHas('tools', [
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

            'published_at' => null,
        ]);
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_guest_can_not_store_a_tool(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route($this->storeRouteName));
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_tool_log_will_be_created(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this
            ->actingAs($this->actingUser)
            ->post(route($this->storeRouteName), [
                ...$this->validRequestData(),
                'name'                 => '::name::',
                'description_short_en' => '::description_short_en::',
                'description_short_nl' => '::description_short_nl::',
                'logo_filename'        => UploadedFile::fake()->create('logo.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route($this->indexRouteName));

        $this->assertDatabaseHas('tools', [
            'name'                 => '::name::',
            'description_short_en' => '::description_short_en::',
            'description_short_nl' => '::description_short_nl::',
        ]);

        $tool = Tool::latest()->first();
        $log = ToolLog::first();

        $this->assertNotNull($log);
        $this->assertEquals($tool->id, $log->tool->id);
        $this->assertEquals($this->actingUser->id, $log->user->id);
        $this->assertEquals(null, $log->institute);
    }

    private function validRequestData(): array
    {
        return [
            'name'                      => '::name::',
            'description_short_en'      => '::description_short_en::',
            'description_short_nl'      => '::description_short_nl::',
            'logo_filename'             => UploadedFile::fake()->create('logo.jpg'),
            'features'                  => [],
            'software_types'            => [],
            'devices'                   => [],
            'standards'                 => [],
            'operating_systems'         => [],
            'data_processing_locations' => [],
            'certifications'            => [],
            'working_methods'           => [],
            'target_groups'             => [],
            'complexity'                => [],
        ];
    }
}
