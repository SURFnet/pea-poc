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

class StoreTest extends TestCase
{
    /** @test */
    public function can_be_done_with_minimal_data(): void
    {
        $this
            ->actingAs($this->admin)
            ->post(route('content-manager.tool.store'), [
                'name'              => '::name::',
                'description_short' => '::description_short::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertDatabaseHas('tools', [
            'name'              => '::name::',
            'description_short' => '::description_short::',
        ]);
    }

    /** @test */
    public function images_can_be_uploaded(): void
    {
        $this
            ->actingAs($this->admin)
            ->post(route('content-manager.tool.store'), [
                'name'              => '::name::',
                'description_short' => '::description_short::',

                'image_filename'               => UploadedFile::fake()->image('image1.jpg'),
                'description_1_image_filename' => UploadedFile::fake()->image('image2.jpg'),
                'description_2_image_filename' => UploadedFile::fake()->image('image3.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $tool = Tool::latest()->first();

        foreach (Tool::$images as $imageField) {
            $this->assertNotEmpty($tool->$imageField);
            $this->assertTrue(Storage::disk(Tool::$disk)->exists($tool->$imageField));

            Storage::disk(Tool::$disk)->delete($tool->$imageField);
        }
    }

    /** @test */
    public function files_other_than_images_can_not_be_uploaded(): void
    {
        $this
            ->actingAs($this->admin)
            ->post(route('content-manager.tool.store'), [
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
    public function after_creating_it_gets_the_concept_status(): void
    {
        $this
            ->actingAs($this->admin)
            ->post(route('content-manager.tool.store'), [
                'name'              => '::name::',
                'description_short' => '::description_short::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertEquals(Status::CONCEPT, Tool::latest()->first()->status);
    }

    /** @test */
    public function the_name_needs_to_be_unique(): void
    {
        Tool::factory()->create(['name' => '::name::']);

        $this
            ->actingAs($this->admin)
            ->post(route('content-manager.tool.store'), [
                'name' => '::name::',
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

        $this
            ->actingAs($this->admin)
            ->post(route('content-manager.tool.store'), [
                'name'              => '::name::',
                'description_short' => '::description_short::',

                'image_filename' => null,

                'features' => $attachedFeatures->pluck('id')->toArray(),

                'supported_standards'  => [SupportedStandard::CALIPER, SupportedStandard::QTI],
                'additional_standards' => '::additional_standards::',

                'authentication_methods' => [AuthenticationMethod::SSO, AuthenticationMethod::SURFCONEXT],

                'stored_data'       => [StoredData::USAGE_LOGGING],
                'other_stored_data' => '::other_stored_data::',

                'european_data_storage'           => true,
                'surf_standards_framework_agreed' => true,
                'has_processing_agreement'        => true,

                'description_long_1'           => '::description_long_1::',
                'description_1_image_filename' => null,

                'description_long_2'           => '::description_long_2::',
                'description_2_image_filename' => null,

                'info_url' => 'https://paqt.com',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertDatabaseHas('tools', [
            'name'              => '::name::',
            'description_short' => '::description_short::',
            'image_filename'    => null,

            'description_long_1'           => '::description_long_1::',
            'description_1_image_filename' => null,

            'description_long_2'           => '::description_long_2::',
            'description_2_image_filename' => null,

            'info_url' => 'https://paqt.com',

            'additional_standards' => '::additional_standards::',

            'other_stored_data' => '::other_stored_data::',

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
    public function a_guest_can_not_store_a_tool(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route('content-manager.tool.store'));
    }
}
