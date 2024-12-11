<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\File;
use App\Models\Institute;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Institute */
class InstituteResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'full_name'  => $this->full_name,
            'short_name' => $this->short_name,
            'domain'     => $this->domain,

            'logo_full_url'   => File::getPublicUrl(Institute::$disk, $this->logo_full_filename),
            'logo_square_url' => File::getPublicUrl(Institute::$disk, $this->logo_square_filename),
            'banner_url'      => File::getPublicUrl(Institute::$disk, $this->banner_filename),

            'homepage_title_en' => $this->homepage_title_en,
            'homepage_body_en'  => $this->homepage_body_en,
            'homepage_title_nl' => $this->homepage_title_nl,
            'homepage_body_nl'  => $this->homepage_body_nl,

            'created_at' => $this->created_at->toW3cString(),
            'updated_at' => $this->updated_at->toW3cString(),
        ];
    }
}
