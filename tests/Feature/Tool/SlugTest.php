<?php

declare(strict_types=1);

namespace Tests\Feature\Tool;

use App\Enums\InstituteTool\Status;
use App\Models\Tool;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class SlugTest extends TestCase
{
    /** @test */
    public function the_slugs_are_correct_after_creating_a_custom_tool(): void
    {
        $values = [
            'name'                 => ':name:',
            'description_short_en' => ':description_short_en:',
            'description_short_nl' => ':description_short_nl:',
            'logo_filename'        => UploadedFile::fake()->image('logo.jpg'),
        ];
        $instituteSlug = $this->getInstituteSlug($values['name']);

        $this->actingAs($this->informationManager)
            ->post(route('information-manager.custom-tool.store', ['continue' => false]), $values)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.custom-tool.index'));

        $createdTool = Tool::where('name', $values['name'])->latest()->first();

        $this->assertNotNull($createdTool);
        $this->assertSame($instituteSlug, $createdTool->slug);
        $this->assertSame($instituteSlug, $createdTool->institute_slug);
    }

    /** @test */
    public function the_slugs_are_correct_after_publishing_a_custom_tool(): void
    {
        $customTool = Tool::factory()
            ->customTool($this->informationManager->institute)
            ->create();

        $instituteSlug = $this->getInstituteSlug($customTool->name);

        $this->assertSlugsCorrectAfterSetup($instituteSlug, $customTool);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.publish', $customTool), ['status' => Status::ALLOWED])
            ->assertSessionDoesntHaveErrors();

        $customTool->refresh();
        $this->assertSame($instituteSlug, $customTool->slug);
        $this->assertSame($instituteSlug, $customTool->institute_slug);
    }

    /** @test */
    public function the_slugs_are_correct_after_converting_a_custom_tool(): void
    {
        $customTool = Tool::factory()
            ->customTool($this->informationManager->institute)
            ->published()
            ->create();

        $instituteSlug = $this->getInstituteSlug($customTool->name);

        $this->assertSlugsCorrectAfterSetup($instituteSlug, $customTool);

        $this->actingAs($this->contentManager)
            ->post(route('content-manager.tool.convert', $customTool))
            ->assertSessionDoesntHaveErrors();

        $customTool->refresh();

        $this->assertDatabaseHas('tools', [
            'id'             => $customTool->id,
            'slug'           => Str::slug($customTool->name),
            'institute_slug' => $instituteSlug,
        ]);
    }

    private function getInstituteSlug(string $name): string
    {
        return implode('-', [
            $this->informationManager->institute->short_name,
            Str::slug($name),
        ]);
    }

    private function assertSlugsCorrectAfterSetup(string $expectedSlug, Tool $customTool): void
    {
        $slugError = 'The setup created an incorrect slug';
        $instituteSlugError = 'The setup created an incorrect institute slug';

        $this->assertSame($expectedSlug, $customTool->slug, $slugError);
        $this->assertSame($expectedSlug, $customTool->institute_slug, $instituteSlugError);
    }
}
