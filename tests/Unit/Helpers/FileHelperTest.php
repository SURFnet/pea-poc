<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Helpers\File;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileHelperTest extends TestCase
{
    /**
     * @test
     *
     * Note: I would use the storage facade's faking here,
     * but since the function being tested does not use the storage facade, that doesn't work.
     */
    public function it_can_store_an_uploaded_file(): void
    {
        $file = UploadedFile::fake()->image('avatar.jpg');

        $newFilename = File::store($file, 'local');

        /** @var \Illuminate\Filesystem\FilesystemAdapter */
        $fileSystem = Storage::disk('local');

        $fileSystem->assertExists($newFilename);
        $fileSystem->delete($newFilename);
    }

    /** @test */
    public function it_can_generate_a_filename_based_on_the_current_datetime(): void
    {
        Carbon::setTestNow(Carbon::parse('2005-10-30 12:23:40'));

        $filename = File::generateFilename('csv');
        $timestamp = explode('_', $filename)[0];

        $this->assertEquals('20051030122340', $timestamp);
    }

    /** @test */
    public function it_can_generate_unique_filenames(): void
    {
        $filenames = [];

        for ($i = 1; $i <= 50; $i++) {
            $filenames[] = File::generateFilename('jpg');
        }

        // Check that all filenames are unique.
        $this->assertEquals($filenames, collect($filenames)->unique()->toArray());
    }

    /** @test */
    public function it_can_detect_that_a_file_exists(): void
    {
        Storage::fake();

        Storage::disk('local')->put('file.xlsx', 'some-file-content');

        $this->assertTrue(File::exists('file.xlsx', 'local'));
    }

    /** @test */
    public function it_can_detect_that_a_file_does_not_exist(): void
    {
        Storage::fake();

        $this->assertFalse(File::exists('bird.png', 'local'));
    }

    /** @test */
    public function it_can_delete_a_file(): void
    {
        Storage::fake();

        Storage::disk('local')->put('file.xlsx', 'some-file-content');

        File::delete('file.xlsx', 'local');

        $this->assertFalse(Storage::disk('local')->exists('file.xlsx'));
    }

    /** @test */
    public function it_can_get_contents_from_a_file(): void
    {
        Storage::fake();

        Storage::disk('local')->put('file.xlsx', 'some-file-content');

        $this->assertEquals('some-file-content', File::get('file.xlsx', 'local'));
    }

    /** @test */
    public function it_can_create_a_new_file(): void
    {
        Storage::fake();

        File::put('cats.csv', 'cat-1,cat-2', 'local');

        $this->assertEquals('cat-1,cat-2', Storage::disk('local')->get('cats.csv'));
    }

    /** @test */
    public function it_can_replace_contents_of_a_file(): void
    {
        Storage::fake();

        Storage::disk('local')->put('cats.csv', 'dog-1,dog-2');

        File::put('cats.csv', 'cat-1,cat-2', 'local');

        $this->assertEquals('cat-1,cat-2', Storage::disk('local')->get('cats.csv'));
    }
}
