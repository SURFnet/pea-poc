<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File
{
    public static function store(UploadedFile $file, string $disk): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $newFilename = self::generateFilename($extension);

        Storage::disk($disk)->putFileAs('/', $file, $newFilename);

        return $newFilename;
    }

    public static function storeFromPath(string $path, string $disk): string
    {
        $extension = Str::afterLast($path, '.');
        $newFilename = self::generateFilename($extension);

        Storage::disk($disk)->put($newFilename, file_get_contents($path));

        return $newFilename;
    }

    public static function generateFilename(string $extension = null): string
    {
        $timestamp = Carbon::now()->format(config('constants.format.timestamp'));

        $filename = uniqid($timestamp . '_');
        if ($extension) {
            $filename .= '.' . $extension;
        }

        return $filename;
    }

    public static function exists(string $filename, string $disk = 'local'): bool
    {
        return Storage::disk($disk)->exists($filename);
    }

    public static function delete(string $filename, string $disk = 'local'): bool
    {
        if (empty($filename)) {
            return true;
        }

        return Storage::disk($disk)->delete($filename);
    }

    public static function get(string $filename, string $disk = 'local'): string
    {
        return Storage::disk($disk)->get($filename);
    }

    public static function put(string $filename, string $content, string $disk = 'local'): void
    {
        Storage::disk($disk)->put($filename, $content);
    }

    public static function getPublicUrl(
        string $disk,
        string $fileName,
        ?DateTimeInterface $expiration = null
    ): string {
        /** @var FilesystemAdapter $storage */
        $storage = Storage::disk($disk);

        if (App::environment(config('constants.environment.development'))) {
            return $storage->url($fileName);
        }

        if ($expiration === null) {
            $expiration = now()->addDay();
        }

        return $storage->temporaryUrl($fileName, $expiration);
    }
}
