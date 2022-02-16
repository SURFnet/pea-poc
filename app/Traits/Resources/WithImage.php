<?php

declare(strict_types=1);

namespace App\Traits\Resources;

use App\Models\Tool;
use Illuminate\Support\Facades\Storage;

trait WithImage
{
    private function getImageUrl(?string $image, bool $showPlaceholder = false): string | null
    {
        if ($image) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter */
            $storage = Storage::disk(Tool::$disk);

            return $storage->url($image);
        }

        return $showPlaceholder ? asset('dist/admin/images/placeholder.png') : null;
    }
}
