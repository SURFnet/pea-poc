<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/** @mixin \App\Models\Tag */
class TagResource extends JsonResource
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

            'name'        => $this->name,
            'slug'        => $this->slug,
            'type'        => $this->type,
            'description' => $this->getTranslation('description', App::getLocale()),
        ];
    }
}
