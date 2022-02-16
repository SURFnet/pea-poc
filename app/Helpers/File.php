<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File
{
    public static function store(UploadedFile $file, string $disk = 'local'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $newFilename = self::generateFilename($extension);

        $file->move(config('filesystems.disks.' . $disk . '.root'), $newFilename);

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
}
