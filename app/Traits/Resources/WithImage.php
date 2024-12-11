<?php

declare(strict_types=1);

namespace App\Traits\Resources;

use App\Helpers\File;
use App\Models\Tool;

trait WithImage
{
    protected function getImageUrl(?string $image, bool $showPlaceholder = false): string | null
    {
        if ($image) {
            return File::getPublicUrl(Tool::$disk, $image);
        }

        return $showPlaceholder ? asset('dist/admin/images/placeholder.png') : null;
    }
}
