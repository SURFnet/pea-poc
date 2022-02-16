<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Institute;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @extends JsonResource<\App\Models\Institute> */
class InstituteResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter */
        $storage = Storage::disk(Institute::$disk);

        return [
            'id' => $this->id,

            'full_name'  => $this->full_name,
            'short_name' => $this->short_name,
            'domain'     => $this->domain,

            'logo_full_url'   => $storage->url($this->logo_full_filename),
            'logo_square_url' => $storage->url($this->logo_square_filename),
            'banner_url'      => $storage->url($this->banner_filename),

            'created_at' => $this->created_at->toW3cString(),
            'updated_at' => $this->updated_at->toW3cString(),
        ];
    }
}
