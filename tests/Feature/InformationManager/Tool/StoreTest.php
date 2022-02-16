<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Enums\InstituteTool\Status;
use App\Models\Category;
use App\Models\InstituteTool;
use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /** @test */
    public function can_be_done_without_additional_data(): void
    {
        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertTrue($this->informationManager->institute->tools()->find($tool)->exists());
    }

    /** @test */
    public function all_data_is_correctly_saved_in_the_pivot_table(): void
    {
        $tool = Tool::factory()->published()->create();

        $institute = $this->informationManager->institute;
        $categories = Category::factory()->count(2)->for($institute)->create();

        $data = [
            'categories'              => $categories->pluck('id')->sort()->toArray(),
            'description_1'           => ':: description_1 ::',
            'description_2'           => ':: description_2 ::',
            'extra_information_title' => ':: extra_information_title ::',
            'extra_information'       => ':: extra_information ::',
            'support_title_1'         => ':: support_title_1 ::',
            'support_email_1'         => 'assist@paqt.com',
            'support_title_2'         => ':: support_title_2 ::',
            'support_email_2'         => 'info@paqt.com',
            'manual_title_1'          => ':: manual_title_1 ::',
            'manual_url_1'            => 'https://manuals.com/summary',
            'manual_title_2'          => ':: manual_title_2 ::',
            'manual_url_2'            => 'https://manuals.com/extensive',
            'video_title_1'           => ':: video_title_1 ::',
            'video_url_1'             => 'https://youtube.com/12345678',
            'video_title_2'           => ':: video_title_2 ::',
            'video_url_2'             => 'https://youtube.com/87654321',
            'status'                  => Status::SUPPORTED,
        ];

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool), $data)

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertEquals(
            $data['categories'],
            $institute->categories()->forTool($tool)->orderBy('id')->pluck('id')->toArray()
        );

        unset($data['categories']);
        $this->assertDatabaseHas('institute_tool', $data);
    }

    /** @test */
    public function a_tool_can_only_be_added_when_it_is_published(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool));
    }

    /** @test */
    public function a_tool_can_only_be_added_once_per_institute(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool));
    }

    /** @test */
    public function a_teacher_can_not_store_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->post(route('information-manager.tool.store', $tool));
    }

    /** @test */
    public function a_guest_can_not_store_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route('information-manager.tool.store', $tool));
    }

    /** @test */
    public function images_can_be_uploaded(): void
    {
        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool), [
                'description_1_image_filename' => UploadedFile::fake()->image('image2.jpg'),
                'description_2_image_filename' => UploadedFile::fake()->image('image3.jpg'),
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $instituteTool = $this->informationManager->institute->tools()->find($tool)->pivot;

        foreach (InstituteTool::$images as $imageField) {
            $this->assertNotEmpty($instituteTool->$imageField);
            $this->assertTrue(Storage::disk(InstituteTool::$disk)->exists($instituteTool->$imageField));

            Storage::disk(InstituteTool::$disk)->delete($instituteTool->$imageField);
        }
    }

    /** @test */
    public function files_other_than_images_can_not_be_uploaded(): void
    {
        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tool.store', $tool), [
                'description_1_image_filename' => UploadedFile::fake()->image('file2.docx'),
                'description_2_image_filename' => UploadedFile::fake()->image('file3.exe'),
            ])

            ->assertSessionHasErrors([
                'description_1_image_filename' => trans('validation.image'),
                'description_2_image_filename' => trans('validation.image'),
            ]);
    }
}
