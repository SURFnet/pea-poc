<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool;

use App\Enums\Tool\AuthenticationMethod;
use App\Enums\Tool\Status;
use App\Enums\Tool\StoredData;
use App\Enums\Tool\SupportedStandard;
use App\Models\Feature;
use App\Models\Tool;
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
                'name'              => '::edited_name::',
                'description_short' => '::edited_description_short::',
                'image_filename'    => null,

                'features' => null,

                'supported_standards'  => null,
                'additional_standards' => null,

                'authentication_methods' => null,

                'stored_data'       => null,
                'other_stored_data' => null,

                'european_data_storage'           => false,
                'surf_standards_framework_agreed' => false,
                'has_processing_agreement'        => false,

                'description_long_1'           => null,
                'description_1_image_filename' => null,

                'description_long_2'           => null,
                'description_2_image_filename' => null,

                'info_url' => null,
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertDatabaseHas('tools', [
            'id' => $tool->id,

            'name'              => '::edited_name::',
            'description_short' => '::edited_description_short::',
            'image_filename'    => null,

            'supported_standards'  => null,
            'additional_standards' => null,

            'authentication_methods' => null,

            'stored_data'       => null,
            'other_stored_data' => null,

            'european_data_storage'           => 0,
            'surf_standards_framework_agreed' => 0,
            'has_processing_agreement'        => 0,

            'description_long_1'           => null,
            'description_1_image_filename' => null,

            'description_long_2'           => null,
            'description_2_image_filename' => null,

            'info_url' => null,
        ]);

        $this->assertDatabaseMissing('feature_tool', [
            'tool_id' => $tool->id,
        ]);
    }

    /** @test */
    public function images_can_be_uploaded(): void
    {
        $tool = Tool::factory()->published(false)->create([
            'image_filename'               => null,
            'description_1_image_filename' => null,
            'description_2_image_filename' => null,
        ]);

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                'name'              => '::name::',
                'description_short' => '::description_short::',

                'image_filename'               => UploadedFile::fake()->image('image1.jpg'),
                'description_1_image_filename' => UploadedFile::fake()->image('image2.jpg'),
                'description_2_image_filename' => UploadedFile::fake()->image('image3.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $tool = $tool->refresh();

        foreach (Tool::$images as $imageField) {
            $this->assertNotEmpty($tool->$imageField);
            $this->assertTrue(Storage::disk(Tool::$disk)->exists($tool->$imageField));

            Storage::disk(Tool::$disk)->delete($tool->$imageField);
        }
    }

    /** @test */
    public function files_other_than_images_can_not_be_uploaded(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                'name'              => '::name::',
                'description_short' => '::description_short::',

                'image_filename'               => UploadedFile::fake()->create('file1.zip'),
                'description_1_image_filename' => UploadedFile::fake()->image('file2.docx'),
                'description_2_image_filename' => UploadedFile::fake()->image('file3.exe'),
            ])

            ->assertSessionHasErrors([
                'image_filename'               => trans('validation.image'),
                'description_1_image_filename' => trans('validation.image'),
                'description_2_image_filename' => trans('validation.image'),
            ]);
    }

    /** @test */
    public function existing_images_are_deleted(): void
    {
        $tool = Tool::factory()->published(false)->withImages()->create();

        $oldData = $tool->only(Tool::$images);

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                'name'              => '::name::',
                'description_short' => '::description_short::',

                'image_filename'               => UploadedFile::fake()->image('image1.jpg'),
                'description_1_image_filename' => UploadedFile::fake()->image('image2.jpg'),
                'description_2_image_filename' => UploadedFile::fake()->image('image3.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $tool = $tool->refresh();

        foreach ($oldData as $imageField => $value) {
            $this->assertFalse(Storage::disk(Tool::$disk)->exists($value));

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
                'name'              => '::name::',
                'description_short' => '::description_short::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertEquals(Status::CONCEPT, $tool->refresh()->status);
    }

    /** @test */
    public function the_name_needs_to_be_unique(): void
    {
        Tool::factory()->create(['name' => 'not-unique']);

        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                'name' => 'not-unique',
            ])

            ->assertSessionHasErrors([
                'name' => trans('validation.unique'),
            ]);
    }

    /** @test */
    public function can_be_done_with_all_data(): void
    {
        $attachedFeatures = Feature::factory()->count(2)->create();
        $otherFeatures = Feature::factory()->count(2)->create();

        $tool = Tool::factory()->published(false)->create([
            'supported_standards'             => [],
            'authentication_methods'          => [],
            'stored_data'                     => [],
            'european_data_storage'           => false,
            'surf_standards_framework_agreed' => false,
            'has_processing_agreement'        => false,
        ]);

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.update', $tool), [
                'name'              => '::edited_name::',
                'description_short' => '::edited_description_short::',

                'image_filename' => null,

                'features' => $attachedFeatures->pluck('id')->toArray(),

                'supported_standards'  => [SupportedStandard::CALIPER, SupportedStandard::QTI],
                'additional_standards' => '::edited_additional_standards::',

                'authentication_methods' => [AuthenticationMethod::SSO, AuthenticationMethod::SURFCONEXT],

                'stored_data'       => [StoredData::USAGE_LOGGING],
                'other_stored_data' => '::edited_other_stored_data::',

                'european_data_storage'           => true,
                'surf_standards_framework_agreed' => true,
                'has_processing_agreement'        => true,

                'description_long_1'           => '::edited_description_long_1::',
                'description_1_image_filename' => null,

                'description_long_2'           => '::edited_description_long_2::',
                'description_2_image_filename' => null,

                'info_url' => 'https://paqt.com#edited',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertDatabaseHas('tools', [
            'name'              => '::edited_name::',
            'description_short' => '::edited_description_short::',
            'image_filename'    => null,

            'description_long_1'           => '::edited_description_long_1::',
            'description_1_image_filename' => null,

            'description_long_2'           => '::edited_description_long_2::',
            'description_2_image_filename' => null,

            'info_url' => 'https://paqt.com#edited',

            'additional_standards' => '::edited_additional_standards::',

            'other_stored_data' => '::edited_other_stored_data::',

            'european_data_storage'           => 1,
            'surf_standards_framework_agreed' => 1,
            'has_processing_agreement'        => 1,

            'published_at' => null,
        ]);

        $tool = Tool::latest()->first();

        foreach ($attachedFeatures as $feature) {
            $this->assertDatabaseHas('feature_tool', [
                'feature_id' => $feature->id,
                'tool_id'    => $tool->id,
            ]);
        }

        foreach ($otherFeatures as $feature) {
            $this->assertDatabaseMissing('feature_tool', [
                'feature_id' => $feature->id,
            ]);
        }

        $this->assertEquals(
            [SupportedStandard::CALIPER, SupportedStandard::QTI],
            $tool->supported_standards
        );

        $this->assertEquals(
            [AuthenticationMethod::SSO, AuthenticationMethod::SURFCONEXT],
            $tool->authentication_methods
        );

        $this->assertEquals(
            [StoredData::USAGE_LOGGING],
            $tool->stored_data
        );
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
}
