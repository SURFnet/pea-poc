<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Locale;
use App\Http\Requests\Request;
use App\Models\ContentPage;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ContentPage
 */
class ContentPageResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'slug' => $this->slug,

            'title'    => Locale::getLocalizedFieldValue($this->resource, 'title'),
            'title_en' => $this->title_en,
            'title_nl' => $this->title_nl,

            'body'    => Locale::getLocalizedFieldValue($this->resource, 'body'),
            'body_en' => $this->body_en,
            'body_nl' => $this->body_nl,
        ];
    }
}
