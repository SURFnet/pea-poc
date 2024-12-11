<?php

declare(strict_types=1);

namespace App\Http\Resources\InformationManager;

use App\Enums\Tags\TagTypes;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Tag */
class TagResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'name_array'        => $this->getTranslations('name'),
            'name_en'           => $this->getTranslation('name', 'en'),
            'name_nl'           => $this->getTranslation('name', 'nl', false),
            'description_array' => count($this->getTranslations('description')) > 0 ?
                $this->getTranslations('description') : ['nl' => null, 'en' => null],
            'description_en' => $this->getTranslation('description', 'en'),
            'description_nl' => $this->getTranslation('description', 'nl', false),
            'slug'           => $this->slug,
            'type'           => $this->type,
            'type_display'   => TagTypes::getTranslation($this->type),
            'institute_id'   => $this->institute_id,
            'order_column'   => $this->order_column,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
